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