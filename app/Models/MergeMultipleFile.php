<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MergeMultipleFile extends Model
{
    use HasFactory;

    protected $table = 'merge_multiple_files';
    protected $guarded = [];
}
