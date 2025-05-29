<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class leetcode_daily extends Model
{
    use HasFactory;
    protected $table = 'leetcode_daily_question';
    protected $fillable = [
        'title',
        'topic_tags',
        'link',
    ];
    protected $casts = [
        'topic_tags' => 'array',
    ];
}
