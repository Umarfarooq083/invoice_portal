<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function block()
    {
        return $this->belongsTo(Block::class, 'society_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'received_by', 'id');
    }
}
