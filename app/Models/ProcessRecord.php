<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcessRecord extends Model
{
    protected $table = 'process_records';
    protected $fillable = ['type_name', 'date', 'shift', 'machine_no', 'tool_type_id', 'machine_operator_name', 'quantity_kg', 'quantity_pcs','quantity_inspected','ok_number','rejected_number','reason_of_rejected'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function tool()
    {
        return $this->belongsTo(Tool::class, 'tool_type_id');
    }
}
