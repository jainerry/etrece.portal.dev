<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class HasFlooring extends Model
{
    use HasFactory;
    use HasUuids;
    protected $table = 'faas_has_flooring';
}
