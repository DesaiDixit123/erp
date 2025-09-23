<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dispatch extends Model
{
    protected $fillable = [
        'date',
        'tool_type_id',
        'quantity_dispatch',
       
    ];


      public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function tool()
    {
        return $this->belongsTo(Tool::class, 'tool_type_id');
    }
}
