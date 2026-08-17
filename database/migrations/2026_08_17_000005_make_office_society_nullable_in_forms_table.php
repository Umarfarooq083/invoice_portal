<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('forms', function (Blueprint $table) {
            $table->integer('office_id')->nullable(true)->change();
            $table->integer('society_id')->nullable(true)->change();
        });
    }

    public function down(): void
    {
        Schema::table('forms', function (Blueprint $table) {
            $table->integer('office_id')->nullable(false)->change();
            $table->integer('society_id')->nullable(false)->change();
        });
    }
};
