<?php

declare(strict_types=1);

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
        Schema::create('content_summaries', function (Blueprint $table) {
            $table->id();
            $table->text('url')->unique();
            $table->text('title')->nullable(true);
            $table->text('original_content')->nullable(true);
            $table->text('summary')->nullable(true);
            $table->integer('token_count')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_summaries');
    }
};
