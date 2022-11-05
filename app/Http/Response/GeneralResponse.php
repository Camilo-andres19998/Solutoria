<?php
namespace App\Http\Response;
class GeneralResponse
{
    public function default_json($success=null, $message=null, $data=[], $code=null)
    {

        return [
            'success'=> $success,
            'message'=> $message,
            'data'=>  $data,
            'code' => $code
        ];
    }
}
