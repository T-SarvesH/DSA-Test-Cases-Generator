<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestCase extends Model
{
    protected $fillable = ['problem_title', 'description', 'input', 'expected_output'];
}
