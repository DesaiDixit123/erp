<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawMaterialType extends Model
{
    protected $fillable = [
        'name',
        'measuring_unit',
        'opening_stock',
        'type',
    ];
    public function available()
    {
        return $this->hasMany(AvailableRawMaterial::class);
    }

    public function consumable()
    {
        return $this->hasMany(ConsumableRawMaterial::class);
    }



    public function availableRawMaterial()
    {
        return $this->hasOne(AvailableRawMaterial::class, 'raw_material_type_id');
    }


}                                   
