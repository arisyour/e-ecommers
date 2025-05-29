<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CustomerController extends Controller
{
    // Tampilkan semua customer
    public function index(): JsonResponse
    {
        $customers = Customer::all();
        return response()->json($customers, 200);
    }

    // Tampilkan customer berdasarkan ID
    public function show($id): JsonResponse
    {
        try {
            $customer = Customer::findOrFail($id);
            return response()->json($customer, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Customer tidak ditemukan.'], 404);
        }
    }

    // Tambah customer baru
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return response()->json([
            'message' => 'Customer berhasil ditambahkan.',
            'data' => $customer
        ], 201);
    }

    // Update data customer
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $customer = Customer::findOrFail($id);

            $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:customers,email,' . $id,
                'password' => 'sometimes|string|min:8',
                'phone' => 'sometimes|string|max:20',
                'address' => 'sometimes|string',
            ]);

            $data = $request->only(['name', 'email', 'password', 'phone', 'address']);
            if (isset($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            }

            $customer->update($data);

            return response()->json([
                'message' => $customer->wasChanged()
                    ? 'Customer berhasil diperbarui.'
                    : 'Tidak ada perubahan pada data customer.',
                'data' => $customer
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Customer tidak ditemukan.'], 404);
        }
    }

    // Hapus customer
    public function destroy($id): JsonResponse
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->delete();

            return response()->json(['message' => 'Customer berhasil dihapus.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Customer tidak ditemukan.'], 404);
        }
    }
}