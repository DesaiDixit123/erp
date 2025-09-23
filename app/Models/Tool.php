<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Tool extends Model
{
     use HasFactory;

     protected $fillable = [
        'name',
        'image',
        'company_id'

    ];


     public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
