<?php

namespace App\Helpers;

class Helper
{

    public static function apiResponse($status = 'success', $code = 200, $message = null, $result = null)
    {
        $statusText = ($status == 'success' || $status == 1) ? 'success' : 'error';

        $response = [
            'status' => $statusText,
            'code' => $code,
            'message' => $message ?? ($statusText == 'success' ? 'Request successful.' : 'Request failed.'),
            'result' => $result,
        ];
        return $response;
    }

    public function testt(){
        echo ';test';
    }
}
