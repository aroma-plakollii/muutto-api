<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <style>
        .checkmark-wrapper {
            border-radius: 200px;
            height: 200px;
            background: #e9ece3;
            margin: 0 auto;
            width: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .checkmark-wrapper .checkmark {
            font-size: 84px;
            color: #43b443;
        }

        .card {
            max-width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body class="font-sans antialiased">
<div class="container">
    @if(Request::get('AUTHCODE') )
        @if(Request::get('RETURN_CODE') == 0)
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="card mt-5 p-5 d-flex align-items-center justify-content-center">
                        <div class="checkmark-wrapper">
                            <i class="checkmark">✓</i>
                        </div>
                        <h1 class='success mt-5'>Menestys</h1>
                        <p class='success'>Saimme maksusi.<br/> Kiitos, että valitsit meidät</p>

                        <a class='mt-5 text-muted' href="https://muuttotarjous.fi">Mene kotisivulle</a>
                    </div>
                </div>
            </div>
{{--            <script>--}}
{{--                const data = { booking_number: '<?php echo Request::get('ORDER_NUMBER'); ?> ' };--}}

{{--                fetch('<?php echo url(''); ?>/api/mb-bookings-update-payment', {--}}
{{--                    method: 'POST', // or 'PUT'--}}
{{--                    headers: {--}}
{{--                        'Content-Type': 'application/json',--}}
{{--                    },--}}
{{--                    body: JSON.stringify(data),--}}
{{--                })--}}
{{--                    .then((response) => response.json())--}}
{{--                    .then((data) => {--}}
{{--                        console.log('Success:', data);--}}
{{--                    })--}}
{{--                    .catch((error) => {--}}
{{--                        console.error('Error:', error);--}}
{{--                    });--}}
{{--            </script>--}}
        @endif
    @endif
</div>
</body>
</html>


