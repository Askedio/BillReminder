@extends('layouts.app')

@section('content')

@if(!Auth::user()->calendar)
  <section class="container text-center" style="width: 60%; margin: 60px auto">
    <h1>Let's get started!</h1>
    <p>What do you want to call your Bill Reminder calendar?</p>
    <form action="{{ url('/calendar') }}" method="post" role="form">
      {!! csrf_field() !!} 
      <div class="form-group">
        <input name="calendar" type="text" class="form-control input-lg" placeholder="Bill Reminders" autofocus tabindex="1">
      </div>
      <button type="submit" class="btn btn-primary btn-lg btn-block"><em class="fa fa-calendar-plus-o"></em> Create Calendar</button>
    </form>
  </section>
@else
  <section class="container text-center" style="width: 60%; margin: 60px auto">
    <h1>My Bill Reminders</h1>

    @if(count($events) == 0)
      <div class="alert alert-warning text-center">
        <h4>You have no Bill Reminders. <a href="">Create one</a> to continue.</h4>
      </div>
    @else
      <table class="table table-bordered">
          <thead>
            <tr>
              <th>Date</th>
              <th>Bill</th>
              <th>Total Due</th>
              <th>Payment Type</th>
              <th>Paid</th>
            </tr>
          </thead>
          <tbody>
            @foreach($events as $event)
            <tr>
              <td>{{ $event['date'] }}
              <br><small>{{ $event['date']->diffForHumans() }}</small></td>
              <td>{{ $event['summary'] }}</td>
              <td>${{ $event['total'] }}</td>
              <td>{{ $event['payment_type'] }}</td>
              <td>
                <em class="fa @if($event['paid'])
fa-check text-success
                @else
fa-close text-danger
                @endif
                ">
                </td>
            </tr>
          @endforeach
        </tbody>
      </table>
      
      <div class="alert alert-danger text-center" style="margin-bottom:0px;">
        <h4>You owe a total of: ${{ $total }} </h4>
      </div>

      <div class="alert alert-success text-center" style="margin-bottom:0px;">
        <h4>You paid a total of: ${{ $paid }} </h4>
      </div>

      <div class="alert alert-info text-center">
        <h4>You still owe: ${{ number_format($total - $paid,2)}} </h4>
      </div>
      

    @endif

    <a class="btn btn-danger btn-lg" href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a>
  </section>


@endif

@endsection
