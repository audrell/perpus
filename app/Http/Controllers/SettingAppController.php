<?php

namespace App\Http\Controllers;

use App\Models\SettingApp;
use Illuminate\Http\Request;

class SettingAppController extends Controller
{
    public function index()
    {
        $setting = SettingApp::first();
        return view('settings.index', compact('setting'));
    }

    public function store(Request $request)
    {
        // Hanya boleh 1 data
        if (SettingApp::count() > 0) {
            return redirect()->route('settings.index')->with('error', 'Setting sudah ada, silakan edit.');
        }

        $request->validate([
            'name_app'      => 'required|string|max:255',
            'short_cut_app' => 'required|string|max:50',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only(['name_app', 'short_cut_app']);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '-' . $file->getClientOriginalName();
            $storageDir = public_path('storage');
            $destinationPath = public_path('storage/uploads/logos');

            // Pastikan folder uploads/logos ada
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $filename);
            $data['image'] = $filename;
        }

        SettingApp::create($data);

        return redirect()->route('settings.index')->with('success', 'Setting berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        $setting = SettingApp::findOrFail($id);

        $request->validate([
            'name_app'      => 'required|string|max:255',
            'short_cut_app' => 'required|string|max:50',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only(['name_app', 'short_cut_app']);

        if ($request->hasFile('image')) {
            // Hapus file lama jika ada
            if (!empty($setting->image) && file_exists(public_path('storage/uploads/logos/' . $setting->image))) {
                unlink(public_path('storage/uploads/logos/' . $setting->image));
            }

            // Simpan file baru ke dalam public/storage/uploads/logos
            $file = $request->file('image');
            $filename = time() . '-' . $file->getClientOriginalName();
            $storageDir = public_path('storage');
            $destinationPath = public_path('storage/uploads/logos');

            // Pastikan folder storage ada
            if (!is_dir($storageDir)) {
                mkdir($storageDir, 0755, true);
            }

            // Pastikan folder uploads/logos ada
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $filename);
            $data['image'] = $filename;
        }

        $setting->update($data);

        return redirect()->route('settings.index')->with('success', 'Setting berhasil diperbarui.');
    }
}
