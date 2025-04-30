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
        Schema::create('leetcode_test_cases', function (Blueprint $table) {
            $table->id();
            $table->integer('Question Id')->unique();
            $table->string('Question Title');
            $table->string('Question Description');
            $table->string('Constraints');
            $table->string('Follow Ups')->nullable();
            $table->string('Normal Test Cases');
            $table->string('Edge Test Cases');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leetcode_test_cases');
    }
};
