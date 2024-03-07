<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function sendError($message,$errors,$code = Response::HTTP_BAD_REQUEST)
    {
        $data = [
            'success'=>false,
            'message'=>$message,
            'errors'=>$errors,
        ];
        return response()->json($data, $code);
    }

    public function sendSuccess($data,$message='',$code = Response::HTTP_OK)
    {
        $data = [
            'success'=>true,
            'data'=>$data,
            'message'=>$message,
        ];

        return response()->json($data, $code);
    }
}
