<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merger extends Model
{
    use HasFactory;

    protected $table = 'invoice_merge';
    protected $guarded = [];

    public function block()
    {
        return $this->belongsTo(Block::class, 'society_id', 'id');
    }

    public function dealer()
    {
        return $this->belongsTo(Dealer::class, 'dealer_id', 'id');
    }
}
