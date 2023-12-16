<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function likePost(Post $post){

        try{
            $user = Auth::user();

            // Check if the user has already liked the post
            if (!$user->likes()->where('post_id', $post->id)->exists()) {
                // Create a new like
                $like = new Like();
                $like->user_id = $user->id;
                $like->post_id = $post->id;
                $like->save();

                //update the number of like in post model
                $post->update([
                    'number_of_likes' => $post->number_of_likes + 1
                ]);
                
                return response()->json(['message' => 'You have liked this post successfully', 'status' => 200]);
            }

            return response()->json(['message' => 'You have already liked this post', 'status' => 400]);
        }catch(\Exception ){
            return response()->json(['erroe' => 'Failed to like a post', 'status' => 500]);
        }
    }

    public function unlikePost(Post $post)
    {
        try{
            $user = Auth::user();

            // Check if the user has liked the post
            $like = $user->likes()->where('post_id', $post->id)->first();

            if ($like) {
                // Unlike the post
                $like->delete();

                //update the number of like in post model
                if($post->likes){
                    $post->update([
                        'number_of_likes' => $post->number_of_likes - 1
                    ]);
                }

                return response()->json(['message' => 'Post unliked successfully', 'status' => 200]);
               
            }

            

            return response()->json(['message' => 'You\'ve not liked the post', 'status' => 400]);
        }catch(\Exception ){
            return response()->json(['erroe' => 'Failed to unlike a post', 'status' => 500]);
        }
    }
}
