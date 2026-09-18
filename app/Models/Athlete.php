<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Athlete extends Model
{
    protected $fillable = [
        'runner_id',
        'nat_type',
        'id_number',
        'job_work',
        'passport_image_path',
        'visa_image_path',
        'first_name',
        'middle_name',
        'last_name',
        'father_name',
        'dob',
        'state',
        'gender',
        'blood_type',
        'has_medical_condition',
        'medical_details',
        'nationality',
        'address',
        'contact',
        'viber',
        'face_image_path',
        'has_itra',
        'itra_details',
    ];

    /**
     * Get the User associated with this Athlete
     */
    public function user()
    {
        // point runner_id in this table to runner_id in User table
        return $this->belongsTo(User::class, 'runner_id', 'runner_id');
    }
}