<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Codeforces_Model extends Model
{
    protected $table = 'codeforces_test_cases';
    protected $fillable = [
        'Question Id',
        'Question Title',
        'Question Description',
        'Constraints',
        'Follow Ups',
        'Normal Test Cases',
        'Edge Test Cases'
    ];
}
