<?php

namespace App\Http\Controllers;

use App\Models\Ads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $advs = Ads::with('user')->latest()->get();
        return view('admin.manager_advertisement.index', compact('advs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.manager_advertisement.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'url' => 'required|url',
            'email' => 'required|email',
            'phone' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $adData = [
            'fullname' => $request->fullname,
            'url' => $request->url,
            'email' => $request->email,
            'phone' => $request->phone,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'location' => [
                'lat' => $request->lat,
                'lng' => $request->lng,
            ],
            'hit' => 0, // تعيين قيمة افتراضية
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('ads', 'public');
            $adData['image'] = $path;
        }

        Ads::create($adData);

        return redirect()->route('manager.ads.index')
            ->with('success', 'Advertisement created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ad = Ads::findOrFail($id);
        return view('admin.manager_advertisement.show', compact('ad'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ad = Ads::findOrFail($id);
        return view('admin.manager_advertisement.edit', compact('ad'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ad = Ads::findOrFail($id);

        $request->validate([
            'fullname' => 'required|string|max:255',
            'url' => 'required|url',
            'email' => 'required|email',
            'phone' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'address' => 'nullable|string'
        ]);

        $adData = [
            'fullname' => $request->fullname,
            'url' => $request->url,
            'email' => $request->email,
            'phone' => $request->phone,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'location' => [
                'lat' => $request->lat,
                'lng' => $request->lng,
                'address' => $request->address
            ]
        ];

        if ($request->has('delete_image') && $ad->image) {
            Storage::delete('public/' . $ad->image);
            $adData['image'] = null;
        }

        if ($request->hasFile('image')) {
            if ($ad->image) {
                Storage::delete('public/' . $ad->image);
            }
            $path = $request->file('image')->store('ads', 'public');
            $adData['image'] = $path;
        }

        $ad->update($adData);

        return redirect()->route('manager.ads.index')
            ->with('success', 'Advertisement updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ad = Ads::findOrFail($id);

        // حذف الصورة إذا وجدت
        if ($ad->image) {
            Storage::delete('public/' . $ad->image);
        }

        $ad->delete();

        return back()->with('success', 'Advertisement deleted successfully');
    }

    public function redirectAd($id)
    {
        $ad = Ads::findOrFail($id);

        // زيادة عداد الزيارات
        $ad->increment('hit');

        // إعادة التوجيه إلى الرابط الأصلي للإعلان
        return redirect()->away($ad->url);
    }
    public function toggleAdVisibility($id)
    {
        $ad = Ads::findOrFail($id);
        $new = ['is_public' => !$ad->is_public];
        $ad->update($new);

        //dd($ad);
        return back()->with('success', 'تم تحديث حالة الإعلان');
    }

}
