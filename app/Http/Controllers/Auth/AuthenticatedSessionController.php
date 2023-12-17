<?php

namespace App\Http\Controllers\Auth;

use Throwable;
use App\Models\User;
use App\Models\UserToken;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;


class AuthenticatedSessionController extends Controller
{
   
    public function store(Request $request)
    {
        
            $validation = $request->validate([
                'email'=>"required|exists:users|email",
                'password'=>"required|min:8|string",
            ]);

            try{
                $user = User::where('email', $request->email)
                             ->where('status', 'ACTIVE')->first();
                if($user){
                    if(Hash::check($request->password,$user->password)){
                        $token= $user->createToken('key')->plainTextToken;
                        // Authentication successful

                        $credentials = $request->only('email', 'password');
                        
                        // auth()->attempt($credentials);
                        Auth::login($user);

                        $save_token = UserToken::create([
                            'user_id' => $user->id,
                            'token' => $token,
                        ]);

                        $data=array(
                            'message'=>'User authenticated',
                            'user' => $user,
                            'accessToken'=>$token,
                            'status'=>200
                        );

                        return response()->json($data,200);
                    }
                    return response()->json(["error" => "Wrong password", "status" => 411], 411);
                }
                return response()->json(["error" => "User not exist or Inactive", "status" => 411], 411);

            }catch(\Exception $e){
                return response()->json(["error" => "Error occured while authenticating user", "status" => 500], 500);
            }

    }

    
    public function destroy(Request $request) 
    {

        try {
            
                //get the auth user
                $auth_user = Auth::user();
                
                $user = User::where('id', $auth_user->id)->first();

                $userToken = UserToken::where('user_id', $user->id)->first();

                //check if the auth user has tokens and delete them
                if($userToken != null){
                    $userToken->delete();

                    $user->tokens()->delete();

                    return response()->json(['message' => 'Logged out successfully'], 200);
        
                }else{
                    return response()->json(["message" => "Not logged in","status" => 200],200);
                }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'status'=> 500], 500);
        }
    }
}
