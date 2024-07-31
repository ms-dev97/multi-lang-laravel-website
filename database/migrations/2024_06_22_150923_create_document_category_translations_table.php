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
        Schema::create('document_category_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doc_cat_id')->constrained(
                table: 'document_categories', indexName: 'id'
            )->onDelete('cascade');
            $table->string('locale');
            $table->string('title');
            
            $table->unique(['doc_cat_id','locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_category_translations');
    }
};
