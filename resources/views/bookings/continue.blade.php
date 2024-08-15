<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Continue Booking</title>
    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>

    <!-- Load React. -->
    <!-- Note: when deploying, replace "development.js" with "production.min.js". -->
    <script src="https://unpkg.com/react@18/umd/react.development.js" crossorigin></script>
    <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js" crossorigin></script>

</head>
<body>

<div class="container py-3">
    <header>
        <div class="pricing-header p-3 pb-md-4 mx-auto text-center">
            <h1 class="display-4 fw-normal">Valitse päättymispäivä</h1>
        </div>
    </header>

    <main>
        <div class="row mb-3">
            <div class="col-4"></div>
            <div class="col-4">
                <p class="fs-5 text-muted">Päättymispäiväsi on: <b>15.06.2023</b></p>
                <form>
                    <div class="mb-3">
                        <input type="date" class="form-control" placeholder="12.01.2022">

                        <p class="mt-3 mb-4">Kokonaismäärä <b>100 &euro;</b></p>
                    </div>
                    <button type="button" class="btn btn-lg btn-primary btn btn-block">Suorita Maksu</button>
                </form>
            </div>
            <div class="col-4"></div>
        </div>
    </main>

    <footer class="pt-4 border-top fixed-bottom">
        <div class="row text-center">
            <div class="col-12 col-md">
                <small class="d-block mb-3 text-muted">All Rights Reserved © {{date('Y')}} Muttotarjous</small>
            </div>
        </div>
    </footer>
</div>

{{--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>--}}
<!-- Load our React component. -->
<script src="../../js/continue.js"></script>
</body>

</html>
