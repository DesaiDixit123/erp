<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inwarding extends Model
{
    protected $fillable = [
        'raw_material_type_id',
        'purchase_date',
        'vendor_id',
        'number_of_pcs',
        'quantity'
    ];

    // Relations (optional for Eloquent ease)
    public function rawMaterialType()
    {
        return $this->belongsTo(RawMaterialType::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

}
