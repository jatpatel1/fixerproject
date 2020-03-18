<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- Scripts -->
    @stack('head')

</head>
<body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">DEPT</a>

    </div>
    <div id="navbar" class="collapse navbar-collapse">
      <ul class="nav navbar-nav">
        <li class="active"><a href="#">Home</a></li>
    </ul>
</div><!--/.nav-collapse -->
</div>
</nav>

<div class="container" id="app">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="starter-template">
        <h1 class="cover-heading">Birthday Exchange Rate</h1>
        <p class="lead">Find out what the exchange rate was from EUR to GBP on your previous birthday.</p>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Give it a go:</h3>
            </div>
            <div class="panel-body">
                <form class="form-inline" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="txtName">Name</label>
                        <input type="text" class="form-control" id="txtName" name="txtName" placeholder="Your Name">
                    </div>
                    <div class="form-group">
                        <label for="txtBirthDay">Birth Day</label>
                        <select class="form-control" name="txtBirthDay" id="txtBirthDay">
                            @foreach($days as $day)
                            <option value="{{ $day }}">{{ $day }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="txtBirthMonth">Birth Month</label>
                        <select class="form-control" name="txtBirthMonth" id="txtBirthMonth">
                            @foreach($months as $monthKey => $month)
                            <option value="{{ $monthKey }}">{{ $month }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>

            </div>
        </div>

        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Previous Searches</h3>
        </div>
        <div class="panel-body">
            @if (count($results))
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Birthday</th>
                        <th>Rate</th>
                        <th>Same Birthdays</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $result)
                    <tr>
                        <td>{{ $result->name }}</td>
                        <td>{{ $result->birthday->format('jS F Y')}}</td>
                        <td>{{ number_format($result->rate, 2, '.', ',') }}</td>
                        <td><span class="badge">{{ $result->groupCount }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        Currently there are no searches, be the first?
        @endif
    </div>
</div>
</div>

</div><!-- /.container -->
</body>
</html>
<script src="{{ asset('js/app.js')}}"></script>

