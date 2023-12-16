<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BookController extends Controller
{
    
    public function index()
    {
        try{
            $books = Book::all();
            $data = array(
                'message' => "success",
                'books' => $books,
                'status' => 200
            );
                
            return response()->json($data, 200);
            
            }catch(\Exception $e){
                    return response()->json(['message' => 'Failed to fetch books', 'status' => 500]);
        }
    }

    
    public function create()
    {
        
    }

    
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'publisher' => ['required', 'string'],
            'isbn' => ['required', 'unique:books'],
            'category' => ['required'],
            'subcategory' => ['required'],
            'page' => ['required', 'integer'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'], // Allow various image formats with a max size of 2MB
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {   

            $random_id = random_int(1000000, 9000000) + 1; // Generates a cryptographically secure random number
            //book image
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                // Rename the file using a unique name and the original extension
                $fileName = $random_id . '.' . $file->getClientOriginalExtension();
                $destination = public_path().'/IMAGES/BOOKS';
                // Store the file in the 'images/profile' directory with the new name
                $path = $file->move($destination, $fileName);

            } else {
                $path = ''; // If no file is uploaded, set path to null or handle it as needed
            }


            // Create user
            $book = new Book($request->all());

            $book->id = $random_id;
            $book->added_by = Auth::id();
            $book->image = $path;
            $book->category_id = $request->category;
            $book->subcategory_id = $request->subcategory;
            $book->image = $path;


            $book->save();
           
            return response()->json(['message' => 'Book created successfully', 'book' => $book], 201);
            
        } catch (\Exception $e) {
            // Something went wrong
            return response()->json(['error' => 'Failed to create a book ' . $e->getMessage()], 500);
        }
    }

   
    public function show(Book $book)
    {
        try{
            $data = array(
                'message' => 'success',
                'user' => $book,
                'status' => 200
            );
            return response()->json($data, 200);

        } catch (\Exception $e) {
            // Something went wrong
            return response()->json(['error' => 'Failed to fetch a unique book. ' . $e->getMessage()], 500);
        }
    }

   
    public function edit($id)
    {
        //
    }

    
    public function update(Request $request, Book $book)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'publisher' => ['required', 'string'],
            'category' => ['required'],
            'subcategory' => ['required'],
            'page' => ['required', 'integer'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'], // Allow various image formats with a max size of 2MB

        ]);

        //return the validation error if there is any
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {   

            $random_id = random_int(100000, 900000) + 1; // Generates a cryptographically secure random number
    
            $book->fill($request->all());
        
            if ($request->hasFile('image')) {
                // Delete the existing image file
                if ($book->image) {
                    Storage::disk('public/IMAGES/BOOKS')->delete($book->image);
                }
        
                // Save the new image file
                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    // Rename the file using a unique name and the original extension
                    $fileName = $random_id . '.' . $file->getClientOriginalExtension();
                    $destination = public_path().'/IMAGES/BOOKS';
                    // Store the file in the 'images/profile' directory with the new name
                    $path = $file->move($destination, $fileName);
    
                } else {
                    $path = ''; // If no file is uploaded, set path to null or handle it as needed
                }
                
                $book->image = $path;
                $book->added_by = Auth::id();

            }
        
            $book->save();
        
            return response()->json(['message' => 'Book updated successfully', 'book' => $book], 201);

        } catch (\Exception $e) {
            // Something went wrong
            return response()->json(['error' => 'Failed to update the book. ' . $e->getMessage()], 500);
        }
    }

    
    public function destroy(Book $book)
    {
        try {
            // Delete the associated image file from the storage if it exists
            if ($book->image) {

                if (File::exists($book->image)) {
                    // Delete the file
                    File::delete($book->image);
                }
            }
            // Soft delete the book The book will not be deleted instead the property deleted-at with contain the value
            $book->delete();
    
            return response()->json(['success', 'Book deleted successfully!', 'status' => 200], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error', 'Book not found! ' . $e->getMessage(), 'status' => 411], 411);
        } catch (\Exception $e) {
            return response()->json(['error', 'An error occurred while deleting the book. ' . $e->getMessage(), 'status' => 500], 500);
        }
    }

    
    public function availableStatus(Book $book)
    {
        try{
            $book->update([
                'status' => 'AVAILABLE'
            ]);
            return response()->json(['message' => 'Book\'s status changed to available successfully',  'status' => 200], 200);
        }
        catch (\Exception $e){
            return response()->json(['message' => 'Error occured while updating ' . $e->getMessage(), 'status' => 500]);
        }
    }

    public function unavailableStatus(Book $book)
    {
        try{
            $book->update([
                'status' => 'UNAVAILABLE'
            ]);
            return response()->json(['message' => 'Book\'s status changed to unavailable successfully',  'status' => 200], 200);
        }
        catch (\Exception $e){
            return response()->json(['message' => 'Error occured while updating ' . $e->getMessage(), 'status' => 500]);
        }
    }
}
