<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Banner;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class settingController extends Controller
{
    public function index()
    {
        $setting = Setting::first(); // Assuming you have a Setting model
        $banners = Banner::all();
        $admins = Admin::all();
        return view('admin.setting', compact('setting', 'banners', 'admins'));
    }
    public function updateTitleSistem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title_sistem' => 'required|string|max:255',
        ], [
            'title_sistem.required' => 'Title sistem is required.',
            'title_sistem.max' => 'Title sistem cannot exceed 255 characters.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $setting = Setting::firstOrFail();
        $setting->update(['title_sistem' => $request->title_sistem]);

        return response()->json(['success' => true, 'message' => 'Title sistem updated successfully']);
    }

    public function updateNamaPerusahaan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_perusahaan' => 'required|string|max:255',
        ], [
            'nama_perusahaan.required' => 'Nama perusahaan is required.',
            'nama_perusahaan.max' => 'Nama perusahaan cannot exceed 255 characters.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $setting = Setting::firstOrFail();
        $setting->update(['nama_perusahaan' => $request->nama_perusahaan]);

        return response()->json(['success' => true, 'message' => 'Nama perusahaan updated successfully']);
    }

    public function updateAlamatPerusahaan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'alamat_perusahaan' => 'required|string',
        ], [
            'alamat_perusahaan.required' => 'Alamat perusahaan is required.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $setting = Setting::firstOrFail();
        $setting->update(['alamat_perusahaan' => $request->alamat_perusahaan]);

        return response()->json(['success' => true, 'message' => 'Alamat perusahaan updated successfully']);
    }

    public function updateNomorWa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomor_wa' => 'required|string|regex:/^\+?[0-9]{10,20}$/',
        ], [
            'nomor_wa.required' => 'Nomor WhatsApp is required.',
            'nomor_wa.regex' => 'Nomor WhatsApp must be a valid phone number (10-20 digits, optional + prefix).',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $setting = Setting::firstOrFail();
        $setting->update(['nomor_wa' => $request->nomor_wa]);

        return response()->json(['success' => true, 'message' => 'Nomor WhatsApp updated successfully']);
    }

    // Banner methods (from previous response)
    public function storeBanner(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|max:2048', // Max 2MB
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $data = $request->only(['title', 'description', 'is_active']);
        
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('banners'), $filename);
            $data['image'] = $filename;
        }

        Banner::create($data);

        return response()->json(['success' => true, 'message' => 'Banner added successfully']);
    }

    public function updateBanner(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:banners,id',
            'image' => 'nullable|image|max:2048',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $banner = Banner::findOrFail($request->id);
        $data = $request->only(['title', 'description', 'is_active']);

        if ($request->hasFile('image')) {
            if ($banner->image && File::exists(public_path('banners/' . $banner->image))) {
                File::delete(public_path('banners/' . $banner->image));
            }
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('banners'), $filename);
            $data['image'] = $filename;
        }

        $banner->update($data);

        return response()->json(['success' => true, 'message' => 'Banner updated successfully']);
    }

    public function deleteBanner($id)
    {
        $banner = Banner::findOrFail($id);
        if ($banner->image && File::exists(public_path('banners/' . $banner->image))) {
            File::delete(public_path('banners/' . $banner->image));
        }
        $banner->delete();

        return redirect()->back()->with('success', 'Banner deleted successfully');
    }

    // Admin methods
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:6',
            'is_active' => 'required|boolean',
        ]);

        $data = $request->only(['name', 'email', 'is_active']);
        $data['password'] = Hash::make($request->password);

        Admin::create($data);

        return response()->json(['success' => true, 'message' => 'Admin added successfully']);
    }

    public function updateAdmin(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:admins,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $request->id,
            'password' => 'nullable|string|min:6',
            'is_active' => 'required|boolean',
        ]);

        $admin = Admin::findOrFail($request->id);
        $data = $request->only(['name', 'email', 'is_active']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return response()->json(['success' => true, 'message' => 'Admin updated successfully']);
    }

    public function deleteAdmin($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->delete();

        return redirect()->back()->with('success', 'Admin deleted successfully');
    }
}