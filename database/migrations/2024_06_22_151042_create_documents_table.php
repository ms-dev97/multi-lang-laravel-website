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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('path')->nullable();
            $table->string('link')->nullable();
            $table->boolean('get_from_link')->default(false);
            $table->string('image')->nullable();
            $table->foreignId('document_category_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('status')->default(true);
            $table->boolean('featured')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
