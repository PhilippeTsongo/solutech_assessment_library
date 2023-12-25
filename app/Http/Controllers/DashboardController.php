<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Book;
use App\Models\BookLoan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function books()
    {
        try{
            $totalBooks = Book::get();
            $availableBooks = Book::where('status', 'AVAILABLE')->get();
            $unavailableBooks = Book::where('status', 'UNAVAILABLE')->get();

            return response()->json([
                'books' => $totalBooks->count(),
                'available' => $availableBooks->count(),
                'unavailable' => $unavailableBooks->count(),
            ]);
            
        }catch(\Exception $e){
            return response()->json(['message' => 'Failed to fetch data ' .$e->getMessage(), 'status' => 500]);
        }
    }


    public function loans()
    {
        try{
            $totalLoans = BookLoan::get();
            $pendingLoans = BookLoan::where('status', 'PENDING')->get();
            $approvedLoans = BookLoan::where('status', 'APPROVED')->get();
            $rejectedLoans = BookLoan::where('status', 'REJECTED')->get();
            $extendedLoans = BookLoan::where('extended', 'YES')->get();
            $returnedLoans = BookLoan::where('status', 'RETURNED')->get();

            return response()->json([
                'loans' => $totalLoans->count(),
                'pending' => $pendingLoans->count(),
                'approved' => $approvedLoans->count(),
                'rejected' => $rejectedLoans->count(),
                'extended' => $extendedLoans->count(),
                'returned' => $returnedLoans->count(),
            ]);
            

        }catch(\Exception $e){
            return response()->json(['message' => 'Failed to fetch data ' .$e->getMessage(), 'status' => 500]);
        }
    }
}
