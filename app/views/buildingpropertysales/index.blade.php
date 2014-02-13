@extends('layout')

@section('filter')
  <div class="col-md-12">
    {{ Former::inline_open()->action(action('BuildingPropertySalesController@getIndex'))->method('GET') }}
    {{ Former::text('region')->placeholder('Region') }}
    {{ Former::date('sales_date')->placeholder('Date') }}
    {{ Former::submit('Filter')->addClass('btn-primary') }}
    {{ Former::close() }}
  </div>
@stop

@section('content')
  {{ Route::currentRouteName() }}
  <div class="col-md-12">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Region</th> 
          <th>Total Qty</th>
          <th>Total Area</th>
          <th>Sales Qty</th>
          <th>Sales Area</th>
          <th>Sales Average</th>
          <th>House Sales Average</th>
          <th>Sales Date</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($sales as $row)
          <tr>
            <td>{{ $row->region }}</td>
            <td>{{ $row->total_qty }}</td>
            <td>{{ $row->total_area }}</td>
            <td>{{ $row->sales_qty }}</td>
            <td>{{ $row->sales_area }}</td>
            <td>{{ $row->sales_average }}</td>
            <td>{{ $row->house_sales_average }}</td>
            <td>{{ $row->sales_date }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
    <?php echo $sales->links(); ?>
  </div>
@stop
