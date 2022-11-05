<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Controllers\UserController;

use App\Http\Response\GeneralResponse;

class AuthController extends Controller






{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

   /**
    * @OA\Post(
    *     path="/login",
    *     @OA\Parameter(
     *          name="email",
     *          description="Email Field",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="password",
     *          description="Password",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
    *     @OA\Response(response="200", description="Display a credential User."),
    *     @OA\Response(response="201", description="Successful operation"),
    *     @OA\Response(response="400", description="Bad Request"),
    *     @OA\Response(response="401", description="Unauthenticated"),
    *     @OA\Response(response="403", description="Forbidden")
    * )
    */
    public function login()
    {
        $credentials = request(['email', 'password']);


        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }


        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
    * @OA\Post(
    *     path="/logout",
    *     @OA\Response(response="200", description="Display a credential User."),
    *     @OA\Response(response="201", description="Successful operation"),
    *     @OA\Response(response="400", description="Bad Request"),
    *     @OA\Response(response="401", description="Unauthenticated"),
    *     @OA\Response(response="403", description="Forbidden")
    * )
    */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
    * @OA\Post(
    *     path="/refresh",
    *     @OA\Response(response="200", description="Display a credential User."),
    *     @OA\Response(response="201", description="Successful operation"),
    *     @OA\Response(response="400", description="Bad Request"),
    *     @OA\Response(response="401", description="Unauthenticated"),
    *     @OA\Response(response="403", description="Forbidden")
    * )
    */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }


      /**
    * @OA\Post(
    *     path="/register",
    *     @OA\Parameter(
     *          name="email",
     *          description="Email Field",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *      @OA\Parameter(
     *          name="username",
     *          description="Username Field",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *      @OA\Parameter(
     *          name="firstname",
     *          description="First Name",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *      @OA\Parameter(
     *          name="lastname",
     *          description="Last Name",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="password",
     *          description="Password",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *  @OA\Parameter(
     *          name="password2",
     *          description="Password",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *    @OA\Response(response="200", description="Create User credentials",
     *     @OA\JsonContent(ref="#/components/schemas/User")),
    *     @OA\Response(response="201", description="Successful operation",@OA\JsonContent(ref="#/components/schemas/User")),
    *     @OA\Response(response="400", description="Bad Request"),
    *     @OA\Response(response="401", description="Unauthenticated"),
    *     @OA\Response(response="403", description="Forbidden")

    * )
    */
    public function register(Request $request)
    {
        $request_input = $request->input();
        if(array_key_exists('password',  $request_input) && array_key_exists('password2',  $request_input)){
            if ($request_input['password'] != $request_input['password2']){
                return GeneralResponse::default_json([
                    "success" => false,
                    "message" => "password and confirm password must be same",
                    "code" => 500
                ]);
            }
        }

        $userController = new UserController;
        return $userController->store($request);
    }




    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access' => $token,
            'refresh' => auth()->refresh(),
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
