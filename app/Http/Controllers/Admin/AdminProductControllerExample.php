<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;

/**
 * EXAMPLE: AdminProductController dengan ImageUploadService
 *
 * Gunakan contoh ini untuk mengupdate AdminProductController asli Anda
 */
class AdminProductControllerExample extends Controller
{
    protected ImageUploadService $imageUploadService;

    public function __construct(ImageUploadService $imageUploadService)
    {
        $this->imageUploadService = $imageUploadService;
    }

    public function index()
    {
        $products = Product::paginate(12);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * STORE: Handle product creation dengan gambar
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:10240',
            'sku' => 'nullable|string|unique:products,sku',
        ]);

        // Handle image upload dengan optimasi
        if ($request->hasFile('image')) {
            try {
                $data['image'] = $this->imageUploadService->upload(
                    $request->file('image'),
                    'products'
                );
            } catch (\Exception $e) {
                return redirect()->back()->withError('Gagal mengupload gambar produk: ' . $e->getMessage());
            }
        }

        Product::create($data);
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * UPDATE: Handle product update dengan gambar
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:10240',
            'sku' => 'nullable|string|unique:products,sku,' . $product->id,
        ]);

        // Handle image update
        if ($request->hasFile('image')) {
            try {
                // Hapus gambar lama jika ada
                if ($product->image) {
                    $this->imageUploadService->delete($product->image);
                }

                // Upload gambar baru
                $data['image'] = $this->imageUploadService->upload(
                    $request->file('image'),
                    'products'
                );
            } catch (\Exception $e) {
                return redirect()->back()->withError('Gagal mengupload gambar produk: ' . $e->getMessage());
            }
        }

        $product->update($data);
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui');
    }

    /**
     * DELETE: Handle product deletion dengan cleanup gambar
     */
    public function destroy(Product $product)
    {
        // Hapus gambar dari storage
        if ($product->image) {
            $this->imageUploadService->delete($product->image);
        }

        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus');
    }
}
