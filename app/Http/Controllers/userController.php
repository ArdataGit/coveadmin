<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class userController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.user', compact('users')); // Adjust to 'master-user' if needed
    }

    /**
     * Login API (return JSON + token)
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah',
            ], 401);
        }

        $user  = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'user'    => $user,
            'token'   => $token,
        ]);
    }

    /**
     * Logout API (hapus token)
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ]);
    }

    /**
     * Register API (user daftar sendiri)
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'nik' => 'required|numeric|digits:16|unique:users,nik',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'alamat' => 'required|string',
            // 'gambarktp' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            // 'fotoselfie' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $data = $request->only('nama', 'nik', 'email', 'alamat');
        $data['status'] = 'active'; // default status saat daftar
        $data['password'] = Hash::make($request->password);

        try {
            // // upload files
            // $ktpFilename = time() . '_' . $request->file('gambarktp')->getClientOriginalName();
            // $selfieFilename = time() . '_' . $request->file('fotoselfie')->getClientOriginalName();

            $user = User::create(array_merge($data));

            // // simpan file KTP
            // $ktpPath = public_path("img/user/{$user->id}/gambarktp");
            // if (!file_exists($ktpPath)) mkdir($ktpPath, 0755, true);
            // $request->file('gambarktp')->move($ktpPath, $ktpFilename);

            // // simpan file selfie
            // $selfiePath = public_path("img/user/{$user->id}/fotoselfie");
            // if (!file_exists($selfiePath)) mkdir($selfiePath, 0755, true);
            // $request->file('fotoselfie')->move($selfiePath, $selfieFilename);

            // // otomatis login setelah register
            // $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Register berhasil',
                'user'    => $user,
                // 'token'   => $token
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to register: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update akun (user update profilnya sendiri)
     */
    public function updateAccount(Request $request)
    {
        $user = $request->user(); // dari token

        $validator = Validator::make($request->all(), [
            'nama' => 'sometimes|string|max:255',
            'nik' => 'sometimes|numeric|digits:16|unique:users,nik,' . $user->id,
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'alamat' => 'sometimes|string',
            'password' => 'nullable|string|min:8',
            'gambarktp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'fotoselfie' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        // update data basic
        $user->fill($request->only('nama', 'nik', 'email', 'alamat'));

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // update KTP
        if ($request->hasFile('gambarktp')) {
            if ($user->gambarktp) {
                $oldFile = public_path("img/user/{$user->id}/gambarktp/{$user->gambarktp}");
                if (file_exists($oldFile)) unlink($oldFile);
            }
            $filename = time() . '_' . $request->file('gambarktp')->getClientOriginalName();
            $path = public_path("img/user/{$user->id}/gambarktp");
            if (!file_exists($path)) mkdir($path, 0755, true);
            $request->file('gambarktp')->move($path, $filename);
            $user->gambarktp = $filename;
        }

        // update selfie
        if ($request->hasFile('fotoselfie')) {
            if ($user->fotoselfie) {
                $oldFile = public_path("img/user/{$user->id}/fotoselfie/{$user->fotoselfie}");
                if (file_exists($oldFile)) unlink($oldFile);
            }
            $filename = time() . '_' . $request->file('fotoselfie')->getClientOriginalName();
            $path = public_path("img/user/{$user->id}/fotoselfie");
            if (!file_exists($path)) mkdir($path, 0755, true);
            $request->file('fotoselfie')->move($path, $filename);
            $user->fotoselfie = $filename;
        }

        $user->save();

        return response()->json(['success' => true, 'message' => 'Akun berhasil diperbarui', 'user' => $user]);
    }

     /**
     * Get profile (user login)
     */
    public function profile(Request $request)
    {
        $user = $request->user(); // otomatis dari sanctum token

        return response()->json([
            'success' => true,
            'user'    => $user
        ]);
    }

    public function data(Request $request)
    {
        $search = $request->query('search');

        $query = User::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                ->orWhere('nik', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('alamat', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%");
            });
        }

        $users = $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'nama' => $item->nama,
                'nik' => $item->nik,
                'email' => $item->email,
                'alamat' => $item->alamat,
                'status' => $item->status,
                'gambarktp' => $item->gambarktp,
                'fotoselfie' => $item->fotoselfie,
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
            'status' => 'required|in:active,inactive',
            'gambarktp' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'fotoselfie' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'alamat' => 'required|string',
        ], [
            'nik.digits' => 'The NIK must be exactly 16 digits.',
            'nik.numeric' => 'The NIK must contain only numbers.',
            'nik.unique' => 'The NIK has already been taken.',
            'gambarktp.required' => 'The KTP image is required.',
            'fotoselfie.required' => 'The selfie image is required.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $data = $request->only('nama', 'nik', 'email', 'alamat', 'status');
        $data['password'] = Hash::make($request->password);

        try {
            $ktpFilename = $request->hasFile('gambarktp') && $request->file('gambarktp')->isValid()
                ? time() . '_' . $request->file('gambarktp')->getClientOriginalName()
                : throw new \Exception('KTP image is required or invalid.');
            $selfieFilename = $request->hasFile('fotoselfie') && $request->file('fotoselfie')->isValid()
                ? time() . '_' . $request->file('fotoselfie')->getClientOriginalName()
                : throw new \Exception('Selfie image is required or invalid.');

            $data['gambarktp'] = $ktpFilename;
            $data['fotoselfie'] = $selfieFilename;

            $user = User::create($data);

            if ($request->hasFile('gambarktp')) {
                $file = $request->file('gambarktp');
                $destinationPath = public_path("img/user/{$user->id}/gambarktp");
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                $file->move($destinationPath, $ktpFilename);
                if (!file_exists("{$destinationPath}/{$ktpFilename}")) {
                    throw new \Exception('Failed to save KTP image to permanent location.');
                }
            }

            if ($request->hasFile('fotoselfie')) {
                $file = $request->file('fotoselfie');
                $destinationPath = public_path("img/user/{$user->id}/fotoselfie");
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                $file->move($destinationPath, $selfieFilename);
                if (!file_exists("{$destinationPath}/{$selfieFilename}")) {
                    throw new \Exception('Failed to save selfie image to permanent location.');
                }
            }

            return response()->json(['success' => true, 'message' => 'User added successfully']);
        } catch (\Exception $e) {
            if (isset($user) && $user->exists) {
                $ktpPath = public_path("img/user/{$user->id}/gambarktp/{$ktpFilename}");
                if (file_exists($ktpPath)) {
                    unlink($ktpPath);
                }
                $selfiePath = public_path("img/user/{$user->id}/fotoselfie/{$selfieFilename}");
                if (file_exists($selfiePath)) {
                    unlink($selfiePath);
                }
                $user->delete(); 
            }
            return response()->json(['success' => false, 'message' => 'Failed to add user: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'nik' => 'required|numeric|digits:16|unique:users,nik,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'alamat' => 'required|string',
            'status' => 'required|in:active,inactive',
            'gambarktp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'fotoselfie' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
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

        if ($request->hasFile('gambarktp')) {
            // Delete old file
            if ($user->gambarktp) {
                $oldFilePath = public_path("img/user/{$user->id}/gambarktp/{$user->gambarktp}");
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
            
            $file = $request->file('gambarktp');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path("img/user/{$user->id}/gambarktp");

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $filename);
            $user->gambarktp = $filename;
        }

        if ($request->hasFile('gambarktp') && $request->file('gambarktp')->isValid()) {
            if ($user->gambarktp) {
                $oldFilePath = public_path("img/user/{$user->id}/gambarktp/{$user->gambarktp}");
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
            
            $file = $request->file('gambarktp');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path("img/user/{$user->id}/gambarktp");

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $filename);
            $user->gambarktp = $filename; 
        }

        if ($request->hasFile('fotoselfie') && $request->file('fotoselfie')->isValid()) {
            if ($user->fotoselfie) {
                $oldFilePath = public_path("img/user/{$user->id}/fotoselfie/{$user->fotoselfie}");
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
            
            $file = $request->file('fotoselfie');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path("img/user/{$user->id}/fotoselfie");

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $filename);
            $user->fotoselfie = $filename; 
        }

        $user->save();

        return response()->json(['success' => true, 'message' => 'User updated successfully']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->gambarktp) {
            $filePath = public_path("img/user/{$user->id}/gambarktp/{$user->gambarktp}");
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        if ($user->fotoselfie) {
            $filePath = public_path("img/user/{$user->id}/fotoselfie/{$user->fotoselfie}");
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

       $userFolder = public_path("img/user/{$user->id}");
        if (is_dir($userFolder)) {
            if (is_dir($userFolder . '/gambarktp')) {
                rmdir($userFolder . '/gambarktp');
            }
            if (is_dir($userFolder . '/fotoselfie')) {
                rmdir($userFolder . '/fotoselfie');
            }
            rmdir($userFolder);
        }
        $user->delete();
        return response()->json(['success' => true, 'message' => 'User deleted successfully']);
    }
}