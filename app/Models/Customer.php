<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory, Searchable;

    protected $guarded = [];

    public array $searchable = [
        'name','email','contact','country','city','address'
    ];
}
