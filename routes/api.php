<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookLoanController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\UserRoleController;
use App\Http\Controllers\BorrowBookController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;



// Route::middleware('api')->group(function () {
    // Your API routes here

    Route::get('/', function () { 
        return view('index');
    })->middleware(['auth']);

    //optimization routes
    Route::get('/optimize', function(){
        $exitCode = Artisan::call('optimize');
        return 'DONE';
    });
    Route::get('/cache', function(){
        $exitCode = Artisan::call('cache:clear');
        $exitCode = Artisan::call('config:cache');
        return 'DONE';
    });

    
    
    //user authentication route
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');

    //Auth routes
    Route::middleware('auth:sanctum')->group(function () {

        //user
        Route::get('/user/authenticated', [AuthenticatedSessionController::class, 'authenticatedUser']);

        Route::resource('/user', UserController::class);
        Route::resource('/user/role', UserRoleController::class);

        //user status
        Route::post('/user/activate/status/{user}', [UserController::class, 'activateUserStatus']);
        Route::post('/user/deactivate/status/{user}', [UserController::class, 'deactivateUserStatus']);
        
        //Log out
        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
         
        //profile
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        //book
        Route::resource('/book', BookController::class);

        //book status
        Route::post('/book/status/available/{book}', [BookController::class, 'availableStatus']);
        Route::post('/book/status/unavailable/{book}', [BookController::class, 'unavailableStatus']);

        //book loan 
        Route::resource('/book_loan', BookLoanController::class);
        //borrow a book
        Route::post('/book/borrow/{bookId}', [BookLoanController::class, 'borrowBook']);
        
        //Book loan status
        Route::post('/book/loan/approve/{book_loan}', [BookLoanController::class, 'ApproveBookLoan']);
        Route::post('/book/loan/reject/{book_loan}', [BookLoanController::class, 'RejectBookLoan']);

        //Book loan Extend date
        Route::post('/book/extend/loan/{book_loan}', [BookLoanController::class, 'extendLoan']);

        Route::post('/book/loan/return/{book_loan}', [BookLoanController::class, 'returnBook']);

        
        Route::resource('/category', CategoryController::class);
        Route::resource('/subcategory', SubcategoryController::class);        


    });
