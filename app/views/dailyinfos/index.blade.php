@extends('layout')

@section('filter')

  <div class="col-md-12">
    {{ Former::inline_open()->action(action('DailyInfoController@getIndex'))->method('GET') }}
    {{ Former::text('name') }}
    {{ Former::select('type')->fromQuery(BuildingSalesDaily::select(DB::raw('type'))
                                         ->groupBy('type')
                                         ->orderBy('type')
                                         ->get(), 'type', 'type')->addClass('form-control') }}

    {{ Former::submit('Filter')->addClass('btn-primary') }}
    {{ Former::close() }}
  </div>

@stop

@section('content')
  <div class="col-md-12">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Name</th>      
          <th>Region</th>      
          <th>QTY</th>      
          <th>Price Average</th>      
          <th>Area Average</th>      
          <th>Type</th>      
          <th>Area</th>      
          <th>Sales Date</th>      
        </tr>
      </thead>
      <tbody>
        @foreach ($rows as $row)
          <tr>
            <td>{{ $row->name }}</td>
            <td>{{ $row->region }}</td>
            <td>{{ $row->qty }}</td>
            <td>{{ $row->price_average }}</td>
            <td>{{ $row->area_average }}</td>
            <td>{{ $row->type }}</td>
            <td>{{ $row->area }}</td>
            <td>{{ $row->sales_date }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <?php echo $rows->links(); ?>
  </div>

@stop
