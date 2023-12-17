<?php

use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
   
    public function up()
    {
        Schema::create('book_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Book::class);
            $table->date('loan_date');
            $table->date('return_date');
            $table->boolean('extended')->default(false);
            $table->date('extension_date')->nullable();
            $table->date('due_date');
            $table->decimal('penalty_amount', 8, 2)->default(0.0);
            $table->string('penalty_status')->nullable();
            $table->string('status')->default('PENDING');
            $table->unsignedBigInteger('added_by');
            $table->timestamps();
            $table->softDeletes(); // for 'deleted_at' column
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('book_loans');
    }
};
