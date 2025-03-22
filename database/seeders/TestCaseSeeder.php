<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TestCase;

class TestCaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TestCase::create([
            'problem_title' => 'Find Maximum',
            'description' => 'Given an array of numbers, find the maximum number.',
            'input' => '1 5 3 9 2',
            'expected_output' => '9'
        ]);

        TestCase::create([
            'problem_title' => 'Reverse a String',
            'description' => 'Given a string, reverse it.',
            'input' => 'hello',
            'expected_output' => 'olleh'
        ]);
    }
}
