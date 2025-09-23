<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
  protected $fillable = [
    'name',
    'email',
    'phone',
    'address',
    'password',
    'avatar',
    'adhar_image',
    'pan_image',
    'user_type',
    'user_status',
];

}
