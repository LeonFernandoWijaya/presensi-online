<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftDay extends Model
{
    use HasFactory;

    protected $fillable = ['shift_id', 'dayName', 'startHour', 'endHour'];
}
