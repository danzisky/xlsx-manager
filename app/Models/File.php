<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    public function header() {
        return $this->hasOne(Header::class);
    }
    public function row() {
        return $this->hasMany(Row::class);
    }
}
