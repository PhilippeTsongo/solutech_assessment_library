<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    //only admin can access to all these functions
    public function __construct()
    {
        $this->middleware(['IsAdmin']);
    }

    public function index()
    {
        $roles = Role::with(['staffs'])->get();
        foreach($roles as $role):
            if(!$role->staffs){
                return response()->json(['error' => 'related data not found']);             
            }
        endforeach; 

        $data = array(
            'message' => 'Liste de roles de staffs',
            'role' => $roles, 
            'status' => 200,
        );

        return response()->json($data);
        // return view('course.index', compact('role'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255', 'unique:roles'],
        ]);

        $role = Role::firstOrCreate([
            'name' => $request->course_id,
            'description' => $request->description
        ]);
            
        if($role){
            $data = array(
                'message' => 'Role enregistré avec succès',
                'role' => $role, 
                'status' => 200,
            );

            return response()->json($data);
            // session()->flash('message', 'Cours crée avec succès');
            // return redirect(route('course.index'));
        }
    }
  
    public function show(Role $role)
    {
        if(!$role)
        {
            return response()->json(['message' => 'Ce Role n\'existe pas'], 404);
        }

        $staffs = $role->staffs;

        $data = array(
            'message' => 'Information du role' . $role->name,
            'role' => $role,
            'staffs' => $staffs,
            'status' => 200,
        );

        return response()->json($data);

        // return view('course.show', compact('course'));
    }

    
    public function update(Request $request, Role $role)
    {
        if($role)
        {
            $request->validate([
                'name' => ['required', 'string', 'min:3', 'max:255'],
            ]);

            $edit_role = $role->update([
                'name' => $request->name,  
                'description' => $request->description,   
            ]);
    
            if($edit_role):

                $data = array(
                    'message' => 'Role modifié avec succès',
                    'role' => $role, 
                    'status' => 200,
                );
    
                return response()->json($data);
            endif;
            // session()->flash('message', 'Role modifié avec succès');
            // return redirect(route('course.index'));
        }
    }

    //EDIT FUNCCTION
    public function destroy(Role $role)
    {
        if($role)
        {
            if($role->staffs)
            {
                $role->staffs->delete();
            }

            $role->delete();

            $data = array(
                'message' => 'Role supprimé avec succès',
                'Role' => $role, 
                'status' => 200,
            );

            return response()->json($data);
            // session()->flash('message', "Cours supprimée avec succès");
            // return redirect()->route('course.index');
        }
    }
}
