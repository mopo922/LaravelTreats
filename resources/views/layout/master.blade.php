@extends('LaravelTreats::layout.web')

@section('html-body')
    @if (!empty($guestHome))
    <img src="/img/jumbotron.jpg">
    @endif
    <div id="body">

        <header role="navigation">
            <div class="container">
                <a class="logo" href="/">
                    <img src="/img/logo.png"> &nbsp;
                </a>
                @include('LaravelTreats::layout.nav')
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

        @if (!empty($guestHome))
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
@endsection
