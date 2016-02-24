@extends('layouts.app')

@section('content')

<style>
</style>

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
    <div class="panel panel-info ">
      <div class="panel-heading">{{ count($events) }} Bill Reminders</div>
      <div class="panel-body" style="padding:0">

    <table class="table table-bordered table-hover table-striped" style="margin:0">
        <thead>
          <tr>
            <th class="text-center"><em class="fa fa-cog"></em></th>
            <th>Date</th>
            <th>Bill</th>
            <th>Payment Type</th>
            <th>Total Due</th>
            <th>Paid</th>
          </tr>
        </thead>
        <tbody>
          @foreach($events as $event)
          <tr>
            <td class="text-center">
              <a href="{{ url('/event/'. $event['id'] ) }}" class="hidden"><em class="fa fa-pencil"></em></a>
              <a href="{{ url('/event/delete/'. $event['rec_id'] ) }}" class="text-danger"><em class="fa fa-trash"></em></a>
            </td>
            <td>
            
            {{ $event['date']->toDateString() }}
            @if(!$event['paid'])
              <div class="pull-right label 
     
              @if(preg_match('/ago/i', $event['date']->diffForHumans()))
                label-danger
              @else
              
              label-success
              @endif 
              
              "><small>{{ $event['date']->diffForHumans() }}</small></div>
            @endif
            </td>
            <td>{{ $event['summary'] }}</td>
            <td>{{ $event['payment_type'] }}</td>
            <td>${{ number_format($event['total'],2) }}</td>
            <td class="text-center">
              <a href="{{ url('/event/'.($event['paid'] ? 'unpaid' : 'paid' ).'/' . $event['id']) }}"><em class="fa @if($event['paid'])
                fa-check text-success
              @else
                fa-close text-danger
              @endif
              "></em></a>
              </td>
          </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr style="border:0">
           <td colspan="4" class="" style="border:0"></td>
           <td class="bg-danger" style="border:0">${{ number_format($total,2) }}</td>
           <td class="bg-success" style="border:0">${{ number_format($paid,2) }}</td>
        </tr>
        <tr style="border:0">
           <td colspan="4" class=" text-right" style="border:0"><strong>You still owe:</strong></td>
           <td colspan="2" class="bg-info" style="border:0;border-top:1px solid #ccc"><strong>${{ number_format($total - $paid,2)}}</strong></td>
        </tr>
      </tfoot>
    </table>

    </div>
    </div>
  @endif

  <div class="panel panel-primary">
    <div class="panel-heading">Create A Bill Reminder</div>
    <div class="panel-body text-left">
      <form action="{{ url('/event') }}" method="post" role="form">
        {!! csrf_field() !!}

        <div class="form-group">
          <label for="">Date:</label>
          <input name="date" type="date" class="form-control" placeholder="Date" required>
        </div>

        <div class="form-group">
          <label for="">Title:</label>
          <input name="summary" type="text" class="form-control" placeholder="Car Insurance" required>
        </div>

        <div class="form-group">
          <label for="">Total Due:</label>
          <input name="total" type="text" class="form-control" placeholder="$100.00" required>
        </div>

        <div class="form-group">
          <label for="">Payment Type:</label>
          <input name="type" type="text" class="form-control" placeholder="Credit Card">
        </div>
        
        <div class="form-group">
          <label for="">Repeat:</label>
          <select name="repeat" class="form-control">
            <option>Once
            <option>Weekly
            <option>Bi-weekly
            <option>Monthly
          </select>
        </div>
        
        <div class="form-group">
          <label for="">Email Reminder</label>
          <input name="reminder_email" type="number" class="form-control" value="1440">
        </div>

        <div class="form-group">
          <label for="">Popup Reminder</label>
          <input name="reminder_popup" type="number" class="form-control" value="60">
        </div>
        
        <button type="submit" class="btn btn-block btn-success btn-lg">Add New Bill Reminder</button>

      </form>
    </div>
  </div>
@endif
</section>

@endsection
