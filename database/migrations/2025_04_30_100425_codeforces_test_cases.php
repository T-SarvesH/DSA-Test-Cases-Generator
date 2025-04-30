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
        Schema::create('codeforces_test_cases', function (Blueprint $table) {
            $table->id();
            $table->string('Question Id')->unique();
            $table->string('Question Title');
            $table->text('Question Description');
            $table->string('Constraints');
            $table->text('Normal Test Cases');
            $table->text('Edge Test Cases');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codeforces_test_cases');
    }
};
