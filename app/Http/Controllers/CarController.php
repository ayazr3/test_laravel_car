<?php

namespace App\Http\Controllers;

use App\Models\Ads;
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
            'status' => 'pending', // يتم إضافتها كطلب جديد يحتاج موافقة
        ]);

        return redirect()->route('car.index')->with('success', 'تم تقديم طلب الإعلان بنجاح، بانتظار الموافقة من الإدارة');
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
        $currentImages = $request->existing_images ?? [];

        // حذف الصور المحددة
        if ($request->has('deleted_images')) {
            $deletedIndices = $request->deleted_images;
            $imagesToKeep = [];

            foreach ($car->images as $index => $image) {
                if (!in_array($index, $deletedIndices)) {
                    $imagesToKeep[] = $image;
                } else {
                    Storage::disk('public')->delete($image);
                }
            }

            $currentImages = $imagesToKeep;
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

        // السيارات العادية (غير المميزة وغير المباعة)
        $regularCars = Car::where('sold', false)

            ->whereHas('user', function($query) {
                $query->where('status', true); // إذا كان لديك حقل لحالة المستخدم
            })
            ->with('user')
            ->latest()
            ->paginate(9);

        // الإعلانات المميزة (المعتمدة والمنشورة)
        $ads = Ads::where('is_public', 1)
            ->where('end_date', '>=', now())
            ->with(['car', 'user']) // تأكد من وجود هذه العلاقات
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

// dd($ads);
        return view('welcome', compact('regularCars', 'ads'));
    //     $regularCars = Car::where('sold', false)
    //     ->where('is_featured', false)
    //     ->latest()
    //     ->paginate(9);

    //     $ads = Ads::where('is_public', true)
    //         ->where('end_date', '>=', now())
    //         ->orderBy('created_at', 'desc')
    //         ->take(6)
    //         ->get();

    // return view('welcome', compact('regularCars', 'ads'));
    }
    // طلب إعلان مميز
    public function requestFeatured($id)
    {
        $car = Car::where('user_id', auth()->id())->findOrFail($id);

        $car->update([
            'featured_status' => 'pending',
            'rejection_reason' => null
        ]);

        return back()->with('success', 'تم إرسال طلب الإعلان المميز بنجاح');
    }

    // عرض طلبات الإعلانات المميزة للأدمن
    public function featuredRequests()
    {
        $cars = Car::where('featured_status', 'pending')->with('user')->paginate(10);
        return view('admin.featured-requests', compact('cars'));
    }

    // موافقة الأدمن على الإعلان المميز
    public function approveFeatured($id)
    {
        $car = Car::with('user')->findOrFail($id);

        // إنشاء إعلان جديد
        $ad = Ads::create([
            'fullname' => $car->user->name,
            'image' => $car->images[0] ?? null,
            'url' => route('cars.details', $car->id),
            'hit' => 0,
            'start_date' => now(),
            'end_date' => now()->addDays(30),
            'location' => $car->location,
            'email' => $car->user->email,
            'phone' => $car->user->phone,
            'is_public' => true,
            'car_id' => $car->id, // ربط الإعلان بالسيارة
        ]);

        // تحديث حالة السيارة
        $car->update([
            'is_featured' => true,
            'featured_status' => 'approved'
        ]);

        return back()->with('success', 'تمت الموافقة على الإعلان بنجاح');
    }

    // رفض الأدمن للإعلان المميز
    public function rejectFeatured(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:255']);

        $car = Car::findOrFail($id);
        $car->update([
            'is_featured' => false,
            'featured_status' => 'rejected',
            'rejection_reason' => $request->reason
        ]);

        return back()->with('success', 'تم رفض طلب الإعلان المميز');
    }
    // public function pendingCars()
    // {
    //     $cars = Car::where('status', 'pending')->latest()->paginate(10);
    //     return view('admin.manager_cars.pending', compact('cars'));
    // }

    // public function reviewCar($id)
    // {
    //     $car = Car::with('user')->findOrFail($id);
    //     return view('admin.manager_cars.review', compact('car'));
    // }

    // public function approveCar(Request $request, $id)
    // {
    //     $car = Car::findOrFail($id);

    //     $car->update([
    //         'status' => 'approved',
    //         'admin_notes' => null,
    //     ]);

    //     return redirect()->route('admin.cars.pending')
    //         ->with('success', 'Car advertisement approved successfully');
    // }

    // public function rejectCar(Request $request, $id)
    // {
    //     $request->validate([
    //         'admin_notes' => 'required|string|max:500',
    //     ]);

    //     $car = Car::findOrFail($id);

    //     $car->update([
    //         'status' => 'rejected',
    //         'admin_notes' => $request->admin_notes,
    //     ]);

    //     return redirect()->route('admin.cars.pending')
    //         ->with('success', 'Car advertisement rejected');
    // }
}
