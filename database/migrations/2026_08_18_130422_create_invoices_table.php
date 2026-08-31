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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('file_id');
            $table->string('reg_no')->nullable();
            $table->string('security_code')->nullable();
            $table->string('size')->nullable();
            $table->integer('box_no');
            $table->string('sr_no');
            $table->string('tracking_code');
            $table->string('status')->default('In-Process');
            $table->time('time')->nullable();
            $table->integer('received_by');
            $table->integer('dealer_id')->nullable();
            $table->integer('office_id')->nullable();
            $table->string('client_name')->nullable();
            $table->string('client_cnic')->nullable();
            $table->integer('society_id')->nullable();
            $table->integer('print_counter')->default(0);
            $table->text('address')->nullable();
            $table->string('contact')->nullable();
            $table->string('plot_type')->nullable();
            $table->string('dealer_name');
            $table->string('dealer_phone');
            $table->tinyInteger('transfer_status')->default(0);
            $table->text('collection_address')->nullable();
            $table->bigInteger('downpayment')->nullable();
            $table->integer('payment_plan_id')->nullable();
            $table->integer('payment_plan_plot_price')->default(0);
            $table->integer('payment_plan_live_id')->nullable();
            $table->integer('payment_plan_down_payment')->default(0);
            $table->integer('legder_down_payment')->default(0);
            $table->integer('legder_plot_price')->default(0);
            $table->integer('sum_payment')->default(0);
            $table->integer('received_downpayment')->default(0);
            $table->bigInteger('plot_price')->default(0);
            $table->date('printing_date')->nullable();
            $table->tinyInteger('is_deleted')->default(0);
            $table->integer('deleted_by')->nullable();
            $table->text('delete_comments')->nullable();
            $table->integer('old_down_payment')->nullable();
            $table->string('qr_code')->nullable();
            $table->timestamp('system_created_at')->nullable();
            $table->tinyInteger('is_member_transfer')->default(0);
            $table->tinyInteger('is_create_live')->default(0);
            $table->timestamp('cron_updated_at')->nullable();
            $table->tinyInteger('is_adeel_block')->default(0);
            $table->string('submitter_cnic')->nullable();
            $table->integer('phase_id')->nullable();
            $table->string('live_form_no')->nullable();
            $table->integer('is_allow_live')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
