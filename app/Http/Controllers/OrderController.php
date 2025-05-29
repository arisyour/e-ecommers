<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderController extends Controller
{
    // Tampilkan semua order
    public function index(): JsonResponse
    {
        $orders = Order::with('customer')->get(); // include relasi customer
        return response()->json($orders, 200);
    }

    // Tampilkan order berdasarkan ID
    public function show($id): JsonResponse
    {
        try {
            $order = Order::with('customer')->findOrFail($id);
            return response()->json($order, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Order tidak ditemukan.'], 404);
        }
    }

    // Tambahkan order baru
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_date' => 'required|date',
            'total_amount' => 'required|integer|min:0',
            'status' => 'required|string|max:50',
        ]);

        $order = Order::create([
            'customer_id' => $request->customer_id,
            'order_date' => $request->order_date,
            'total_amount' => $request->total_amount,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'Order berhasil dibuat.',
            'data' => $order
        ], 201);
    }

    // Update data order
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $order = Order::findOrFail($id);

            $request->validate([
                'customer_id' => 'sometimes|exists:customers,id',
                'order_date' => 'sometimes|date',
                'total_amount' => 'sometimes|integer|min:0',
                'status' => 'sometimes|string|max:50',
            ]);

            $order->update($request->only(['customer_id', 'order_date', 'total_amount', 'status']));

            return response()->json([
                'message' => $order->wasChanged()
                    ? 'Order berhasil diperbarui.'
                    : 'Tidak ada perubahan pada order.',
                'data' => $order
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Order tidak ditemukan.'], 404);
        }
    }

    // Hapus order
    public function destroy($id): JsonResponse
    {
        try {
            $order = Order::findOrFail($id);
            $order->delete();

            return response()->json(['message' => 'Order berhasil dihapus.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Order tidak ditemukan.'], 404);
        }
    }
}