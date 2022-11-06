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

    public function index()
    {
        return (new GeneralResponse)->default_json(
            $success=false,
            $message = "Success",
            $data= response()->json(User::all())->original,
            $code= Response::HTTP_ACCEPTED
        );
    }





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
