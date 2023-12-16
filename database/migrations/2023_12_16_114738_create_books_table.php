<?php

use App\Models\User;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('publisher');
            $table->string('isbn')->unique();
            $table->foreignIdFor(Category::class);
            $table->foreignIdFor(Subcategory::class);
            $table->text('description')->nullable();
            $table->integer('page');
            $table->string('status')->default('AVAILABLE');
            $table->string('image')->nullable();
            $table->unsignedBigInteger('added_by');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
};
