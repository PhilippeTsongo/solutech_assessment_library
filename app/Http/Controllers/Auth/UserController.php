<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        try{
            $users = User::all();
            $data = array(
                'message' => "success",
                'users' => $users,
                'status' => 200
            );
                
            return response()->json($data, 200);
            
            }catch(\Exception $e){
                    return response()->json(['message' => 'Failed to fetch users', 'status' => 500]);
        }
    }

    //CREATE FUNCTION
    public function create()
    {

       
    }

    //STORE FUNCTION
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'unique:users'],
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'profile' => ['nullable', 'image', 'mimes:jpeg,png,gif,bmp,tiff,webp,svg,ico,jpg,jfif,pjpeg,pjp', 'max:5048'], // Allow various image formats with a max size of 5MB

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $last_id = User::max('id');
        $id = random_int(100000, 900000) . ($last_id + 1); // Generates a cryptographically secure random number
        try {

            //User Profile
            if ($request->hasFile('profile')) {
                $file = $request->file('profile');
                // Rename the file using a unique name and the original extension
                $fileName = $id . '.' . $file->getClientOriginalExtension();
                $destination = public_path().'/IMAGES/profile';
                // Store the file in the 'images/profile' directory with the new name
                $path = $file->move($destination, $fileName);

            } else {
                $path = ''; // If no file is uploaded, set path to null or handle it as needed
            }

            // Create user
            $user = User::create([
                'id' => $id,
                'email' => $request->email,
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'status' => 'ACTIVE',
                'role_id' => $request->role
            ]);
           
            return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
        } catch (\Exception $e) {
            // Something went wrong
            return response()->json(['error' => 'Failed to create user. ' . $e->getMessage()], 500);
        }

    }

    //SHOW FUNCTION
    public function show(User $user)
    {
        try{
            $data = array(
                'message' => 'success',
                'user' => $user,
                'status' => 200
            );
            return response()->json($data, 200);

        } catch (\Exception $e) {
            // Something went wrong
            return response()->json(['error' => 'Failed to fetch user. ' . $e->getMessage()], 500);
        }
    }

    //EDIT FUNCTION
    public function edit(User $user)
    {

        try{
            $data = array(
                'message' => 'success',
                'user' => $user,
                'status' => 200
            );
            return response()->json($data, 200);
            
        } catch (\Exception $e) {
            // Something went wrong
            return response()->json(['error' => 'Failed to fetch user. ' . $e->getMessage()], 411);
        }
    }

    //UPDATE FUNCTION
    public function update(Request $request, User $user)
    {
        
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'name' => ['required', 'string', 'max:255'],
            'profile' => ['nullable', 'image', 'mimes:jpeg,png,gif,bmp,tiff,webp,svg,ico,jpg,jfif,pjpeg,pjp', 'max:5048'], // Allow various image formats with a max size of 5MB

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $id = random_int(100000, 900000) . (1); // Generates a cryptographically secure random number
        try {

            //User Profile
            if ($request->hasFile('profile')) {
                $file = $request->file('profile');
                // Rename the file using a unique name and the original extension
                $fileName = $id . '.' . $file->getClientOriginalExtension();
                $destination = public_path().'/IMAGES/profile';
                // Store the file in the 'images/profile' directory with the new name
                $path = $file->move($destination, $fileName);

            } else {
                $path = ''; // If no file is uploaded, set path to null or handle it as needed
            }

            $user->update([
                'email' => $request->email,
                'name' => $request->name,
                'profile' => $path,
                'role_id' => $request->role
            ]);
           

            return response()->json(['message' => 'User update successfully', 'user' => $user], 201);
        } catch (\Exception $e) {
            // Something went wrong
            return response()->json(['error' => 'Failed to update user. ' . $e->getMessage()], 500);
        }

    }

    public function destroy(User $user)
    {
        try{
            $user->delete();
            return response()->json(['message' => 'User Deleted successfully' , $user, 200]);
        }
        catch (\Exception $e){
            return response()->json(['message' => 'Pas d\'', 'status' => 411]);
        }
    }

    public function activateUserStatus(User $user)
    {
        try{
            $user->update([
                'status' => 'ACTIVE'
            ]);
            return response()->json(['message' => 'User\'s status changed successfully' , $user, 200]);
        }
        catch (\Exception $e){
            return response()->json(['message' => 'Error occured while updating ' . $e->getMessage(), 'status' => 500]);
        }
    }

    public function deactivateUserStatus(User $user)
    {
        try{
            $user->update([
                'status' => 'INACTIVE'
            ]);
            return response()->json(['message' => 'User\'s status changed successfully' , $user, 200]);
        }
        catch (\Exception $e){
            return response()->json(['message' => 'Error occured while updating' . $e->getMessage(), 'status' => 500]);
        }
    }
}
