<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class MainController extends Controller
{
    public function home(): View
    {
        return view('home');
    }

    public function generateExcercises(Request $request)
    {
        $request->validate([
            'check_sum' => 'required_without_all:check_subtraction,check_multiplication,check_division',
            'check_subtraction' => 'required_without_all:check_sum,check_multiplication,check_division',
            'check_multiplication' => 'required_without_all:check_sum,check_subtraction,check_division',
            'check_division' => 'required_without_all:check_sum,check_subtraction,check_multiplication',
            'number_one' => 'required|integer|min:0|max:999|lt:number_two',
            'number_two' => 'required|integer|min:0|max:999',
            'number_exercises' => 'required|integer|min:5|max:50',
        ]);

        $operations = [];
        $operations_to_check = [
            'sum' => 'check_sum',
            'subtraction' => 'check_subtraction',
            'multiplication' => 'check_multiplication',
            'division' => 'check_division',
        ];

        foreach ($operations_to_check as $operation => $property)
        {
            if ($request->$property)
            {
                $operations[] = $operation;
            }
        }

        // get numbers (min and max)
        $min = $request->number_one;
        $max = $request->number_two;

        // get number of exercises
        $numberOfExercises = $request->number_exercises;

        // generate exercises
        $exercises = [];
        for ($i = 0; $i < $numberOfExercises; $i++)
        {

            $operation = $operations[array_rand($operations)];

            $number1 = random_int($min, $max);
            $number2 = random_int($min, $max);

            // Garante que number1 seja sempre maior ou igual a number2
            while ($number1 < $number2)
            {
                $number2 = random_int($min, $max);
            }


            $exercise = '';
            $solution = '';

            switch ($operation)
            {
                case 'sum':
                    $exercise = "$number1 + $number2";
                    $solution = $number1 + $number2;
                    break;
                case 'subtraction':
                    $exercise = "$number1 - $number2";
                    $solution = $number1 - $number2;
                    break;
                case 'multiplication':
                    $exercise = "$number1 * $number2";
                    $solution = $number1 * $number2;
                    break;
                case 'division':
                    if ($number2 == 0)
                    {
                        $number2 = 1;
                    }
                    $exercise = "$number1 ÷ $number2";
                    $solution = $number1 / $number2;
                    break;
            }

            $exercises[] = [
                'exercise_number' => $i,
                'exercise' => $exercise,
                'solution' => "$exercise $solution",
            ];
        }
        dd($exercises);
    }

    public function printExcercises()
    {
        echo 'imprimir exercicios no navegador';
    }

    public function exportExcercises()
    {
        echo 'exportar exercicios';
    }
}
