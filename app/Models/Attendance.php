<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function overtime()
    {
        return $this->hasOne(Overtime::class);
    }

    public function activitytypeclockin()
    {
        return $this->belongsTo(ActivityType::class, 'clock_in_activity_type_id');
    }

    public function activitycategoryclockin()
    {
        return $this->belongsTo(ActivityCategory::class, 'clock_in_activity_category_id');
    }

    public function activitytypeclockout()
    {
        return $this->belongsTo(ActivityType::class, 'clock_out_activity_type_id');
    }

    public function activitycategoryclockout()
    {
        return $this->belongsTo(ActivityCategory::class, 'clock_out_activity_category_id');
    }
}
