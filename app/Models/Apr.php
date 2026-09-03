<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apr extends Model
{
    use HasFactory;

    protected $table = 'apr';
    protected $guarded = [];

    public function block()
    {
        return $this->belongsTo(Block::class, 'society_id', 'id');
    }

    public function dealer()
    {
        return $this->belongsTo(Dealer::class, 'dealer_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'received_by', 'id');
    }
}
