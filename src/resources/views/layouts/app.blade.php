<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>BillReminder Beta</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

    <style>
      section{margin-top:50px}
      .alert h4{margin-top:10px}
      footer{margin:0 0 50px 0}
    </style>

</head>
<body id="app-layout">

    @yield('content')


    <footer class="section text-center" style="margin-top: 150px">
     &copy; {{{ date('Y') }}} <a href="https://asked.io" target="_new">Asked.io</a> <a href="https://github.com/Askedio"><em class="fa fa-github"></em></a> | <a href="https://github.com/Askedio/BillReminder" target="_blank">Fork this software</a>
    </footer>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
</body>
</html>
