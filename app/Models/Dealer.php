<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dealer extends Model
{
    protected $fillable = [
        'dealer_phone',
        'name',
        'cnic',
        'dealer_state',
        'is_parse_data',
        'Authorize',
        'dealer_relation',
        'dealer_father',
        'created_by',
    ];
}
