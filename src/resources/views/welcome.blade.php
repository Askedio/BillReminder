@extends('layouts.app')

@section('content')


<section class="container">
  @if(Session::get('error'))
    <div class="alert alert-danger text-center">  
      <h4>You have been logged out, {{ Session::get('error') }}</h4>
    </div>
  @endif

  <div class="jumbotron">
    <h1>Bill Reminder <sup style="font-size: 20px"> Beta</sup></h1>
    <p>A Google Calendar based Bill Reminder service.</p>
    <hr>

    @if (Auth::guest())
      <p><small>Bill Reminder uses Google Calender to manage your bills as Events. We store a little bit of data in the description and parse it out so you can see what bills are due, when and how much. <a href="{{ url('/auth') }}">Login with Google</a> to get started!</small></p>

      <p>
        <a href="{{ url('/auth') }}"><img class="img-responsive" src="https://camo.githubusercontent.com/8346bc72856242f677bac4bc5cce851bfec4a502/687474703a2f2f692e696d6775722e636f6d2f537a4965316e6f2e706e67"></a>
      </p>

      <p class="text-center">
        <a class="btn btn-warning btn-lg" href="{{ url('/auth') }}"><em class="fa fa-key"></em> Authenticate with Google</a>
      </p>

    @else
      <h2>Welcome back, {{ Auth::user()->name }}!</h2>
      <p><small>
        Start using Google Calendar to remember your bills by clicking <a href="{{ url('/home') }}">My Bill Reminders</a>.<br>
        When you're done, <a href="{{ url('/logout') }}">logout</a>.
      </small></p>
      <a class="btn btn-success btn-lg" href="{{ url('/home') }}"><em class="fa fa-dashboard"></em> My Bill Reminders</a>
      <a class="btn btn-danger btn-lg" href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a>
    @endif
  </div>
  <div class="alert alert-warning text-center"><h4>We save your name, email, avatar, reminder calendar, and authentication token from Google.</h4></div>
</section>
@endsection