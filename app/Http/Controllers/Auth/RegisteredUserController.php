<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', 'string', 'max:20'],
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB كحد أقصى
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',

        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone ?? '0000000000',
            'location' => json_encode([ // حول المصفوفة إلى JSON
            'lat' => $request->lat,
            'lng' => $request->lng,
            ]),
            'images' => json_encode([]), // مصفوفة فارغة للصور

    ]);

    // رفع الصور إذا وجدت
    if ($request->hasFile('images')) {
        $uploadedImages = [];

        foreach ($request->file('images') as $image) {
            $path = $image->store('user/' . $user->id, 'public');
            $uploadedImages[] = $path;
        }

        // تحديث حقل الصور
        $user->update(['images' => json_encode($uploadedImages)]);

    }

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
     }
}
