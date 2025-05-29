<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderItemController extends Controller
{
    // Tampilkan semua item pesanan
    public function index(): JsonResponse
    {
        $items = OrderItem::with(['order', 'product'])->get();
        return response()->json($items, 200);
    }

    // Tampilkan item berdasarkan ID
    public function show($id): JsonResponse
    {
        try {
            $item = OrderItem::with(['order', 'product'])->findOrFail($id);
            return response()->json($item, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Item pesanan tidak ditemukan.'], 404);
        }
    }

    // Tambah item baru
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|integer|min:0',
        ]);

        $item = OrderItem::create($request->only([
            'order_id', 'product_id', 'quantity', 'price'
        ]));

        return response()->json([
            'message' => 'Item pesanan berhasil ditambahkan.',
            'data' => $item
        ], 201);
    }

    // Update item
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $item = OrderItem::findOrFail($id);

            $request->validate([
                'order_id' => 'sometimes|exists:orders,id',
                'product_id' => 'sometimes|exists:products,id',
                'quantity' => 'sometimes|integer|min:1',
                'price' => 'sometimes|integer|min:0',
            ]);

            $item->update($request->only([
                'order_id', 'product_id', 'quantity', 'price'
            ]));

            return response()->json([
                'message' => $item->wasChanged()
                    ? 'Item pesanan berhasil diperbarui.'
                    : 'Tidak ada perubahan.',
                'data' => $item
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Item tidak ditemukan.'], 404);
        }
    }

    // Hapus item
    public function destroy($id): JsonResponse
    {
        try {
            $item = OrderItem::findOrFail($id);
            $item->delete();

            return response()->json(['message' => 'Item berhasil dihapus.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Item tidak ditemukan.'], 404);
        }
    }
}