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

        TestCase::create([
            'problem_title' => 'Sum of Two Numbers',
            'description' => 'Given two numbers, find their sum.',
            'input' => '4 7',
            'expected_output' => '11'
        ]);

        TestCase::create([
            'problem_title' => 'Check Even or Odd',
            'description' => 'Given a number, determine if it is even or odd.',
            'input' => '6',
            'expected_output' => 'even'
        ]);

        TestCase::create([
            'problem_title' => 'Count Vowels',
            'description' => 'Given a string, count the number of vowels.',
            'input' => 'beautiful',
            'expected_output' => '5'
        ]);

        TestCase::create([
            'problem_title' => 'Find Factorial',
            'description' => 'Given a number, find its factorial.',
            'input' => '5',
            'expected_output' => '120'
        ]);

        TestCase::create([
            'problem_title' => 'Check Palindrome',
            'description' => 'Given a string, check if it is a palindrome.',
            'input' => 'madam',
            'expected_output' => 'true'
        ]);

        TestCase::create([
            'problem_title' => 'Find Second Largest Number',
            'description' => 'Given an array of numbers, find the second largest number.',
            'input' => '10 20 4 45 99',
            'expected_output' => '45'
        ]);

        TestCase::create([
            'problem_title' => 'Convert Celsius to Fahrenheit',
            'description' => 'Given a temperature in Celsius, convert it to Fahrenheit.',
            'input' => '25',
            'expected_output' => '77'
        ]);

        TestCase::create([
            'problem_title' => 'Find GCD of Two Numbers',
            'description' => 'Given two numbers, find their greatest common divisor (GCD).',
            'input' => '36 48',
            'expected_output' => '12'
        ]);

        TestCase::create([
            'problem_title' => 'Check Prime Number',
            'description' => 'Given a number, determine if it is prime.',
            'input' => '29',
            'expected_output' => 'true'
        ]);

        TestCase::create([
            'problem_title' => 'Fibonacci Sequence',
            'description' => 'Given a number n, return the first n terms of the Fibonacci sequence.',
            'input' => '5',
            'expected_output' => '0 1 1 2 3'
        ]);

    }
}
