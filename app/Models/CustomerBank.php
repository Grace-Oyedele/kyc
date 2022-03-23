<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerBank extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'bank_name',
        'bank_code',
        'account_name',
        'account_number',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
