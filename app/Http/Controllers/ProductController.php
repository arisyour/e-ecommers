<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends Controller
{
    // Tampilkan semua produk
    public function index(): JsonResponse
    {
        $products = Product::all();
        return response()->json($products, 200);
    }

    // Tampilkan satu produk berdasarkan ID
    public function show($id): JsonResponse
    {
        try {
            $product = Product::findOrFail($id);
            return response()->json($product, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Produk tidak ditemukan.'], 404);
        }
    }

    // Tambahkan produk baru
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'required|string|max:255',
        ]);

        $product = Product::create($request->only([
            'name', 'description', 'price', 'stock', 'category'
        ]));

        return response()->json([
            'message' => 'Produk berhasil ditambahkan.',
            'data' => $product
        ], 201);
    }

    // Update data produk
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $product = Product::findOrFail($id);

            $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'price' => 'sometimes|integer|min:0',
                'stock' => 'sometimes|integer|min:0',
                'category' => 'sometimes|string|max:255',
            ]);

            $product->update($request->only([
                'name', 'description', 'price', 'stock', 'category'
            ]));

            return response()->json([
                'message' => $product->wasChanged()
                    ? 'Produk berhasil diupdate.'
                    : 'Tidak ada perubahan pada data produk.',
                'data' => $product
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Produk tidak ditemukan.'], 404);
        }
    }

    // Hapus produk
    public function destroy($id): JsonResponse
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            return response()->json(['message' => 'Produk berhasil dihapus.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Produk tidak ditemukan.'], 404);
        }
    }
}