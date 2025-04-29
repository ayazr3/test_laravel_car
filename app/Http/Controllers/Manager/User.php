<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User as ModelsUser;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Storage;

class User extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = ModelsUser::get();
        return view('admin.manger_user.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.manger_user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'], // تغيير هنا
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', 'string', 'max:20'],
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $user = ModelsUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone ?? '0000000000',
            'location' => json_encode([
                'lat' => $request->lat,
                'lng' => $request->lng,
            ]),
            'images' => json_encode([]),
        ]);

        if ($request->hasFile('images')) {
            $uploadedImages = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('user/' . $user->id, 'public');
                $uploadedImages[] = $path;
            }
            $user->update(['images' => json_encode($uploadedImages)]);
        }

        return redirect()->route('manager.user.index')->with('success', 'User created successfully.');
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = ModelsUser::findOrFail($id);
        return view('admin.manger_user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = ModelsUser::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'required|string|max:20',
            // 'location' => 'required|string|max:255',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'password' => 'nullable|string|min:8|confirmed',
            // 'images' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'new_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            // 'location' => $request->location,
            'location' => json_encode([
                'lat' => $request->lat,
                'lng' => $request->lng,
            ]),
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        // معالجة حذف الصورة
    if ($request->has('delete_image')) {
        $oldImages = json_decode($user->images);
        foreach ($oldImages as $oldImage) {
            Storage::disk('public')->delete($oldImage);
        }
        $userData['images'] = json_encode([]);
    }

    // معالجة الصورة الجديدة
    if ($request->hasFile('new_image')) {
        // حذف الصورة القديمة إذا وجدت
        if ($user->images) {
            $oldImages = json_decode($user->images);
            foreach ($oldImages as $oldImage) {
                Storage::disk('public')->delete($oldImage);
            }
        }

        $imagePath = $request->file('new_image')->store('users/'.$user->id, 'public');
        $userData['images'] = json_encode([$imagePath]);
    }

        // if ($request->hasFile('images')) {
        //     // Delete old images if exist
        //     if ($user->images) {
        //         $oldImages = json_decode($user->images);
        //         foreach ($oldImages as $oldImage) {
        //             Storage::disk('public')->delete($oldImage);
        //         }
        //     }

        //     $imagePath = $request->file('images')->store('users', 'public');
        //     $userData['images'] = json_encode([$imagePath]);
        // }

        $user->update($userData);

        return redirect()->route('manager.user.index')->with('success', 'User updated successfully.');
    }

    public function toggleRole(Request $request, string $id)
    {
        $user = ModelsUser::findOrFail($id);

        $newRole = $user->role === 'admin' ? 'vendor' : 'admin';
        $user->update(['role' => $newRole]);

        return back()->with('success', 'User role changed successfully.');
    }

    public function toggleStatus(Request $request, string $id)
    {
        $user = ModelsUser::findOrFail($id);
        $newStatus = !$user->status;
        $user->update(['status' => $newStatus]);
        // إذا تم تعطيل الحساب، نرسل event لتسجيل الخروج
        if ($newStatus === false) {
            event(new \Illuminate\Auth\Events\Logout('web', $user));
        }
        return back()->with('success', 'User status updated to '. ($newStatus ? 'Active' : 'Inactive'));
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = ModelsUser::findOrFail($id);

        // Delete user images if exist
        if ($user->images) {
            $images = json_decode($user->images);
            foreach ($images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $user->delete();

        return redirect()->route('manager.user.index')->with('success', 'User deleted successfully.');
    }
}
