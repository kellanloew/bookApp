<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookView extends Model
{
    public $title;
    public $description;
    public $authors;
    public $ISBN;
    public $pages;
}
