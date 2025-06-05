<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EanScan extends Model
{
    use HasFactory;

    protected $fillable = ['codigo_ean'];
}
