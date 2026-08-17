<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Form extends Model
{
    use HasFactory;

    protected $table = 'forms';

    protected $fillable = [
        'rebate_id',
        'form_no',
        'inv_reg_no',
        'client_name',
        'client_cnic',
        'tracking_code',
        'size',
        'reg_type',
        'address',
        'office_id',
        'box_no',
        'sr_no',
        'user_id',
        'down_payment',
        'society_id',
        'contact',
        'deposite_slip_no',
        'form_type',
        'plot_price',
        'dealer_name',
        'villa_no',
        'is_create_live',
        'payment_plan_id',
        'qr_code',
        'system_created_at',
        'is_member_transfer',
        'cron_updated_at',
        'dealer_id',
        'submitted_by',
        'submitter_cnic',
        'phase_id',
        'return_issue_id',
        'submitter_contact',
        'awami_green_form_live_id',
    ];

    protected $casts = [
        'is_create_live'     => 'boolean',
        'is_member_transfer' => 'boolean',
        'down_payment'       => 'integer',
        'plot_price'         => 'integer',
        'system_created_at'  => 'datetime',
        'cron_updated_at'    => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    /**
     * The user who submitted this form.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
