@extends('layout')

@section('filter')
  <div class="col-md-12">
    {{ Former::inline_open()->action(action('BuildingPropertySalesController@getIndex'))->method('GET') }}
    {{ Former::text('region')->placeholder('Region') }}
    {{ Former::text('sales_date')->placeholder('Date YYYY-mm-dd...') }}
    {{ Former::submit('Filter')->addClass('btn-primary') }}
    {{ Former::close() }}
  </div>
@stop

@section('content')

  <div class="row region-index">
    {{ Route::currentRouteName() }}

    <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading">当日概览<span class="label label-default">{{ $date }}</span></div> 
        <div class="panel-body">
          <ul class="list-group">
            <li class="field list-group-item">
              <span class="badge">{{ $summary->totalQty }} 套</span>
              总存量
            </li>
            <li class="field list-group-item">
              <span class="badge">{{ round($summary->totalArea, 2) }} 平米</span>
              总面积
            </li>
            <li class="field list-group-item">
              <span class="badge">{{ $summary->totalSalesQty }} 套</span>
              总销售数量
            </li>
            <li class="field list-group-item">
              <span class="badge">{{ round($summary->totalSalesArea, 2) }} 平米</span>
              总销售面积
            </li>
            <li class="field list-group-item">
              <span class="badge">{{ round($summary->totalSalesAvg, 2) }} 元/平</span>
              销售均价
            </li>
          </ul>
        </div>
      </div>
    </div> 

    <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading">各区销售数量<span class="label label-default">{{ $date }}</span></div> 
        <div class="panel-body">
          {{ Lava::PieChart('RegionSalesQty')->outputInto('region_sales_qty') }}
          {{ Lava::div() }}

          @if (Lava::hasErrors())
            {{ Lava::getErrors() }}
          @endif
        </div>
      </div>
    </div>

    <div class="col-md-12">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>区域</th> 
            <th class="num">总存量</th>
            <th class="num">总面积</th>
            <th class="num">销售数量</th>
            <th class="num">销售面积</th>
            <th class="num">销售均价</th>
            <th class="num">住宅销售均价</th>
            <th>销售日期</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($sales as $row)
            <tr>
              <td>{{ link_to_route('bp_region', $row->region, array($row->region)) }}</td>
              <td class="num">{{ $row->total_qty }} 套</td>
              <td class="num">{{ $row->total_area }} 平米</td>
              <td class="num">{{ $row->sales_qty }} 套</td>
              <td class="num">{{ $row->sales_area }} 平米</td>
              <td class="num">{{ $row->sales_average }} 元/平</td>
              <td class="num">{{ $row->house_sales_average }} 元/平</td>
              <td>{{ $row->sales_date }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

  </div>

  <div class="row paginate">
    <div class="col-md-3"></div> 
    <div class="col-md-9 clearfix">
      {{ $sales->links() }}
    </div>
  </div> 

@stop

@section('footer')

  <script type="text/javascript">
   require(['jquery', 'bootstrap-datepicker'], function($) {
     $('#sales_date').datepicker({
       format: 'yyyy-mm-dd',
     });
   });
  </script>

@stop
