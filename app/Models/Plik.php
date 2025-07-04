<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plik extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'konkurs_id', 'path', 'original_name', 'size'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function konkurs()
    {
        return $this->belongsTo(Konkurs::class);
    }
} 