<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\CarComplaintNotification;

class ReviewController extends Controller
{
    // عرض صفحة إنشاء شكوى أو اقتراح
    public function create($carId = null)
    {
        $car = null;
        if ($carId) {
            $car = Car::findOrFail($carId);
        }

        return view('reviews.create', compact('car'));
    }

    // حفظ الشكوى أو الاقتراح
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'note' => 'required|string|max:1000',
            'id_car' => 'nullable|exists:cars,id'
        ]);

        $review = Review::create([
            'email' => $request->email,
            'note' => $request->note,
            'id_car' => $request->id_car,
            'is_public' => false
        ]);

        // إذا كانت الشكوى متعلقة بسيارة، نرسل إشعارًا للبائع
        if ($review->id_car) {
            $car = Car::with('user')->find($review->id_car);
            if ($car && $car->user) {
                Mail::to($car->user->email)->send(new CarComplaintNotification($review, $car));
            }
        }

        return redirect()->back()->with('success', 'تم استلام شكواك/اقتراحك بنجاح وسيتم مراجعته من قبل الإدارة');
    }

    // عرض جميع الشكاوي والاقتراحات (للإدارة)
    public function index()
    {
        $this->authorize('viewAny', Review::class);

        $reviews = Review::with('car')->latest()->paginate(10);
        return view('reviews.index', compact('reviews'));
    }

    // تغيير حالة النشر
    public function togglePublish(Review $review)
    {
        $this->authorize('update', $review);

        $review->update(['is_public' => !$review->is_public]);
        return back()->with('success', 'تم تحديث حالة النشر بنجاح');
    }

    // حذف شكوى أو اقتراح
    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);

        $review->delete();
        return back()->with('success', 'تم الحذف بنجاح');
    }
}
