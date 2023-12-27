<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Book;
use App\Models\User;
use App\Models\BookLoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\ModelNotFoundException;

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

    public function users()
    {
        try{
            $totalUsers = User::get();
            $activeUsers = User::where('status', 'ACTIVE')->get();
            $inactiveUsers = User::where('status', 'INACTIVE')->get();

            return response()->json([
                'users' => $totalUsers->count(),
                'active' => $activeUsers->count(),
                'inactive' => $inactiveUsers->count(),
            ]);
            
        }catch(\Exception $e){
            return response()->json(['message' => 'Failed to fetch data ' .$e->getMessage(), 'status' => 500]);
        }
    }


    public function loans()
    {
        try{

            $user_role = Auth::user()->role_id;
            $auth_user = Auth::user()->id;
        
            //User must see only information about his loans
            if($user_role == 1)
            {
                $totalLoans = BookLoan::get();
                $pendingLoans = BookLoan::where('status', 'PENDING')->get();
                $approvedLoans = BookLoan::where('status', 'APPROVED')->get();
                $rejectedLoans = BookLoan::where('status', 'REJECTED')->get();
                $extendedLoans = BookLoan::where('extended', 1)->get();
                $returnedLoans = BookLoan::where('status', 'RETURNED')->get();

                return response()->json([
                    'loans' => $totalLoans->count(),
                    'pending' => $pendingLoans->count(),
                    'approved' => $approvedLoans->count(),
                    'rejected' => $rejectedLoans->count(),
                    'extended' => $extendedLoans->count(),
                    'returned' => $returnedLoans->count(),
                ]);
            }else{
                $totalLoans = BookLoan::where('user_id', $auth_user)->get();
                $pendingLoans = BookLoan::where('status', 'PENDING')->where('user_id', $auth_user)->get();
                $approvedLoans = BookLoan::where('status', 'APPROVED')->where('user_id', $auth_user)->get();
                $rejectedLoans = BookLoan::where('status', 'REJECTED')->where('user_id', $auth_user)->get();
                $extendedLoans = BookLoan::where('extended', 1)->where('user_id', $auth_user)->get();
                $returnedLoans = BookLoan::where('status', 'RETURNED')->where('user_id', $auth_user)->get();

                return response()->json([
                    'loans' => $totalLoans->count(),
                    'pending' => $pendingLoans->count(),
                    'approved' => $approvedLoans->count(),
                    'rejected' => $rejectedLoans->count(),
                    'extended' => $extendedLoans->count(),
                    'returned' => $returnedLoans->count(),
                ]);
            }

        }catch(\Exception $e){
            return response()->json(['message' => 'Failed to fetch data ' .$e->getMessage(), 'status' => 500]);
        }
    }



}
