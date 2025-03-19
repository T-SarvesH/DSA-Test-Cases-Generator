<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestCase extends Model
{
    use HasFactory;
    protected $fillable = ['problem_title', 'description', 'input', 'expected_output'];
}
