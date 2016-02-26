@extends('layouts.app')

@section('content')
<section class="container text-left" style="width: 60%; margin: 60px auto">
  @if(Session::get('success'))
    <div class="alert alert-success text-center">  
      <h4>Success!</h4>
    </div>
  @endif

  @if(!Auth::user()->calendar)
    <h1>Let's get started!</h1>
    <p>What do you want to call your Bill Reminder calendar?</p>
    <form action="{{ url('/calendar') }}" method="post" role="form">
      {!! csrf_field() !!} 
      <div class="form-group">
        <input name="calendar" type="text" class="form-control input-lg" placeholder="Bill Reminders" autofocus tabindex="1">
      </div>
      <button type="submit" class="btn btn-primary btn-lg btn-block"><em class="fa fa-calendar-plus-o"></em> Create Calendar</button>
    </form>
  @else
    <h1 class="text-center">My Bill Reminders</h1>

    @if(count($events) == 0)
      <div class="alert alert-warning text-center">
        <h4>You have no Bill Reminders. <strong>Create one</strong> to continue.</h4>
      </div>
    @else
      <div class="panel panel-info">
        <div class="panel-heading">{{ count($events) }} Bill Reminders</div>
        <div class="panel-body" style="padding:0">
          @include('partials.list')
        </div>
      </div>

      <div class="text-center" style="margin: 0 0 20px 0">
        <a href="{{ url('/home') }}" class="btn btn-default">1 Month View</a>
        <a href="{{ url('/home?display=2month') }}" class="btn btn-default">2 Month View</a>
        <a href="{{ url('/home?display=2week') }}" class="btn btn-default">Next 2 Weeks</a>
      </div>


      <div class="panel panel-success">
        <div class="panel-heading">Breakdown</div>
        <div class="panel-body" style="padding:0">
          <table class="table table-bordered table-hover table-striped" style="margin:0">
              <thead>
                <tr>
                  <th>Payment Type</th>
                  <th>Total Due</th>
                  <th>Paid</th>
                  <th>Balance</th>
                </tr>
              </thead>
              <tbody>
                @foreach($breakdowns as $break => $down )
                <tr>
                  <td>{{ $break }}</td>
                  <td>${{ number_format($down['total'],2) }}</td>
                  <td>${{ number_format($down['paid'],2) }}</td>
                  <td>${{ number_format($down['total'] - $down['paid'],2) }}</td>
                </tr>
                @endforeach
              </tbody>
          </table>
        </div>
      </div>
    @endif
    @include('partials.create')
  @endif
</section>
@endsection