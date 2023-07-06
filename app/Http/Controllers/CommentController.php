<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public  function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required',
            'movie_id' => 'required',
        ]);

        if ($validator->fails()) {
            return ApiResponse::fail('Validation Error', $validator->errors()->all(), 422);
        }


        $comment = Comment::create([
            'comment' => $request->comment,
            'movie_id' => $request->movie_id,
            'user_id' => auth()->user()->id,
        ]);

        return ApiResponse::success('Comment Created at Movies successfully', $comment, 201);

    }
}
