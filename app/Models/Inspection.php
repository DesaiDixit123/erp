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
        'sound_test',
        'blow_hole',
        'trimming',
        'casting',
        'non_filling',
        'rejection_reason',
        'total_rejected'
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
