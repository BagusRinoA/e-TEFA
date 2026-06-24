<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RedemptionItem;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;

/**
 * EXAMPLE: AdminRedemptionController dengan ImageUploadService
 *
 * Gunakan contoh ini untuk mengupdate AdminRedemptionController asli Anda
 */
class AdminRedemptionControllerExample extends Controller
{
    protected ImageUploadService $imageUploadService;

    public function __construct(ImageUploadService $imageUploadService)
    {
        $this->imageUploadService = $imageUploadService;
    }

    public function index()
    {
        $items = RedemptionItem::paginate(12);
        return view('admin.redemptions.index', compact('items'));
    }

    public function create()
    {
        return view('admin.redemptions.create');
    }

    /**
     * STORE: Handle redemption item creation dengan gambar
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'points_required' => 'required|integer|min:1',
            'stock' => 'required|integer|min:0',
            'category' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:10240',
        ]);

        // Handle image upload dengan optimasi
        if ($request->hasFile('image')) {
            try {
                $data['image_url'] = $this->imageUploadService->upload(
                    $request->file('image'),
                    'redemption-items'
                );
            } catch (\Exception $e) {
                return redirect()->back()->withError('Gagal mengupload gambar: ' . $e->getMessage());
            }
        }

        RedemptionItem::create($data);
        return redirect()->route('admin.redemptions.index')->with('success', 'Item hadiah berhasil ditambahkan');
    }

    public function edit(RedemptionItem $item)
    {
        return view('admin.redemptions.edit', compact('item'));
    }

    /**
     * UPDATE: Handle redemption item update dengan gambar
     */
    public function update(Request $request, RedemptionItem $item)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'points_required' => 'required|integer|min:1',
            'stock' => 'required|integer|min:0',
            'category' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:10240',
        ]);

        // Handle image update
        if ($request->hasFile('image')) {
            try {
                // Hapus gambar lama jika ada
                if ($item->image_url) {
                    $this->imageUploadService->delete($item->image_url);
                }

                // Upload gambar baru
                $data['image_url'] = $this->imageUploadService->upload(
                    $request->file('image'),
                    'redemption-items'
                );
            } catch (\Exception $e) {
                return redirect()->back()->withError('Gagal mengupload gambar: ' . $e->getMessage());
            }
        }

        $item->update($data);
        return redirect()->route('admin.redemptions.index')->with('success', 'Item hadiah berhasil diperbarui');
    }

    /**
     * DELETE: Handle redemption item deletion dengan cleanup gambar
     */
    public function destroy(RedemptionItem $item)
    {
        // Hapus gambar dari storage
        if ($item->image_url) {
            $this->imageUploadService->delete($item->image_url);
        }

        $item->delete();
        return redirect()->route('admin.redemptions.index')->with('success', 'Item hadiah berhasil dihapus');
    }
}
