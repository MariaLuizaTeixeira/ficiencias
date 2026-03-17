<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nickname', 50)->nullable(false)->change();
            $table->string('gender')->nullable(false)->change();
            $table->date('birth_date')->nullable(false)->change();
            $table->string('phone_number')->nullable(false)->change();
        });
    }
};
