<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        {{ trans('LaravelTreats::layout.site.name') }}
        @if ('production' !== App::environment())
        ({{ App::environment() }})
        @endif
    </title>
    <meta name="description" content="{{ trans('LaravelTreats::layout.site.description') }}">

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

    <!-- Styles -->
    <link href="/css/styles.css?v=0.0" rel="stylesheet" type="text/css">
    <style>
        body {
            font-family: 'Lato';
        }
    </style>
    @yield('styles')

    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

    @yield('html-body')

</body>
</html>