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
        Schema::create('dealers', function (Blueprint $table) {
            $table->id();
            $table->string('dealer_phone')->nullable();
            $table->string('name');
            $table->string('cnic');
            $table->string('dealer_state');
            $table->tinyInteger('is_parse_data')->default(0);
            $table->tinyInteger('Authorize')->default(1);
            $table->string('dealer_relation')->nullable();
            $table->string('dealer_father')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealers');
    }
};
