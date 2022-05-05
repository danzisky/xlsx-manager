<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'path',
        'description',
        'has_header',
    ];

    public function header() {
        return $this->hasOne(Header::class);
    }
    public function row() {
        return $this->hasMany(Row::class);
    }
}
