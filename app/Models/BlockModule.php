<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockModule extends Model
{
    protected $table = 'block_module';
    protected $fillable = ['block_id', 'module_name'];

    public function block()
    {
        return $this->belongsTo(Block::class);
    }
}