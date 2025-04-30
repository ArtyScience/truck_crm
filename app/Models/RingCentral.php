<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RingCentral extends Model
{
    use HasFactory;

    protected $table = 'ringcentral_calls';

    protected $fillable = ['file_path', 'record_id', 'from', 'to', 'status', 'started_at'];

    protected $casts = [
        'started_at' => 'datetime', // Cast the column to a Carbon instance
    ];
}
