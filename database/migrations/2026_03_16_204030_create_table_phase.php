<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phase', function (Blueprint $table) {
            $table->id();
            $table->integer('unity');
            $table->integer('section');
            $table->integer('guide');
        });
    }
};
