<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasWalling extends Model
{
    use HasFactory;
    protected $table = 'faas_has_walling';
}