<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;

use App\Http\Response\GeneralResponse;

use Symfony\Component\HttpFoundation\Response;



class UserController extends Controller
{

      /**
     * Create a new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    /**
     * @OA\Get(
     *      path="/users",
      *      summary="Get list User",
     *      description="Returns User data",

     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function index()
    {
        return (new GeneralResponse)->default_json(
            $success=false,
            $message = "Success",
            $data= response()->json(User::all())->original,
            $code= Response::HTTP_ACCEPTED
        );
    }




/**
     * @OA\Post(
     *      path="/users",
      *      summary="Create User",
     *      description="Returns User data",

     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *       ),
     *  *      @OA\Response(
     *          response=202,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function store(Request $request)
    {
        $request_input = $request->all();
        if(User::where("email", $request_input['email'])->count()){
            return (new GeneralResponse)->default_json(
                $success=false,
                $message= "Email is exist",
                $data=[],
                $code=Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        $user = User::create($request_input);
        return (new GeneralResponse)->default_json(
            $success=true,
            $message= "",
            $data= new UserResource($user),
            $code= Response::HTTP_ACCEPTED
        );
    }





 /**
     * @OA\Get(
     *     path="/users/{id}",
     *     tags={"User"},
     *     summary="Get all products for REST API",
     *     description="Multiple status values can be provided with comma separated string",
     *     operationId="index",
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         description="Per page count",
     *         required=false,
     *         explode=true,
     *         @OA\Schema(
     *             default="10",
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value"
     *     )
     * )
     */


    public function show($id)
    {
        return (new GeneralResponse)->default_json(
            $success=true,
            $message= "",
            $data= response()->json(User::find($id))->original,
            $code= Response::HTTP_ACCEPTED
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    
    /**
     * @OA\Put(
     *      path="/Users/{id}",
     *      summary="Update existing User",
     *      description="Returns updated User data",
     *      @OA\Parameter(
     *          name="id",
     *          description="User id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *      ),
     *      @OA\Response(
     *          response=202,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->update($request->all());

        return (new GeneralResponse)->default_json(
            $success=true,
            $message= "",
            $data= response()->json($user)->original,
            $code= Response::HTTP_ACCEPTED
        );
    }


    /**
     * @OA\Delete(
     *      path="/Users/{id}",
     *      summary="Delete existing User",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="User id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent({})
     *       ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent({})
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return (new GeneralResponse)->default_json(
            $success=true,
            $message= "",
            $data=[],
            $code= Response::HTTP_NO_CONTENT
        );
    }
}
