<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Change the box_no column from integer to string so that
     * date-derived codes (e.g. 030826) preserve leading zeros.
     */
    public function up(): void
    {
        Schema::table('forms', function (Blueprint $table) {
            $table->string('box_no', 6)->change();
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::table('forms', function (Blueprint $table) {
            $table->integer('box_no')->change();
        });
    }
};
