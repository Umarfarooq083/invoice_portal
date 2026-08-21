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
        Schema::create('invoice_merge', function (Blueprint $table) {
            $table->id();
            $table->integer('file_id')->nullable();
            $table->string('reg_no')->nullable();
            $table->string('security_code')->nullable();
            $table->string('size')->nullable();
            $table->integer('box_no');
            $table->integer('sr_no')->default(0);
            $table->string('tracking_code');
            $table->string('status')->default('In-Process');
            $table->time('time')->nullable();
            $table->integer('received_by');
            $table->integer('dealer_id')->nullable();
            $table->integer('office_id')->nullable();
            $table->integer('merge_to')->nullable();
            $table->integer('society_id')->nullable();
            $table->integer('is_open');
            $table->string('from_reg_no')->nullable();
            $table->string('from_security_code')->nullable();
            $table->string('from_size')->nullable();
            $table->integer('case_no')->default(0);
            $table->string('client_name')->nullable();
            $table->string('client_cnic')->nullable();
            $table->string('client_contact')->nullable();
            $table->string('plot_type')->nullable();
            $table->string('address')->nullable();
            $table->string('dealer_name');
            $table->string('dealer_phone');
            $table->integer('transfer_status')->default(0);
            $table->string('collection_address')->nullable();
            $table->integer('payment_plan_plot_price')->default(0);
            $table->integer('payment_plan_live_id')->default(0);
            $table->integer('payment_plan_down_payment')->default(0);
            $table->integer('legder_down_payment')->default(0);
            $table->integer('legder_plot_price')->default(0);
            $table->integer('sum_payment')->default(0);
            $table->integer('received_downpayment')->default(0);
            $table->integer('is_parse_data')->default(0);
            $table->string('submitter_cnic')->nullable();
            $table->integer('map_id')->nullable();
            $table->integer('dt_flag')->default(0);
            $table->string('builder_type')->nullable();
            $table->string('sub_category')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_merge');
    }
};
