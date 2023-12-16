<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        try{
            $categories = Category::all();
            $data = array(
                'message' => "success",
                'categoriess' => $categories,
                'status' => 200
            );
                
            return response()->json($data, 200);
            
            }catch(\Exception $e){
                    return response()->json(['message' => 'Failed to fetch Categories', 'status' => 500]);
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
            'name' => ['required', 'unique:categories'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $last_id = Category::max('id');
        $id = random_int(100000, 900000) . ($last_id + 1); // Generates a cryptographically secure random number
        try {

            // Create Category
            $category = Category::create([
                'id' => $id,
                'name' => $request->name,
            ]);
           
            return response()->json(['message' => 'Category created successfully', 'category' => $category], 201);
        } catch (\Exception $e) {
            // Something went wrong
            return response()->json(['error' => 'Failed to create Category. ' . $e->getMessage()], 500);
        }

    }

    //SHOW FUNCTION
    public function show(Category $category)
    {
        try{
            $data = array(
                'message' => 'success',
                'category' => $category,
                'status' => 200
            );
            return response()->json($data, 200);

        } catch (\Exception $e) {
            // Something went wrong
            return response()->json(['error' => 'Failed to fetch category. ' . $e->getMessage()], 500);
        }
    }

    //EDIT FUNCTION
    public function edit(Category $category)
    {

        try{
            $data = array(
                'message' => 'success',
                'category' => $category,
                'status' => 200
            );
            return response()->json($data, 200);
            
        } catch (\Exception $e) {
            // Something went wrong
            return response()->json(['error' => 'Failed to fetch category. ' . $e->getMessage()], 411);
        }
    }

    //UPDATE FUNCTION
    public function update(Request $request, Category $category)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {

            $category->update([
                'name' => $request->name,
            ]);

            return response()->json(['message' => 'category update successfully', 'category' => $category], 201);
        } catch (\Exception $e) {
            // Something went wrong
            return response()->json(['error' => 'Failed to update category. ' . $e->getMessage()], 500);
        }

    }

    public function destroy(category $category)
    {
        try{
            $category->delete();
            return response()->json(['message' => 'Category Deleted successfully', 200], 200);
        }
        catch (\Exception $e){
            return response()->json(['error' => 'Failed to delete a category ' . $e->getMessage(), 'status' => 500], 500);
        }
    }

}
