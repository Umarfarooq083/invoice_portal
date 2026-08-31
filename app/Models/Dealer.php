<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dealer extends Model
{
    use SoftDeletes;
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
