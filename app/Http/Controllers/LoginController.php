<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use LdapRecord\Models\ActiveDirectory\User as UserLDAP;
use App\Ldap\User;
use LdapRecord\Models\Attributes\AccountControl;
use LdapRecord\Models\ActiveDirectory\Group;



class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'usuario' => 'required|max:255',
            'contrasena' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $credentials = [
            'samaccountname' => $request->usuario,
            'password' => $request->contrasena,
        ];

        if(!Auth::attempt($credentials)){

            return response([
                'message' => 'Autenciación inválida'
            ], 422);
        }


        $accesToken = Auth::user()->createToken('authTestToken')->accessToken;

        return response([
            "user" => Auth::user(),
            "access_token" => $accesToken
        ]);
    }


}
