<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use DB;


class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6 ',
        ]);
        $error = [];
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $accessToken = $user->createToken('authToken')->plainTextToken;
                $user->accessToken = $accessToken;
                $user->role = 'admin';
                $user->ability = [['action'=>'manage','subject'=>'all']];
                $response = ['status' => true,  'is_login' => true, 'userData' => $user, 'accessToken' => $accessToken, 'expiration' => config('sanctum.expiration'), 'token_type' => 'Bearer', 'message' => 'Logged in successfully !']; //phpcs:ignore
                return response($response, 200);
            } else {
                $error = array_merge($error, ['password' => ['Password Mismatch']]);
                return response(['errors' => $error, 'code' => 422], 422);
            }
        } else {
            $error = array_merge($error, ['email' => ['User does not exist']]);
            return response(['errors' => $error, 'code' => 422], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
