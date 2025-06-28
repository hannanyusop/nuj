<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnregisteredParcel extends Model
{
    use HasFactory;

    public function parcels(){
        return $this->hasOne(Parcel::class, 'id', 'parcel_id');
    }
}
