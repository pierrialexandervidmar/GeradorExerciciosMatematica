<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }}</title>
    <!-- favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/png">
    <!-- bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/bootstrap.min.css') }}">
    <!-- main css -->
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
</head>

<body>

    <!-- logo -->
    <div class="text-center my-3">
        <img src="{{ asset('assets/images/logo.png') }}"alt="logo" class="img-fluid" width="250px">
    </div>

    <!-- operations -->
    <div class="container">

        <hr>

        <div class="row">

            <!-- each operation -->
            
            @foreach ($exercises as $exercise)
                <div class="col-3 display-6 mb-3">
                    <span class="badge bg-dark">{{ str_pad($exercise['exercise_number'], 2, '0', STR_PAD_LEFT) }}</span>
                    <span>{{ $exercise['exercise'] }}</span>
                </div>
            @endforeach

        </div>

        <hr>

    </div>

    <!-- print version -->
    <div class="container mt-5">
        <div class="row">
            <div class="col">
                <a href="{{ route('home') }}" class="btn btn-primary px-5">Voltar</a>
            </div>
            <div class="col text-end">
                <a href="{{ route('exportExcercises') }}" class="btn btn-secondary px-5">Baixar Exercícios</a>
                <a href="{{ route('printExcercises') }}" class="btn btn-secondary px-5">Imprimir Exercícios</a>
            </div>
        </div>
    </div>

    <!-- footer -->
    <footer class="text-center mt-5">
        <p class="text-secondary">Gerador de Exercícios de Matemática - Pype Math &copy; <span class="text-info">{{ date('Y') }}</span></p>
    </footer>

    <!-- bootstrap -->
    <script src="{{ asset('assets/bootstrap/bootstrap.bundle.min.js') }}"></script>
</body>

</html>