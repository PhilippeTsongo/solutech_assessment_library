<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    //only admin can acceess this 
    public function __construct()
    {
        $this->middleware(['IsAdmin']);
    }

    public function index()
    {
        try{
            $categories = Category::orderBy('created_at', 'DESC')->get();
           
            foreach ($categories as $category_book) {
                $number_of_books = $category_book->books;
                $number_of_sub_categories = $category_book->subCategories;
            }

            $total = $categories->count();

            $data = array(
                'message' => "success",
                'categories' => $categories,
                'total' => $total,
                'status' => 200
            );
                
            return response()->json($data, 200);
            
            }catch(\Exception $e){
                    return response()->json(['message' => 'Failed to fetch Categories' . $e->getMessage(), 'status' => 500]);
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

        $id = random_int(100000, 900000)  + 1; // Generates a cryptographically secure random number
        try {

            // Create Category
            $category = Category::create([
                'id' => $id,
                'name' => $request->name,
            ]);
           
            return response()->json(['message' => 'Category created successfully', 'category' => $category], 200);
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

            return response()->json(['message' => 'category update successfully', 'category' => $category], 200);
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
