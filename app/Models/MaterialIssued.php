<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class MaterialIssued extends Model
{
   use HasFactory;

    protected $fillable = [
        'raw_material_type_id',
        'issue_date',
        'quantity',
        'shift',
    ];

    public function rawMaterialType()
    {
        return $this->belongsTo(RawMaterialType::class);
    }
}
