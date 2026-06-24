<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PointEarningConfiguration;
use Illuminate\Http\Request;

class AdminLoyaltyController extends Controller
{
    /**
     * Menampilkan daftar konfigurasi point earning
     */
    public function index()
    {
        $configurations = PointEarningConfiguration::latest()->paginate(10);
        return view('admin.loyalty.index', compact('configurations'));
    }

    /**
     * Menampilkan form create
     */
    public function create()
    {
        return view('admin.loyalty.create');
    }

    /**
     * Menyimpan konfigurasi baru
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'min_purchase_amount' => 'required|numeric|min:0',
            'max_purchase_amount' => 'nullable|numeric|min:0',
            'points_earned' => 'required|integer|min:1',
            'description' => 'nullable|string|max:500',
            'is_active' => 'sometimes|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        // Validasi bahwa max tidak kurang dari min
        if (!empty($data['max_purchase_amount']) && $data['max_purchase_amount'] < $data['min_purchase_amount']) {
            return redirect()->back()->withErrors(['max_purchase_amount' => 'Max amount harus lebih besar dari min amount']);
        }

        PointEarningConfiguration::create($data);

        return redirect()->route('admin.loyalty.configurations.index')->with('success', 'Konfigurasi point berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit
     */
    public function edit(PointEarningConfiguration $configuration)
    {
        return view('admin.loyalty.edit', compact('configuration'));
    }

    /**
     * Update konfigurasi
     */
    public function update(Request $request, PointEarningConfiguration $configuration)
    {
        $data = $request->validate([
            'min_purchase_amount' => 'required|numeric|min:0',
            'max_purchase_amount' => 'nullable|numeric|min:0',
            'points_earned' => 'required|integer|min:1',
            'description' => 'nullable|string|max:500',
            'is_active' => 'sometimes|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        // Validasi bahwa max tidak kurang dari min
        if (!empty($data['max_purchase_amount']) && $data['max_purchase_amount'] < $data['min_purchase_amount']) {
            return redirect()->back()->withErrors(['max_purchase_amount' => 'Max amount harus lebih besar dari min amount']);
        }

        $configuration->update($data);

        return redirect()->route('admin.loyalty.configurations.index')->with('success', 'Konfigurasi point berhasil diperbarui.');
    }

    /**
     * Hapus konfigurasi
     */
    public function destroy(PointEarningConfiguration $configuration)
    {
        $configuration->delete();
        return redirect()->route('admin.loyalty.configurations.index')->with('success', 'Konfigurasi point berhasil dihapus.');
    }

    /**
     * Toggle status konfigurasi
     */
    public function toggleStatus(PointEarningConfiguration $configuration)
    {
        $configuration->update(['is_active' => !$configuration->is_active]);
        return redirect()->route('admin.loyalty.configurations.index')->with('success', 'Status konfigurasi berhasil diubah.');
    }
}
