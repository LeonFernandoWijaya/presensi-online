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

    public function activitytype()
    {
        return $this->belongsTo(ActivityType::class, 'activity_type_id');
    }

    public function activitycategory()
    {
        return $this->belongsTo(ActivityCategory::class, 'activity_category_id');
    }

    public function clockinstatus()
    {
        return $this->belongsTo(Status::class, 'clockInStatusId');
    }

    public function clockoutstatus()
    {
        return $this->belongsTo(Status::class, 'clockOutStatusId');
    }
}
