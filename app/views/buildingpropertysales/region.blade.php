@extends('layout')

@section('content')
  <!-- Summary panel -->
  <div class="row content region-view">
    <div class="col-md-6">
      <div class="panel panel-default panel-summary">
        <div class="panel-heading"><h4>统计 <span class="label label-default">{{ $region }}</span></h4></div>
        <div class="panel-body">

          <ul class="list-group">
            <li class="field list-group-item">
              <span class="badge">{{ $regionTotal->totalQty }}套</span>
              <span class="title">总存量</span> <span class="label label-primary">{{ round($regionTotal->totalQty / $total->totalQty * 100, 2) }}%</span> * <span class="label label-default">{{ $total->totalQty }}套</span>
            </li>

            <li class="field list-group-item">
              <span class="badge">{{ $regionTotal->totalArea }}平米</span>
              <span class="title">总面积</span> <span class="label label-primary">{{ round($regionTotal->totalArea / $total->totalArea * 100, 2) }}%</span> * <span class="label label-default">{{ $total->totalArea }}平米</span>
            </li>

            <li class="field list-group-item">
              <span class="badge">{{ $regionTotal->totalSalesQty }}套</span>
              <span class="title">总销售数量</span> <span class="label label-primary">{{ round($regionTotal->totalSalesQty / $total->totalSalesQty * 100, 2) }}%</span> * <span class="label label-default">{{ $total->totalSalesQty }}套</span>
            </li>

            <li class="field list-group-item">
              <span class="badge">{{ round($regionTotal->totalSalesArea, 2) }}平米</span>
              <span class="title">总销售面积</span> <span class="label label-primary">{{ round($regionTotal->totalSalesArea / $total->totalSalesArea * 100, 2) }}%</span> * <span class="label label-default">{{ round($total->totalSalesArea, 2) }}平米</span>
            </li>

            <li class="field list-group-item">
              <span class="badge">{{ round($regionTotal->totalSalesAvg, 2) }}平米/元</span>
              <span class="title">销售均价</span> <span class="label label-primary">{{ round($regionTotal->totalSalesAvg / $total->totalSalesAvg * 100, 2) }}%</span> * <span class="label label-default">{{ round($total->totalSalesAvg, 2) }}平米/元</span>
            </li>
          </ul>

        </div>
      </div>

      <!-- Property panel -->
    </div>

    <div class="col-md-12">
      <div class="panel panel-default panel-property">
        <div class="panel-heading">地区楼盘</div>
        <div class="panel-body">
          
        </div>
      </div>
    </div>
  </div>
@stop
