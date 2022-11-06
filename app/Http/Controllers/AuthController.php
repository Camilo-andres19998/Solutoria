<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{


    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Auth"},
     *     summary="Registrate",
     *     operationId="Register",
     *
     *     @OA\Response(
     *         response=405,
     *         description="Invalid input"
     *     ),
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     description="Enter your name",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     description="Enter your Email",
     *                     type="email"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="Enter your password",
     *                     type="password"
     *                
     *                 )
     *             )
     *         )
     *     )
     * )
     */



    public function Register(Request $request){


        $fields = $request->validate([
            'name'=>'required|string',
            'email'=>'required|string|unique:users,email',
            'password'=>'required|string|confirmed',
        ]);
        return response()->json('camilo');
        $user = new User();
        $user->name = $fields['name'];
        $user->email = $fields['email'];
        $user->password = bcrypt($fields['password']);
        $user->save();
        $token = $user->createToken('myapptoken')->plainTextToken;
        $response = [
            'data'=>$user,
            'token'=>$token
        ];
        return response()->json($response);
    }
    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Auth"},
     *     summary="Authentificate",
     *     operationId="Login",
     *
     *     @OA\Response(
     *         response=405,
     *         description="Invalid input"
     *     ),
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     description="Enter your email",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="Enter password",
     *                     type="password"
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function Login(Request $request){
        $fields = $request->validate([
            'email'=>'required',
            'password'=>'required'
        ]);
        $user = User::where('email', $request->email)->first();
        if(!$user||!Hash::check($fields['password'], $user->password)){
            return response()->json('password or login is incorrect');
        }
        $token = $user->createToken('myapptoken')->plainTextToken;
        $response = [
            'data'=>$user,
            'token'=>$token
        ];
        return response()->json($response);
    }
}
