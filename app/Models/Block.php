<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Block extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name'];

    public function modules()
    {
        return $this->hasMany(BlockModule::class);
    }

    public function blockRoles()
    {
        return $this->belongsToMany(BlockRole::class, 'block_role_assignments');
    }
}
