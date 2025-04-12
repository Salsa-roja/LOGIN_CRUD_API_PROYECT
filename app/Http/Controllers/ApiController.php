<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|max:255',
                'password' => 'required|string|min:6'
            ]);

            $email = trim(strtolower($request->input('email')));

            if (User::where('email', $email)->exists()) {
                $response['msg'] = 'El nombre de usuario ya está en uso, utiliza otro';
                $response['statusHttp'] = 400;
                return response()->json($response, 400);
            }

            $user = new User();
            $user->name = $request->input('name');
            $user->email = $email;
            $user->password = Hash::make($request->input('password'));
            $user->save();

            return $user; 
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|max:255',
            'password' => 'required|string'
        ]);

        $email = trim(strtolower($request->input('email')));
        $user = User::where('email', $email)->first();

        if (!$user) {
            return [
                'status'  => 400,
                'token'   => '',
                'message' => 'El usuario no existe'
            ];
        }

        if (Hash::check($request->input('password'), $user->password)) {
            $token = $user->createToken("token")->plainTextToken;

            return [
                'status'  => true,
                'token'   => $token,
                'info'    => $user,
                'message' => 'Autorizado'
            ];
        }

        return [
            'status'  => 400,
            'token'   => '',
            'message' => 'Usuario o contraseña incorrectos'
        ];
    }
}
