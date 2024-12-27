<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class MainController
 * 
 * Controlador principal para manipulação de exercícios matemáticos.
 * Inclui geração, exibição e exportação de exercícios com base em parâmetros fornecidos.
 * 
 * @author Pierri Alexander Vidmar
 * @since 12/2024
 */
class MainController extends Controller
{
    public function home(): View
    {
        return view('home');
    }

    /**
     * Gera um único exercício matemático com base nos parâmetros fornecidos.
     *
     * @param int $i O índice do exercício atual.
     * @param array $operations Lista de operações possíveis (e.g., 'sum', 'subtraction').
     * @param int $min O valor mínimo permitido para os números.
     * @param int $max O valor máximo permitido para os números.
     *
     * @return array Um array associativo com os dados do exercício:
     *               - 'operation': A operação matemática.
     *               - 'exercise_number': O número do exercício.
     *               - 'exercise': A string do exercício.
     *               - 'solution': A solução do exercício.
     */
    public function generateExcercises(Request $request): View
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
            $exercises[] = $this->generateExercice($i, $operations, $min, $max);
        }

        // colocar os exercícios na sessão para ser baixado
        session(['exercices' => $exercises]);

        return view('operations', ['exercises' => $exercises]);
    }

    /**
     * Exibe os exercícios matemáticos armazenados na sessão.
     * 
     * Este método verifica se os exercícios estão disponíveis na sessão.
     * Se não houver exercícios, redireciona para a página inicial com uma mensagem de erro.
     * Caso contrário, exibe os exercícios e suas soluções em formato HTML.
     * 
     * @return mixed Redireciona para a página inicial com mensagem de erro ou exibe os exercícios no navegador.
     */
    public function printExcercises()
    {
        // Verificar se os exercícios estão na sessão
        if (!session()->has('exercices'))
        {
            return redirect()->route('home')->with('error', 'Não há exercícios disponíveis para impressão.');
        }

        $exercises = session('exercices');

        echo '<pre>';
        echo '<h1>Exercícios de Matemática</h1>';
        echo "Gerado em: " . date('d/m/Y H:i:s') . "\n";
        echo '<hr>';

        // // Imprimir os exercícios
        foreach ($exercises as $exercise)
        {
            echo '<h2><small>Exercício ' . $exercise['exercise_number'] . ': </small>' . $exercise['exercise'] . '</h2>';
        }

        echo '<hr>';
        echo "<small>Soluções</small><br>";
        foreach ($exercises as $exercise)
        {
            echo '<small>Exercício ' . $exercise['exercise_number'] . ': ' . $exercise['solution'] . '</small><br>';
        }
    }

    /**
     * Exporta os exercícios armazenados na sessão para um arquivo de texto.
     * Caso não haja exercícios na sessão, redireciona para a página inicial com uma mensagem de erro.
     *
     * @return \Illuminate\Http\Response A resposta contendo o arquivo para download.
     */
    public function exportExcercises()
    {
        // Verificar se os exercícios estão na sessão
        if (!session()->has('exercices'))
        {
            return redirect()->route('home')->with('error', 'Não há exercícios disponíveis para impressão.');
        }

        // Cria o arquivo para download com o conteúdo dos exercícios
        $exercises = session('exercices');

        $filename = 'exercises_' . env('APP_NAME') . '_' . date('YmdHis') . '.txt';

        $content = '';
        foreach ($exercises as $exercise)
        {
            $content .= $exercise['exercise_number'] . ': ' . $exercise['exercise'] . "\n";
        }

        // Incluir as soluções no arquivo
        $content .= "\n";
        $content .= "Soluções:\n" . str_repeat('-', 20) . "\n";
        foreach ($exercises as $exercise)
        {
            $content .= $exercise['exercise_number'] . ': ' . $exercise['solution'] . "\n";
        }

        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename=' . $filename);
    }

    /**
     * Gera um único exercício matemático com base nos parâmetros fornecidos.
     *
     * @param int $i O índice do exercício atual.
     * @param array $operations Lista de operações possíveis (e.g., 'sum', 'subtraction').
     * @param int $min O valor mínimo permitido para os números.
     * @param int $max O valor máximo permitido para os números.
     *
     * @return array Um array associativo com os dados do exercício:
     *               - 'operation': A operação matemática.
     *               - 'exercise_number': O número do exercício.
     *               - 'exercise': A string do exercício.
     *               - 'solution': A solução do exercício.
     */
    private function generateExercice($i, $operations, $min, $max): array
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
                $exercise = "$number1 + $number2 = ";
                $solution = $number1 + $number2;
                break;
            case 'subtraction':
                $exercise = "$number1 - $number2 = ";
                $solution = $number1 - $number2;
                break;
            case 'multiplication':
                $exercise = "$number1 x $number2 = ";
                $solution = $number1 * $number2;
                break;
            case 'division':
                if ($number2 == 0)
                {
                    $number2 = 1;
                }
                $exercise = "$number1 ÷ $number2 = ";
                $solution = $number1 / $number2;
                break;
        }

        return [
            'operation' => $operation,
            'exercise_number' => str_pad($i + 1, 2, '0', STR_PAD_LEFT),
            'exercise' => $exercise,
            'solution' => "$exercise $solution",
        ];
    }
}
