<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function store(CreateUserRequest $request)
    {
        $user = new User();
        $user->name = $request->validated('name');
        $user->email = $request->validated('email');
        $user->password = Hash::make($request->validated('password'));

        $created =  $user->save();

        if ($created) {
            return response()->json([
                'message' => 'User ' . $user->email . ' berhasil dibuat',
            ]);
        } else {
            return response()->json([
                'message' => 'User gagal dibuat'
            ], 400);
        }
    }

    public function getAll()
    {
        $users = User::all();

        return response()->json([
            'message' => 'List user',
            'data' => $users,
        ]);
    }

    public function getById($id)
    {
        $user = User::where('id', $id)
            ->first();

        if (!$user) {
            return response()->json([
                'message' => 'User is not found'
            ], 404);
        }

        return response()->json([
            'message' => 'User is found',
            'data' => $user
        ]);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::where('id', $id)
            ->first();

        if (!$user) {
            return response()->json([
                'message' => 'User is not found'
            ], 404);
        }

        foreach ($request->only('name', 'email') as $key => $value) {
            $user->$key = $value;
        }

        if ($request->validated('password')) {
            $user->password = Hash::make($request->validated('password'));
        }

        try {
            $updated = $user->save();

            if ($updated) {
                return response()->json([
                    'message' => 'User updated successfully.'
                ]);
            } else {
                throw new Exception("Fail to save user to database");
            };
        } catch (\Throwable $th) {
            Log::error("ERROR: [" . $request->route()->getActionName() . "] " . $th->getMessage());

            return response()->json([
                'message' => 'User update failed.'
            ], 400);
        }
    }

    public function delete($id)
    {
        $user = User::where('id', $id)
            ->first();

        if (!$user) {
            return response()->json([
                'message' => 'User is not found'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted'
        ]);
    }
}
