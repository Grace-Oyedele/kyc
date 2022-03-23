<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone_number',
        'image',
        'status',
        'kyc_level',
        'bvn',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
