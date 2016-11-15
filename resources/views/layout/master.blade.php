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
    <meta name="description" content="DESCRIPTION GOES HERE">

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

    <!-- Styles -->
    <link href="/css/styles.css?v=0.0" rel="stylesheet" type="text/css">
    <link href="/css/bootstrap.vertical-tabs.min.css" rel="stylesheet" type="text/css">
    <style>
        body {
            font-family: 'Lato';
        }
    </style>

    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    @if (!empty($bGuestHome))
    <img src="/img/jumbotron.jpg">
    @endif
    <div id="body">

        <header role="navigation">
            <div class="container">
                <a class="logo" href="/">
                    <img src="/img/logo.png"> &nbsp;
                </a>
                @include('layout.nav')
            </div>
        </header>
        @if ($__env->yieldContent('subnav'))
        @include('layout.subnav')
        @endif

        @if (session()->has('success'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ session('success') }}
        </div>
        @endif
        @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ session('error') }}
        </div>
        @endif

        @if (isset($bGuestHome) && $bGuestHome)
        @yield('content')
        @else
        <div class="container">
            @yield('content')
        </div>
        @endif

    </div>

    <footer>
        <div class="container">
            Copyright &copy; {{ date('Y') }} <b>{{ trans('LaravelTreats::layout.site.name') }}</b>
            <br>
            <a href="/privacy">{{ trans('LaravelTreats::layout.link.privacy') }}</a> |
            <a href="/terms">{{ trans('LaravelTreats::layout.link.terms') }}</a>
        </div>
    </footer>

    @yield('body-append')

    @if (config('laravel-treats.google.analytics-key'))
    @include('LaravelTreats::layout.tracking')
    @endif

</body>
</html>