<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('forms', function (Blueprint $table) {
            if (! Schema::hasColumn('forms', 'block_id')) {
                $table->unsignedBigInteger('block_id')->nullable()->after('phase_id');
            }

            if (! Schema::hasColumn('forms', 'reg_type')) {
                $table->string('reg_type')->default('residential')->after('block_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('forms', function (Blueprint $table) {
            if (Schema::hasColumn('forms', 'block_id')) {
                $table->dropColumn('block_id');
            }
        });
    }
};
