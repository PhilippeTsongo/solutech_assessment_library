<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookLoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BookLoanController extends Controller
{
   
    public function index()
    {
        try{
            $loans = BookLoan::all();
            $data = array(
                'message' => "success",
                'users' => $loans,
                'status' => 200
            );
                
            return response()->json($data, 200);
            
            }catch(\Exception $e){
                    return response()->json(['message' => 'Failed to fetch loans', 'status' => 500]);
        }
    }

   
    public function create()
    {
        //
    }

   
    public function store(Request $request)
    {
        //
    }

    
    public function show(BookLoan $book_loan)
    {
        try{
            $data = array(
                'message' => 'success',
                'book_loan' => $book_loan,
                'status' => 200
            );
            return response()->json($data, 200);

        } catch (\Exception $e) {
            // Something went wrong
            return response()->json(['error' => 'Failed to fetch book loan. ' . $e->getMessage()], 500);
        }
    }

    
    public function edit($id)
    {
        //
    }

    
    public function update(Request $request, $id)
    {
        //
    }

   
    public function destroy($id)
    {
        //
    }


    public function borrowBook (Request $request, $bookId)
    {

        $validator = Validator::make($request->all(), [
            'user' => ['required', 'exists:users,id'],
            'book' => ['required', 'exists:books,id'],
            'loan_date' => ['required', 'date'],
            'return_date' => ['nullable', 'date', 'after_or_equal:loan_date'],
            // 'extended' => ['required', 'boolean'],
            'extension_date' => $request->get('extended') ? 'required_if:extended,1|nullable|date|after_or_equal:return_date' : '',
            'due_date' => ['required', 'date'],
            'penalty_amount' => ['required_if:penalty_status,CHARGED', 'nullable', 'numeric', 'min:0'],
            'penalty_status' => ['required', 'in:CHARGED,NOT_CHARGED'],
            'status' => ['required', 'in:REQUESTED']
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        try {

            $random_id = random_int(1000000, 9000000) + 1; // Generates a cryptographically secure random number

            $book = Book::findOrFail($bookId);

            //check the book availability before borrowing
            if($book->status == 'UNAVAILABLE'){
                return response()->json(['message' => 'The book is not available!', 'status' => 411], 411);
            }

            //Deny the user from borrowing one book twice
            if($book->id == BookLoan::where('book_id', $book->id)
                                ->where('user_id', Auth::user()->id)
                                ->where('status', 'REQUESTED')->orWhere('status', 'APPROVED')
                                ->exists())
            {
                return response()->json(['message' => 'You have already sent the request to borrow this book!', 'status' => 411], 411);
            }

            $create_loan = BookLoan::create([
                'id' => $random_id,
                'user_id' => Auth::user()->id,
                'book_id' => $book->id,
                'loan_date' => $request->loan_date,
                'return_date' => $request->return_date,
                'extended' => False,
                'extension_date' => $request->extension_date,
                'due_date' => $request->due_date,
                'penalty_amount' => $request->penalty_amount,
                'penalty_status' => $request->penalty_status,
                'status' => 'REQUESTED',
                'added_by' => Auth::user()->id,

            ]);
    
            return response()->json(['message' => 'Request to borrow the book sent successfully. It will be approved shortly! ', 'status' => 200], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error', 'Book not found! ' . $e->getMessage(), 'status' => 500], 500);
        } 
    }


    public function ApproveBookLoan(BookLoan $book_loan)
    {
        try{

            //check the current status before to proceed
            if($book_loan->status == 'APPROVED'){
                return response()->json(['message' => 'The current book loan\'s status is the same',  'status' => 411], 411);
            }
            $book_loan->update([
                'status' => 'APPROVED'
            ]);
            return response()->json(['message' => 'Book loan\'s status changed to Approved successfully',  'status' => 200], 200);
        }
        catch (\Exception $e){
            return response()->json(['message' => 'Error occured while updating ' . $e->getMessage(), 'status' => 500]);
        }
    }

    public function RejectBookLoan(BookLoan $book_loan)
    {
        try{

            //check the current status before to proceed
            if($book_loan->status == 'REJECTED'){
                return response()->json(['message' => 'The current book loan\'s status is the same',  'status' => 411], 411);
            }

            $book_loan->update([
                'status' => 'REJECTED'
            ]);
            return response()->json(['message' => 'Book loan\'s status changed to Rejected successfully',  'status' => 200], 200);
        }
        catch (\Exception $e){
            return response()->json(['message' => 'Error occured while updating ' . $e->getMessage(), 'status' => 500]);
        }
    }
}
