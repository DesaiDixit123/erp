<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CastingRecord extends Model
{
    protected $fillable = [
        'date',
        'shift',
        'machine_no',
        'tool_type_id',
        'machine_operator_name1',
        'machine_operator_name2',
        'quantity_kg',
        'quantity_pcs'
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
