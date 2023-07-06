<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success($message, $data, $status)
    {
        return response()->json([
            'meta' => [
                'success' => true,
                'message' => $message,
            ],
            'body' => $data,
        ], $status);
    }

    public static function fail($message, $data, $status)
    {
        return response()->json([
            'meta' => [
                'success' => false,
                'message' => $message,
            ],
            'body' => $data,
        ], $status);
    }

}
