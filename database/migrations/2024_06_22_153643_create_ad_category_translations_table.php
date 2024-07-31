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
        Schema::create('ad_category_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_category_id')->constrained()->onDelete('cascade');
            $table->string('locale');
            $table->string('title');
            
            $table->unique(['ad_category_id','locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_category_translations');
    }
};
