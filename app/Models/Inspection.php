<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
      protected $fillable = [
        'date',
        'tool_type_id',
        'quantity_inspected',
        'ok_quantity',
        'rejected_quantity',
        'rejection_reason',
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
