<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainnav" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainnav">
        <a class="navbar-brand" href="{{ URL('/') }}">EzConference</a>
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            {{--If not logged in--}}
            @if (Auth::guest())
                <li class="nav-item {{ \Request::is('/') ? 'active' : '' }}"><a class="nav-link" href="{{ URL('/') }}">Home</a></li>
            {{--If logged in--}}
            @else
                <li class="nav-item {{ \Request::is('dashboard') ? 'active' : '' }}"><a class="nav-link" href="{{ URL('/dashboard') }}">Dashboard</a></li>
            @endif
        </ul>
        <div class="navbar-nav mr-0 my-2 my-lg-0">
            {{--If not logged in--}}
            @if (Auth::guest())
                <li class="nav-item {{ \Request::is('login') ? 'active' : '' }}"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                <li class="nav-item {{ \Request::is('register') ? 'active' : '' }}"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
            {{--If logged in--}}
            @else
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" id="userDropdown">
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="{{ URL('/dashboard/profile') }}"><span class="oi oi-person"></span> Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                            <span class="oi oi-account-logout"></span> Logout
                        </a>

                        {{--Hidden logout action--}}
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </li>
            @endif
        </div>
    </div>
</nav>