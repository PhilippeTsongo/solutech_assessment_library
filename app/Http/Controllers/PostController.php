<?php

// app/Http/Controllers/PostController.php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{


    public function index()
    {
        // Retrieve all posts
        $posts = Post::where('status', 'ACTIVE')
                    ->orderBy('created_at', 'DESC')->get();

        // $number_of_comment = [];
        foreach($posts as $post)
        {
            $post->number_of_comment = count($post->comments);
        }

        $data = [
            'posts' => $posts, 
        ];


        return response()->json($data, 201);
    }

    public function show(Post $post)
    {
        try{
            //last like of the post
            $last_like = DB::table('likes')->where('post_id', $post->id)->latest()->first();
            if($last_like){
                $last_liker = User::where('id', $last_like->user_id)->first();
        
                $last_liked_by = $last_liker->firstname . ' ' .$last_liker->lastname;
                $post->last_liker = $last_liked_by;
            
            }
            //number of comment of the post
            $post->number_of_comment = count($post->comments);

            $data = [
                $post
            ];
            return response()->json(['post' => $data, 'status' => 201]);
            
        }catch(\Exception $e){
            return response()->json(['message' => 'Fail to fetch the post ' . $e->getMessage(), 'status' => 500], 500);
        }
    }

    public function store(Request $request)
    {
        // Check if the user is authenticated
        
        if (Auth::check()) {

            $validator = Validator::make($request->all(), [
                'content' => ['nullable', 'string'],
                'images.*' => ['required', 'image', 'mimes:jpeg,png,gif,bmp,tiff,webp,svg,ico,jpg,jfif,pjpeg,pjp', 'max:30048'],
                'tag' => ['nullable', 'string']
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            try{
                $user = Auth::user();
                $images = [];

                // Handle image uploads if present
                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $image) {
                        $imageName = $user->id . '_' . $image->getClientOriginalName();
                        $destination = public_path().'/IMAGES/posts';

                        $image->move($destination, $imageName);
                        $path = $destination .'/'.$imageName;
                        // Store only the filename in the $images array
                        $images[] = $path;
                    }
                }
            
                if($request->hasFile('images') == null){
                    return response()->json(['errors' => 'Selectionnez une ou plusieurs images', 'status' => 422], 422);
                }

                $imagesJson = json_encode($images);

                $code = random_int(100000000, 999999999) . $user->id; // Generates a cryptographically secure random number

                $post = Post::create([
                    'id' => $code,
                    'content' => $request->content,
                    'images' => $imagesJson,
                    'tag' => $request->tag,
                    'user_id' => $user->id,
                    'likes' => 0
                ]);

                return response()->json(['message' => 'Post created successfully', 'post' => $post], 201);

            }catch(\Exception $e){
                return response()->json(['message' => 'Fail to create a post' . $e->getMessage(), 'status' => 500], 500);
            }

        } else {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
    }

    public function edit()
    {
        
    }

    public function update(Request $request, $post)
    {
        if (Auth::check()) {
            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'content' => ['nullable', 'string'],
                'images.*' => ['required', 'image', 'mimes:jpeg,png,gif,bmp,tiff,webp,svg,ico,jpg,jfif,pjpeg,pjp', 'max:30048'],
                'tag' => ['nullable', 'string'],
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            try{
                $images = [];

                // Handle image uploads if present
                if ($request->hasFile('images')) {

                    // delete existed files and upload new files
                    if($post->images != null){
                        foreach($post->images as $existedImage){
                            // Check if the file exists
                            if (File::exists($existedImage)) {
                                // Delete the file
                                File::delete($existedImage);
                            } else {
                                return response()->json(['error' => 'File not found'], 404);
                            }
                        }
                    }
                    
                    //upload images after deleting the old one
                    foreach ($request->file('images') as $image) {
                        $imageName = $user->id . '_' . $image->getClientOriginalName();
                        $destination = public_path().'/IMAGES/posts';

                        $image->move($destination, $imageName);
                        $path = $destination .'/'.$imageName;
                        // Store only the filename in the $images array
                        $images[] = $path;
                    }
                }
                
                if($request->hasFile('images') == null){
                    return response()->json(['errors' => 'Selectionnez une ou plusieurs images', 'status' => 422], 422);
                }


                $imagesJson = json_encode($images);

                $post->update([
                    'content' => $request->content,
                    'images' => $imagesJson,
                    'tag' => $request->tag,
                    'user_id' => $user->id
                ]);

                return response()->json(['message' => 'Post created successfully', 'post' => $post], 201);

            }catch(\Exception $e){
                return response()->json(['message' => 'Fail to create a post' . $e->getMessage(), 'status' => 500], 500);
            }
        } else {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
    }

    public function destroy(Post $post)
    {
        try{
            // You may want to implement logic to delete associated images

            $post->update([
                'status' => 'DELETED'
            ]);

            return response()->json(['message' => 'Post deleted successfully'], 200);
        }catch(\Exception $e){
            return response()->json(['message' => 'Fail to delete a post' . $e->getMessage(), 'status' => 500], 500);
        }
    }
}
