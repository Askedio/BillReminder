@extends('layouts.app')

@section('content')


<section class="container">
  @if(Session::get('error'))
    <div class="alert alert-danger text-center">  
      <h4>You have been logged out, {{ Session::get('error') }}</h4>
    </div>
  @endif

  <div class="jumbotron">
    <h1>BillReminder <sup style="font-size: 20px"> Beta</sup></h1>
    <p>A Google Calendar based Bill Reminder service.</p>
    <hr>

    @if (Auth::guest())
      <p><small>You will need to allow <strong>BillReminder Beta</strong> access to your Google Calendar in order for it to manage your calendars events. Click the button below to grant access, create an account here and login.</small></p>
      <a class="btn btn-warning btn-lg" href="{{ url('/auth') }}"><em class="fa fa-key"></em> Authenticate with Google</a>

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
  <div class="alert alert-info text-center"><h4>BillReminder is a free service and is using a free Google API key that has limits!</h4></div>
</section>
@endsection