<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory, Searchable;

    protected $guarded = [];

    public array $searchable = [
        'name','rate'
    ];
}
