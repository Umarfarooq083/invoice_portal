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
        Schema::create('apr', function (Blueprint $table) {
            $table->id();
            $table->integer('file_id');
            $table->integer('box_no');
            $table->integer('sr_no')->default(0);
            $table->string('tracking_code');
            $table->string('status')->default('In-Process')->nullable();
            $table->time('time')->nullable();
            $table->integer('received_by');
            $table->integer('dealer_id')->nullable();
            $table->integer('office_id')->nullable();
            $table->integer('society_id')->nullable();
            $table->integer('is_open')->default(0)->nullable();
            $table->string('from_reg_no')->nullable();
            $table->string('backup')->nullable();
            $table->string('from_security_code')->nullable();
            $table->string('from_size')->nullable();
            $table->integer('case_no')->nullable();
            $table->string('client_name')->nullable();
            $table->string('client_cnic')->nullable();
            $table->string('client_contact')->nullable();
            $table->string('plot_type')->nullable();
            $table->text('address')->nullable();
            $table->string('dealer_name');
            $table->string('dealer_phone');
            $table->integer('transfer_status')->default(0);
            $table->text('collection_address')->nullable();
            $table->bigInteger('payment_plan_plot_price')->default(0);
            $table->integer('payment_plan_live_id')->default(0);
            $table->bigInteger('payment_plan_down_payment')->default(0);
            $table->bigInteger('legder_down_payment')->default(0);
            $table->bigInteger('legder_plot_price')->default(0);
            $table->bigInteger('sum_payment')->default(0);
            $table->bigInteger('received_downpayment')->default(0);
            $table->string('user_custom_box_no')->nullable();
            $table->string('user_custom_serial_no')->default(0);
            $table->string('qr_code')->nullable();
            $table->integer('is_parse_data')->default(0);
            $table->integer('sector_id')->nullable();
            $table->string('submitter_cnic')->nullable();
            $table->string('user_type')->default(1);
            $table->integer('dealer_account_id')->nullable();
            $table->integer('dealer_account_credit_balance_id')->nullable();
            $table->integer('is_dc_led')->default(0);
            $table->integer('app_year')->nullable();
            $table->date('dc_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apr');
    }
};
