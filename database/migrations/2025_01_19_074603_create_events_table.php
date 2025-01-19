<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->foreignId('category_id') // Foreign key to categories table
                ->constrained('categories')
                ->onDelete('cascade'); // Deletes events if the category is deleted
            $table->string('name'); // Event name
            $table->text('description');
            $table->string('image'); // Cover image for the event
            $table->json('gallery')->nullable(); // JSON to store image gallery (array of strings)
            $table->decimal('price', 10, 2)->default(0.00); // Price with precision of 2 decimal places
            $table->unsignedTinyInteger('rating')->nullable(); // Rating (1-5 or similar)
            $table->json('location')->nullable(); // JSON to store location data
            $table->timestamps(); // created_at and updated_at columns
            $table->softDeletes(); // deleted_at column for soft deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
