@extends('layouts.app')

@section('content')

<style>
</style>

  <section class="container text-left" style="width: 60%; margin: 60px auto">




    <h1 class="text-center">My Bill Reminders</h1>


      <div class="panel panel-primary">
        <div class="panel-heading">Edit this instance of: {{ $event->summary }} </div>
        <div class="panel-body text-left">
          <form action="{{ url('/event') }}" method="post" role="form">
            {!! csrf_field() !!}
            <div class="form-group">
              <label for="">Date:</label>
              <input name="date" type="date" class="form-control" placeholder="Date" value=" ">
            </div>
            
            <div class="form-group">
              <label for="">Title:</label>
              <input name="summary" type="text" class="form-control" placeholder="Car Insurance" value="{{ $event->summary }}">
            </div>
            <div class="form-group">
              <label for="">Total Due:</label>
              <input name="total" type="text" class="form-control" placeholder="$100.00" value="{{ $options[0] }}">
            </div>
            <div class="form-group">
              <label for="">Payment Type:</label>
              <input name="type" type="text" class="form-control" placeholder="Credit Card" value="{{ $options[1] }}">
            </div>

            <button type="submit" class="btn btn-block btn-success btn-large">Edit Bill Reminder</button>
          </form>
        </div>
      </div>
  </section>

@endsection
