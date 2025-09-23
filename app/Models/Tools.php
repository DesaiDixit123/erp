<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tools extends Model
{
        use HasFactory;

    protected $table = 'tools_dispatches'; // 👈 Table name

    protected $fillable = [
        'component_id',
        'component_type',
        'tool_number',
        'manufacturing_date',
        'quantity_dispatch',
    ];
protected $casts = [
    'manufacturing_date' => 'date',
];

    /**
     * Relationship: ToolDispatch belongs to Tool (component)
     */
    public function component()
    {
        return $this->belongsTo(Tool::class, 'component_id');
    }
}
