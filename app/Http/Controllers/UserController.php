<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __invoke(Request $req)
    {
        $query = Admin::where('usr_login', $req->login);
        $admin = $query->first();
        if($admin === null) {
            return response()->json(['error' => 'Usuário inválido'], 200);
        }
        if(Hash::check($req->password, $admin->usr_pass)) {
            $token = uniqid();
            $query->update(['usr_token' => $token]);
            return response()->json(['error' => false, 'token' => $token], 200);
        } else {
            return response()->json(['error' => 'Senha incorreta'], 200);
        }
    }

    public function checkToken(Request $req)
    {
        $query = Admin::where('usr_token', $req->token);
        $admin = $query->first();
        if($admin === null) {
            return response()->json(['error' => true], 200);
        }
        return response()->json(['error' => false], 200);
    }
}
