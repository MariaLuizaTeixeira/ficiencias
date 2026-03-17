<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nickname', 50)->nullable();
            $table->string('gender', 50, ['M', 'F', 'N/A'])->nullable();
            $table->integer('points')->default(0);
            $table->date('birth_date')->nullable();
            $table->string('phone_number', 15)->nullable();
            $table->integer('phase_id')->nullable()->default(0);
            $table->renameColumn('name', 'full_name');
        });
    }
};
