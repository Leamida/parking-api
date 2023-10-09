<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingBlock extends Model
{
    use HasFactory;

    public function slots()
    {
        return $this->hasMany(ParkingSlot::class);
    }
}
