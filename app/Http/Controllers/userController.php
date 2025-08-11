<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class userController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.user', compact('users')); // Adjust to 'master-user' if needed
    }

    public function data()
    {
        $users = User::all()->map(function ($item) {
            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'nik' => $item->nik,
                'email' => $item->email,
                'alamat' => $item->alamat,
                'status' => $item->status,
                'created_at' => $item->created_at->format('d M Y H:i'),
            ];
        });
        return response()->json($users);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'nik' => 'required|numeric|digits:16|unique:users,nik',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'alamat' => 'required|string',
            'status' => 'required|in:active,inactive',
        ], [
            'nik.digits' => 'The NIK must be exactly 16 digits.',
            'nik.numeric' => 'The NIK must contain only numbers.',
            'nik.unique' => 'The NIK has already been taken.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $data = $request->only('nama', 'nik', 'email', 'alamat', 'status');
        $data['password'] = Hash::make($request->password);
        User::create($data);

        return response()->json(['success' => true, 'message' => 'User added successfully']);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'nik' => 'required|numeric|digits:16|unique:users,nik,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'alamat' => 'required|string',
            'status' => 'required|in:active,inactive',
        ], [
            'nik.digits' => 'The NIK must be exactly 16 digits.',
            'nik.numeric' => 'The NIK must contain only numbers.',
            'nik.unique' => 'The NIK has already been taken.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $user = User::findOrFail($id);
        $user->update($request->only('nama', 'nik', 'email', 'alamat', 'status'));

        return response()->json(['success' => true, 'message' => 'User updated successfully']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['success' => true, 'message' => 'User deleted successfully']);
    }
}