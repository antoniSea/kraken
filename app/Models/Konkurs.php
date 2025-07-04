<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konkurs extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function files()
    {
        return $this->hasManyThrough(Plik::class, User::class);
    }
} 