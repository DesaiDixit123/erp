<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; 

class Triming extends Model
{
    // If your table name is not 'trimings', define it explicitly
    protected $table = 'trimming_records';

    // Allow mass assignment on these fields
    protected $fillable = [
        'date',
        'shift',
        'machine_no',
        'tool_type_id',
        'operator_name1',
        'operator_name2',
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
