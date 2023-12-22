<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserRoleController extends Controller
{
    public function index()
    {
        try{
            $roles = Role::where('status', 'ACTIVE')->get();

            $data = array(
                'message' => "success",
                'roles' => $roles,
                'status' => 200
            );
                
            return response()->json($data);
            
        }catch(\Exception $e){
            return response()->json(['message' => 'Failed to fetch users role', 'status' => 500]);
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
            'name' => ['required', 'string', 'max:255']
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {

            // Create user
            $role = Role::create([
                'name' => $request->name,
                'status' => 'ACTIVE'
            ]);
           
            return response()->json(['message' => 'User role created successfully', 'role' => $role], 201);
        } catch (\Exception $e) {
            // Something went wrong
            return response()->json(['error' => 'Failed to create user. ' . $e->getMessage()], 500);
        }

    }

    //SHOW FUNCTION
    public function show(Role $role)
    {
        try{
            $data = array(
                'message' => 'success',
                'role' => $role,
                'status' => 200
            );
            return response()->json($data, 200);

        } catch (\Exception $e) {
            // Something went wrong
            return response()->json(['error' => 'Failed to fetch users role. ' . $e->getMessage()], 500);
        }
    }

    //EDIT FUNCTION
    public function edit(Role $role)
    {

        try{
            $data = array(
                'message' => 'success',
                'role' => $role,
                'status' => 200
            );
            return response()->json($data, 200);
            
        } catch (\Exception $e) {
            // Something went wrong
            return response()->json(['error' => 'Failed to fetch users role. ' . $e->getMessage()], 411);
        }
    }

    //UPDATE FUNCTION
    public function update(Request $request, Role $role)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255']
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {

            $role->update([
                'name' => $request->name
            ]);
           

            return response()->json(['message' => 'User role update successfully', 'role' => $role], 201);
        } catch (\Exception $e) {
            // Something went wrong
            return response()->json(['error' => 'Failed to update user role. ' . $e->getMessage()], 500);
        }

    }


    public function activateUserRoleStatus(Role $role)
    {
        try{
            $role->update([
                'status' => 'ACTIVE'
            ]);
            return response()->json(['message' => 'User role\'s status changed successfully' , 'role' => $role, 200]);
        }
        catch (\Exception $e){
            return response()->json(['message' => 'Error occured while updating ' . $e->getMessage(), 'status' => 500]);
        }
    }

    public function deactivateUserRole(Role $role)
    {
        try{
            $role->update([
                'status' => 'INACTIVE'
            ]);
            return response()->json(['message' => 'User role\'s status changed successfully', 'role' => $role, 200]);
        }
        catch (\Exception $e){
            return response()->json(['message' => 'Error occured while updating' . $e->getMessage(), 'status' => 500]);
        }
    }
}
