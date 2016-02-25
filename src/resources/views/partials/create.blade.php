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
          <option>Monthly
          <option>Weekly
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