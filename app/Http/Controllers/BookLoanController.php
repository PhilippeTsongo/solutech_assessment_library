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
   
    //List of loan function
    public function index()
    {
        try{
            $loans = BookLoan::orderBy('created_at', 'DESC')->get();

            $pending = BookLoan::where('status', 'PENDING')->get();
            $approved = BookLoan::where('status', 'APPROVED')->get();
            $extended = BookLoan::where('extended', 1)->get();
            $returned = BookLoan::where('status', 'RETURNED')->get();
            $rejected = BookLoan::where('status', 'REJECTED')->get();


            foreach ($loans as $loan) {
                $loan_user = $loan->user;
                $loan_book = $loan->book;
            }

            $total_pending = $pending->count();
            $total_approved = $approved->count();
            $total_extended = $extended->count();
            $total_returned = $returned->count();
            $total_rejected = $rejected->count();

            $total = $loans->count();

            $data = array(
                'message' => "success",
                'loans' => $loans,
                'pending' => $total_pending,
                'approved' => $total_approved,
                'extended' => $total_extended,
                'returned' => $total_returned,
                'rejected' => $total_rejected,
                'total' => $total,
                'status' => 200
            );
                
            return response()->json($data, 200);
            
            }catch(\Exception $e){
                    return response()->json(['message' => 'Failed to fetch loans' . $e->getMessage(), 'status' => 500]);
        }
    }

    //Unique loan function
    public function show($loan)
    {
        try{
            //find all the loan. All row with the property 'deleted_at' are excluded (soft delete)
            $book_loan = BookLoan::findOrFail($loan);

            $data = array(
                'message' => 'success',
                'loan' => $book_loan,
                'status' => 200
            );
            return response()->json($data, 200);

        } catch (\Exception $e) {
            // Something went wrong
            return response()->json(['error' => 'Failed to fetch book loan. ' . $e->getMessage()], 500);
        }
    }

    //borrow book function. it creates a loan and set it to a STATUS status
    public function borrowBook (Request $request, $bookId)
    {

        $validator = Validator::make($request->all(), [
            // 'user' => ['required', 'exists:users,id'],
            // 'book' => ['required', 'exists:books,id'],
            'loan_date' => ['required', 'date'],
            // 'return_date' => ['nullable', 'date', 'after_or_equal:loan_date'],
            // 'extended' => ['required', 'boolean'],
            // 'extension_date' => $request->get('extended') ? 'required_if:extended,1|nullable|date|after_or_equal:return_date' : '',
            'due_date' => ['required', 'date', 'after_or_equal:loan_date'],
            'penalty_status' => ['required', 'in:CHARGED,NOT_CHARGED'],
            'penalty_amount' => ['required_if:penalty_status,CHARGED', 'nullable', 'numeric', 'min:0'],
            // 'status' => ['required', 'in:PENDING']
        ]);

        //return the validation error
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
                                ->where('status', 'APPROVED')
                                ->exists())
            {
                return response()->json(['message' => 'You have already sent the request to borrow this book!', 'status' => 400], 400);
            }

            //create the loan
            $create_loan = BookLoan::create([
                'id' => $random_id,
                'user_id' => Auth::user()->id,
                'book_id' => $book->id,
                'loan_date' => $request->loan_date,
                // 'return_date' => $request->return_date,
                'extended' => False,
                // 'extension_date' => $request->extension_date,
                'due_date' => $request->due_date,
                'penalty_amount' => $request->penalty_amount,
                'penalty_status' => $request->penalty_status,
                'status' => 'PENDING',
                'added_by' => Auth::user()->id // Auth user requesting a loan

            ]);
            
            return response()->json(['message' => 'Request to borrow the book sent successfully. It will be approved shortly! ', 'status' => 200], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Book not found! ' . $e->getMessage(), 'status' => 500], 500);
        } 
    }


    //Approve book loan function
    public function ApproveBookLoan($loan)
    {
        try{

            // Find the loan
            $book_loan = BookLoan::findOrFail($loan);

            //check the current status before to proceed
            if($book_loan->status !== 'PENDING'){
                return response()->json(['message' => 'The book loan\'s status is not pending ',  'status' => 411], 411);
            }
            $book_loan->update([
                'status' => 'APPROVED',
                'added_by' => Auth::user()->id //Auth Admin approving a loan
            ]);
            return response()->json(['message' => 'Book loan\'s status changed to Approved successfully',  'status' => 200], 200);
        }
        catch (\Exception $e){
            return response()->json(['message' => 'Error occured while updating ' . $e->getMessage(), 'status' => 500]);
        }
    }

    //Reject a book loan function
    public function RejectBookLoan($loan)
    {
        try{
            // Find the loan
            $book_loan = BookLoan::findOrFail($loan);

            //check the current status before to proceed
            if($book_loan->status == 'REJECTED'){
                return response()->json(['message' => 'Current book loan\'s status has already been Rejected',  'status' => 411], 411);
            }

            $book_loan->update([
                'status' => 'REJECTED',
                'added_by' => Auth::user()->id //Auth Admin rejecting a loan
            ]);
            return response()->json(['message' => 'Book loan\'s status changed to Rejected successfully',  'status' => 200], 200);
        }
        catch (\Exception $e){
            return response()->json(['message' => 'Error occured while updating ' . $e->getMessage(), 'status' => 500]);
        }
    }


    public function extendLoan(Request $request, $loan)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'extension_date' => 'required|date|after_or_equal:loan_date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        try{
            // Find the loan
            $book_loan = BookLoan::findOrFail($loan);

            // Check if the loan is approved
            if ($book_loan->status !== 'APPROVED') {
                return response()->json(['message' => 'This Loan is not approved.', 'status'=> 411], 411);
            }

            // Check if the loan has once bee extended to prevent a new extension
            if ($book_loan->extended) {
                return response()->json(['message' => 'This Loan is already extended.', 'status'=> 411], 411);
            }

            // Update loan extension details
            $book_loan->extended = true;
            $book_loan->extension_date = $request->extension_date;
            $book_loan->save();

            return response()->json(['message' => 'This Loan is extended successfully.']);
        }catch(\Exception $e){
            return response()->json(['error' => 'Error occured while extending the loan. ' . $e->getMessage(), 'status' => 500 ], 500);
        }
    }

    //Return a book
    public function returnBook($loan)
    {
        
        try{
            // Find the loan
            $book_loan = BookLoan::findOrFail($loan);

            // Check if the loan is approved and not already returned
            if ($book_loan->status !== 'APPROVED' || $book_loan->return_date !== null) {
                return response()->json(['message' => 'Invalid operation.', 'status' => 411], 411);
            }

            $today = date('Y-m-d');
            $auth = Auth::user()->id;
            if($book_loan->penalty_status == 'CHARGED'){
                $book_loan->update([
                    'due_date' => $today,
                ]);
            }

            // Update loan return details
            $book_loan->update([
                'return_date' => $today,
                'status' => 'RETURNED',
                'added_by' => $auth
            ]);

            return response()->json(['message' => 'Book returned successfully.']);

        }catch(\Exception $e){
            return response()->json(['error' => 'Error occured while returning the loan. ' . $e->getMessage(), 'status' => 500 ], 500);
        }
    }
}
