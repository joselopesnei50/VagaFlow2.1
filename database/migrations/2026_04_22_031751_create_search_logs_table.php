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
        Schema::create('search_logs', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $blueprint->string('query')->nullable();
            $blueprint->string('location')->nullable();
            $blueprint->string('service')->default('serper'); // serper, jsearch
            $blueprint->string('status')->default('success'); // success, error
            $blueprint->text('error_message')->nullable();
            $blueprint->integer('results_count')->default(0);
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('search_logs');
    }
};
