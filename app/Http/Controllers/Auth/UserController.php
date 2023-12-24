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
            $users = User::orderBy('created_at', 'DESC')->get();
            $active_users = User::where('status', 'ACTIVE')->get();
            $inactive_users = User::where('status', 'INACTIVE')->get();

            foreach ($users as $user) {
                $user_role = $user->role;
            }
            $active = $active_users->count();
            $inactive = $inactive_users->count();
            $total = $users->count();

            $data = array(
                'message' => "success",
                'users' => $users,
                'active' => $active,
                'inactive' => $inactive,
                'total' => $total,
                'status' => 200
            );
                
            return response()->json($data);
            
            }catch(\Exception $e){
                    return response()->json(['message' => 'Failed to fetch users ' .$e->getMessage(), 'status' => 500]);
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
            'profile' => ['nullable', 'mimes:jpeg,png,gif,bmp,tiff,webp,svg,ico,image/jpeg,image/png,image/gif,image/bmp,image/tiff,image/webp,image/svg+xml,image/x-icon', 'max:5048']
 // Allow various image formats with a max size of 5MB

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $id = random_int(100000, 900000)  + 1; // Generates a cryptographically secure random number
        try {

           if ($request->hasFile('profile')) {
                $file = $request->file('profile');

                $fileName = $id;
                $path = $file->storeAs('IMAGES/PROFILE', $fileName, 'public');

                // $path now contains the path in the storage/app/public directory
            } else {
                $path = null; // If no file is uploaded, set path to null or handle it as needed
            }

            // Create user
            $user = User::create([
                'id' => $id,
                'email' => $request->email,
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'status' => 'ACTIVE',
                'profile' => $path,
                'role_id' => $request->role
            ]);
           
            // return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
            return response()->json(['message' => 'User created successfully', $path, 201]);

        } catch (\Exception $e) {
            // Something went wrong
            return response()->json(['errors' => 'Failed to create user. ' . $e->getMessage()], 500);
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
            // 'profile' => ['nullable', 'mimes:jpeg,png,gif,bmp,tiff,webp,svg,ico,image/jpeg,image/png,image/gif,image/bmp,image/tiff,image/webp,image/svg+xml,image/x-icon', 'max:5048'] // Allow various image formats with a max size of 5MB

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $id = random_int(100000, 900000) . (1); // Generates a cryptographically secure random number
        try {

            //User Profile
            if ($request->hasFile('profile')) {

                // Delete the existing image file
                if ($user->profile) {
                    Storage::disk('public')->delete($user->profile);
                }

                $file = $request->file('profile');
                $fileName = $id;
                $path = $file->storeAs('IMAGES/PROFILE', $fileName, 'public');

                // $path now contains the path in the storage/app/public directory
            } else {
                $path = null; // If no file is uploaded, set path to null or handle it as needed
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

    public function destroy($user)
    {
        try{

            $userData = User::findOrFail($user);

            if ($user->profile) {
                Storage::disk('public')->delete($user->profile);
            }

            $userData->delete();
            return response()->json(['message' => 'User Deleted successfully' , $userData, 200]);
        }
        catch (\Exception $e){
            return response()->json(['message' => 'error deleting the user', 'status' => 411]);
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
