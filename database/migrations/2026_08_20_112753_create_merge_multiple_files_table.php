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
        Schema::create('merge_multiple_files', function (Blueprint $table) {
            $table->id();
            $table->integer('merge_to');
            $table->string('reg_no');
            $table->integer('merge_society_id');
            $table->string('security_code');
            $table->string('size');
            $table->integer('created_by');
            $table->integer('invoice_merge_id');
            $table->integer('box_no');
            $table->integer('sr_no');
            $table->integer('case_no');
            $table->integer('office_id');
            $table->string('tracking_code');
            $table->integer('ledger_amount')->nullable();
            $table->integer('merging_fee')->nullable();
            $table->integer('mapping_id')->nullable();
            $table->integer('payment_plan_plot_price')->default(0);
            $table->integer('payment_plan_live_id')->nullable();
            $table->integer('payment_plan_down_payment')->default(0);
            $table->integer('legder_down_payment')->default(0);
            $table->integer('legder_plot_price')->default(0);
            $table->integer('sum_payment')->default(0);
            $table->integer('received_downpayment')->default(0);
            $table->integer('is_live_merge')->default(0);
            $table->string('merge_app_type')->nullable();
            $table->string('to_qr_code')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merge_multiple_files');
    }
};
