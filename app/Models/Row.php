<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Row extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_id',
        'content',
        'row',
        'column',
    ];

    public function file() {
        return $this->belongsTo(File::class);
    }
}
