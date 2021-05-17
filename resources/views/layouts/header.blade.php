<link rel="stylesheet" href="{{ asset('resources/css/header.css') }}">
<nav class="nav">
    <div class="nav__item item">
        @if (Route::current()->uri == "/")
            <a class="nav__a active_input" href="{{url("/").addB24Auth()}}">Главная</a>
        @else
            <a class="nav__a " href="{{url("/").addB24Auth()}}">Главная</a>
        @endif
    </div>
    <div class="nav__item">
        @if (Route::current()->uri == "task/getPlan")
            <a class="nav__a active_input" href="{{url("/task/getPlan/").addB24Auth()}}">Посмотреть план</a>
        @else
            <a class="nav__a " href="{{url("/task/getPlan/").addB24Auth()}}">Посмотреть план</a>
        @endif
    </div>
    {{-- <div class="nav__item">
        @if (Route::current()->uri == "task/reports")
            <a class="nav__a active_input" href="{{url("/task/reports/").addB24Auth()}}">Отчет </a>
        @else
            <a class="nav__a " href="{{url("/task/reports/").addB24Auth()}}">Отчет</a>
        @endif
    </div> --}}
    <div class="nav__item">
        @if (Route::current()->uri == "task/reportsDays")
            <a class="nav__a active_input" href="{{url("/task/reportsDays/").addB24Auth()}}">Отчет за день</a>
        @else
            <a class="nav__a " href="{{url("/task/reportsDays/").addB24Auth()}}">Отчет за день</a>
        @endif
    </div>

</nav>
