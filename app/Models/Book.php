<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $fillable = ['ISBN', 'title', 'author', 'position'];
    protected $primaryKey = 'ISBN';

    public function doesExistInDb(){
        $existing = $this::where('ISBN', '=', $this->ISBN)->first();
        if($existing != null) return true;
        else return false;
    }
}
