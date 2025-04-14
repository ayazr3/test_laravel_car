<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$cars = Car::where('user_id', auth()->id())->latest()->get();
        $cars = Car::where('user_id', auth()->id())->latest()->paginate(10);
        return view('car.index',compact('cars'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('car.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'description' => 'required|string',
            'color' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        // رفع الصور
        $uploadedImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('car_images', 'public');
                $uploadedImages[] = $path;
            }
        }

        // إنشاء السيارة
        $car = Car::create([
            'user_id' => Auth::id(),
            'brand' => $request->brand,
            'model' => $request->model,
            'year' => $request->year,
            'price' => $request->price,
            'currency' => $request->currency,
            'description' => $request->description,
            'color' => $request->color,
            'images' => $uploadedImages,
            'location' => [
                'lat' => $request->lat,
                'lng' => $request->lng,
            ],
        ]);

        return redirect()->route('car.index')->with('success', 'تمت إضافة السيارة بنجاح!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Car $car)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Car $car)
    {
        // تحقق أن المستخدم هو صاحب السيارة
        if ($car->user_id != auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // تحويل location من JSON إلى array إذا لزم الأمر
        if (is_string($car->location)) {
            $car->location = json_decode($car->location, true);
        }

        return view('car.edit', compact('car'));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Car $car)
    {
        if ($car->user_id != auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'description' => 'required|string',
            'color' => 'required|string',
            'new_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        // معالجة الصور الحالية
        $currentImages = $request->existing_images ?? $car->images;

        // حذف الصور المحددة
        if ($request->has('deleted_images')) {
            foreach ($request->deleted_images as $index) {
                if (isset($car->images[$index])) {
                    Storage::disk('public')->delete($car->images[$index]);
                }
            }
            $currentImages = array_values(array_diff_key($currentImages, array_flip($request->deleted_images)));
        }

        // رفع الصور الجديدة
        $newImages = [];
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $image) {
                $path = $image->store('cars/' . $car->id, 'public');
                $newImages[] = $path;
            }
        }

        // تحديث جميع البيانات
        $car->update([
            'brand' => $validated['brand'],
            'model' => $validated['model'],
            'year' => $validated['year'],
            'price' => $validated['price'],
            'currency' => $validated['currency'],
            'description' => $validated['description'],
            'color' => $validated['color'],
            'images' => array_merge($currentImages, $newImages),
            'location' => [
                'lat' => $validated['lat'],
                'lng' => $validated['lng'],
            ],
        ]);

        return redirect()->route('car.index')->with('success', 'تم تحديث السيارة بنجاح!');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car)
    {
        if ($car->user_id != auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // حذف الصور من التخزين (اختياري)
        foreach ($car->images as $image) {
            Storage::disk('public')->delete($image);
        }

        $car->delete();
        return redirect()->route('car.index')->with('success', 'تم حذف السيارة بنجاح!');
    }

    public function toggleSoldStatus(Car $car)
    {

        // التحقق من أن المستخدم هو صاحب السيارة
        if ($car->user_id != auth()->id()) {
            abort(403, 'Unauthorized');
        }

        abort_if($car->user_id != auth()->id(), 403);

        // طريقة أكثر دقة للتحديث
        $car->sold = !$car->sold;
        $car->save();

        return back()->with('success', 'تم التحديث بنجاح');
    }
    public function viewAll() {
        $cars = Car::with('user') // تحميل علاقة المستخدم مسبقاً
              ->where('sold', false)
              ->latest()
              ->paginate(10); // أو ->get() إذا كنت لا تريد التقسيم

    return view('welcome', compact('cars'));
    }
}
