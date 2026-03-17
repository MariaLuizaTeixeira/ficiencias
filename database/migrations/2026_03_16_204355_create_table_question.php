<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50, ['multiple_choice', 'discursive', 'practical']);
            $table->integer('answer');
            $table->integer('unity');
            $table->integer('phase');
            $table->integer('section');
        });
    }
};
