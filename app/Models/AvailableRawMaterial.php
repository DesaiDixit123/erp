<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvailableRawMaterial extends Model
{
    protected $fillable = [
        'raw_material_type_id',
        'quantity',
    ];
    public function rawMaterialType()
    {
        return $this->belongsTo(RawMaterialType::class);
    }

}
