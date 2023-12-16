<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubcategoryController extends Controller
{
    public function index()
    {
        try{
            $sub_categories = Subcategory::all();
            $data = array(
                'message' => "success",
                'sub_categories' => $sub_categories,
                'status' => 200
            );
                
            return response()->json($data, 200);
            
            }catch(\Exception $e){
                    return response()->json(['message' => 'Failed to fetch sub categories', 'status' => 500]);
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
            'name' => ['required', 'unique:subcategories'],
            'category' => ['required'],

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $last_id = Subcategory::max('id');
        $id = random_int(100000, 900000) . ($last_id + 1); // Generates a cryptographically secure random number
        try {

            // Create Subcategory
            $sub_category = Subcategory::create([
                'id' => $id,
                'name' => $request->name,
                'category_id' => $request->category,
            ]);
           
            return response()->json(['message' => 'Sub category created successfully', 'sub_category' => $sub_category], 201);
        } catch (\Exception $e) {
            // Something went wrong
            return response()->json(['error' => 'Failed to create sub category. ' . $e->getMessage()], 500);
        }

    }

    //SHOW FUNCTION
    public function show(Subcategory $subcategory)
    {
        try{

            $category = Category::where('id', $subcategory->subcategory_id)->first();
            $subcategory->category = $subcategory->name;
            $data = array(
                'message' => 'success',
                'subcategory' => $subcategory,
                'status' => 200
            );
            return response()->json($data, 200);

        } catch (\Exception $e) {
            // Something went wrong
            return response()->json(['error' => 'Failed to fetch sub category. ' . $e->getMessage()], 500);
        }
    }

    //EDIT FUNCTION
    public function edit(Subcategory $subcategory)
    {

        try{

            $category = Category::where('id', $subcategory->subcategory_id)->first();
            $subcategory->category = $subcategory->name;
            $data = array(
                'message' => 'success',
                'subcategory' => $subcategory,
                'status' => 200
            );
            return response()->json($data, 200);

        } catch (\Exception $e) {
            // Something went wrong
            return response()->json(['error' => 'Failed to fetch sub category. ' . $e->getMessage()], 500);
        }
    }

    //UPDATE FUNCTION
    public function update(Request $request, Subcategory $subcategory)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {

            $subcategory->update([
                'name' => $request->name,
                'category_id' => $request->category,

            ]);

            return response()->json(['message' => 'Sub category update successfully', 'sub_category' => $subcategory], 201);
        } catch (\Exception $e) {
            // Something went wrong
            return response()->json(['error' => 'Failed to update Subcategory. ' . $e->getMessage()], 500);
        }

    }

    public function destroy(Subcategory $subcategory)
    {
        try{
            $subcategory->delete();
            return response()->json(['message' => 'Sub category deleted successfully', 'status' => 200], 200);
        }
        catch (\Exception $e){
            return response()->json(['error' => 'Failed to delete a sub category'  . $e->getMessage(), 'status' => 500], 500);
        }
    }
}
