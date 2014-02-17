<!DOCTYPE html>
<html>
  <head>
    <title>Building Property Analysis</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap-theme.min.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    {{ HTML::style('css/styles.css') }}
    {{ HTML::style('require.css') }}

    {{ HTML::script('require.js') }}

    <script type="text/javascript">
     var public_path = '{{ asset('') }}';
    </script>

    {{ HTML::script('require.config.js') }}

    @yield('head')
  </head>
  <body>
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Building Property Analysis</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="{{ Request::is('/') ? 'active' : '' }}">{{ link_to_action('HomeController@index', 'Home') }}</li>
            <li class="{{ Request::is('history-url*') ? 'active' : '' }}">{{ link_to_action('HistoryUrlController@getIndex', 'Urls') }}</li>
            <li class="{{ Request::is('bp*') ? 'active' : '' }}">{{ link_to_action('BuildingPropertySalesController@getIndex', 'Sales') }}</li>
            <li class="{{ Request::is('daily-info*') ? 'active' : '' }}">{{ link_to_action('DailyInfoController@getIndex', 'Daily') }}</li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div class="container">

      <div class="row message">
        @if (Session::has('flash_messages'))
          @foreach (Session::get('flash_messages') as $flash_message)
            <div class="alert alert-{{ $flash_message->status }}">{{ $flash_message->message }}</div>
          @endforeach
        @endif
      </div>

      <div class="row action-bar">
        @yield('actionbar')
      </div>
      
      <div class="row filter">
        @yield('filter')
      </div>
      
      @yield('content')
    </div>

    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <!-- Latest compiled and minified JavaScript -->
    <!-- <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script> -->
    @yield('footer')
  </body>
</html>
