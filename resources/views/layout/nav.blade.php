    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#laravel-treats-navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
    <div class="collapse navbar-collapse" id="laravel-treats-navbar-collapse">
        <nav>
            <ul class="nav navbar-nav navbar-right">

                @if (Auth::check())
                <li class="dropdown">
                    <a id="laravel-treats-nav" href="#" class="dropdown-toggle" data-toggle="dropdown">
                        {{ $navDropdownTitle ?? trans('LaravelTreats::layout.nav.dropdown-label') }}
                        @if (Auth::user()->firstname)
                        ({{ Auth::user()->firstname }})
                        @endif
                        <b class="caret"></b>
                    </a>
                    <ul id="laravel-treats-nav-menu" class="dropdown-menu" role="menu">
                        @include('LaravelTreats::layout.nav-links')
                    </ul>
                </li>
                @else
                <br>
                <form method="POST" action="{{ trans('LaravelTreats::layout.form.login.action') }}" class="form-inline" role="form">
                    {!! csrf_field() !!}
                    @if (isset($errors) && !$errors->isEmpty() && old('remember'))
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif
                    <input type="hidden" name="{{ trans('LaravelTreats::layout.form.login.remember.field') }}" value="1">
                    <input type="email" name="{{ trans('LaravelTreats::layout.form.login.email.field') }}" placeholder="{{ trans('LaravelTreats::layout.form.login.email.label') }}" class="form-control">
                    <input type="password" name="{{ trans('LaravelTreats::layout.form.login.password.field') }}" placeholder="{{ trans('LaravelTreats::layout.form.login.password.label') }}" class="form-control">
                    <input type="submit" class="btn btn-info" value="{{ trans('LaravelTreats::layout.form.login.submit.label') }}">
                    <br>
                    <a class="pull-right" href="{{ trans('LaravelTreats::layout.link.forgot-password.url') }}">
                        {{ trans('LaravelTreats::layout.link.forgot-password.label') }}
                    </a>
                </form>
                @endif

            </ul>
        </nav>
    </div>
