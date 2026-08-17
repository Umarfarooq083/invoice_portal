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
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->integer('rebate_id')->nullable();
            $table->string('form_no');
            $table->string('inv_reg_no')->nullable();
            $table->string('client_name');
            $table->string('client_cnic');
            $table->string('tracking_code');
            $table->string('size');
            $table->string('reg_type');
            $table->text('address');
            $table->integer('office_id');
            $table->integer('box_no');
            $table->integer('sr_no');
            $table->integer('user_id');
            $table->integer('down_payment');
            $table->integer('society_id');
            $table->string('contact');
            $table->string('deposite_slip_no')->nullable();
            $table->tinyInteger('form_type')->nullable();
            $table->integer('plot_price')->nullable();
            $table->string('dealer_name')->nullable();
            $table->string('villa_no')->nullable();
            $table->tinyInteger('is_create_live')->default(0);
            $table->integer('payment_plan_id')->nullable();
            $table->string('qr_code')->nullable();
            $table->timestamp('system_created_at')->nullable();
            $table->tinyInteger('is_member_transfer')->default(0);
            $table->timestamp('cron_updated_at')->nullable();
            $table->integer('dealer_id')->nullable();
            $table->string('submitted_by')->nullable();
            $table->string('submitter_cnic')->nullable();
            $table->integer('phase_id')->nullable();
            $table->unsignedBigInteger('return_issue_id')->nullable();
            $table->string('submitter_contact')->nullable();
            $table->integer('awami_green_form_live_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forms');
    }
};
