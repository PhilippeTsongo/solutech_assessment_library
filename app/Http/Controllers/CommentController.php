<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
   
    public function index()
    {
        $comment = Comment::orderBy('created_at', 'DESC')->get();

        return response()->json(['commnet' => $comment, 'status' => 201], 201);
    }

    public function create()
    {
        //
    }

   
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post' => ['required', 'exists:posts,id'],
            'content' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try{

            $user = Auth::user()->id;

            $comment = Comment::create([
                'user_id' => $user,
                'post_id' => $request->post,
                'content' => $request->content,
            ]);

            return response()->json(['message' => 'Comment created successfully', 'comment' => $comment], 201);
        }catch(\Exception $e){
            return response()->json(['message' => 'Failure in creating a comment', 'status' => 422], 422);
        }
    }

    
    public function show(Comment $comment)
    {
        try{

            return response()->json(['comment' => $comment], 200);
        }catch(\Exception $e){
            return response()->json(['message' => 'Failure in fetching the comment', 'status' => 422], 422);
        }

    }

    public function edit($id)
    {
        //
    }

   
    public function update(Request $request, Comment $comment)
    {
        $validator = Validator::make($request->all(), [
            'content' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try{

            $comment->update([
                'content' => $request->content,
            ]);

            return response()->json(['message' => 'Comment updated successfully', 'comment' => $comment], 201);
        }catch(\Exception $e){
            return response()->json(['message' => 'Failure in updating the comment', 'status' => 422], 422);
        }
    }

    
    public function destroy(Comment $comment)
    {
        try{

            $comment->delete();

            return response()->json(['message' => 'Comment deleted successfully'], 200);
        }catch(\Exception $e){
            return response()->json(['message' => 'Failure in deleted the comment', 'status' => 422], 422);
        }
    }
}
