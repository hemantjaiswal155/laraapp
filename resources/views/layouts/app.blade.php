<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sweetalert.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <!-- JS -->
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>


</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>
                                        <a href="{{ route('user.edit', Auth::user()->id) }}">Edit profile</a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
        @yield('content')
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/parsley.js') }}"></script>
    <script src="{{ asset('js/plugins.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            //Get state based on country id
            $('#country_id').on('change',function(){
                var countryID = $(this).val();
                if(countryID){

                    var url = "{{ URL::to('get-states') }}";
                    var csrfToken = '{{ csrf_token() }}';
                    var allStates = '<option value="">Select state</option>';

                    $.ajax({
                        type:'POST',
                        url:url,
                        data: {_token:csrfToken, country_id:countryID},
                        success:function(data){
                            if(data.status) {
                                var states = data.data;
                                for (var id in states) {
                                    allStates += '<option value="' + id + '">' + states[id] + '</option>';
                                }
                            }
                            $('#state_id').html(allStates);
                        }
                    });
                }else{
                    $('#state_id').html('<option value="">Select state</option>');
                }
            });

            toastr.options = {
                "timeOut": 10000
            }

            @if(Session::has('success'))
                var msg = "{{ Session::get('success') }}";
                toastr.success(msg, 'Success');
            @endif

            @if(Session::has('failure'))
                var msg = "{{ Session::get('failure') }}";
                toastr.error(msg, 'Failed');
            @endif

            @if(Session::has('errors'))
                var validationError = '{{ config('app.messages.form_errors')  }}';
                toastr.error(validationError, 'Failed');
            @endif

        });
    </script>
    @yield('javascript')
</body>
</html>
