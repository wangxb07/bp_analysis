@extends('layout')

@section('content')
  <!-- Summary panel -->
  <div class="row content region-view">
    <div class="col-md-6">
      <div class="panel panel-default panel-total">
        <div class="panel-heading"><h4>图表 <span class="label label-default">{{ $region }}</span></h4></div>
        <div class="panel-body">

        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="panel panel-default panel-summary">
        <div class="panel-heading"><h4>统计 <span class="label label-default">{{ $region }}</span></h4></div>
        <div class="panel-body">

          <ul class="list-group">
            <li class="field list-group-item">
              <span class="badge">{{ $regionTotal->totalQty }} 套</span>
              <span class="title">总存量</span> <span class="label label-primary">{{ round($regionTotal->totalQty / $total->totalQty * 100, 2) }}%</span> * <span class="label label-default">{{ $total->totalQty }} 套</span>
            </li>

            <li class="field list-group-item">
              <span class="badge">{{ $regionTotal->totalArea }} 平米</span>
              <span class="title">总面积</span> <span class="label label-primary">{{ round($regionTotal->totalArea / $total->totalArea * 100, 2) }}%</span> * <span class="label label-default">{{ $total->totalArea }} 平米</span>
            </li>

            <li class="field list-group-item">
              <span class="badge">{{ $regionTotal->totalSalesQty }} 套</span>
              <span class="title">总销售数量</span> <span class="label label-primary">{{ round($regionTotal->totalSalesQty / $total->totalSalesQty * 100, 2) }}%</span> * <span class="label label-default">{{ $total->totalSalesQty }} 套</span>
            </li>

            <li class="field list-group-item">
              <span class="badge">{{ round($regionTotal->totalSalesArea, 2) }} 平米</span>
              <span class="title">总销售面积</span> <span class="label label-primary">{{ round($regionTotal->totalSalesArea / $total->totalSalesArea * 100, 2) }}%</span> * <span class="label label-default">{{ round($total->totalSalesArea, 2) }} 平米</span>
            </li>

            <li class="field list-group-item">
              <span class="badge">{{ round($regionTotal->totalSalesAvg, 2) }} 平米/元</span>
              <span class="title">销售均价</span> <span class="label label-primary">{{ round($regionTotal->totalSalesAvg / $total->totalSalesAvg * 100, 2) }}%</span> * <span class="label label-default">{{ round($total->totalSalesAvg, 2) }} 平米/元</span>
            </li>
          </ul>

        </div>
      </div>

      <!-- Property panel -->
    </div>

    <div class="col-md-12">
      <div class="panel panel-default panel-property">
        <div class="panel-heading"><h4>地区楼盘 <span class="label label-default">{{ $region }}</span></h4></div>
        <div class="panel-body">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>楼盘名称</th>
                <th>销售套数</th>
                <th>均价</th>
              </tr>
            </thead>

            <tbody>
              @foreach ($properties as $property) 
                <tr>
                  <td>{{ $property->name }}</td>
                  <td>{{ $property->total_qty }} 套</td>
                  <td>{{ round($property->price_avg, 2) }} 平米/元</td>
                </tr>
              @endforeach
            </tbody>
          </table>

        </div>
      </div>
    </div>
  </div>
@stop

@section('footer')

  <script type="text/javascript" >

   require(["jquery"], function($) {
     // hide more rows
     var hideMoreRows = function() {
       $('.panel-property table tr').each(function(i, e) {
         if (i > 10) {
           $(e).hide();
         }
       });
     };

     var bt = $('<button></button>');
     bt.attr('type', 'button');
     bt.addClass('btn');
     bt.addClass('btn-default');
     bt.addClass('btn-lg');
     bt.html('更多<span class="glyphicon glyphicon-chevron-down"></span>');

     bt.click(function() {
       var glyphicon = $(this).find('.glyphicon');

       if (glyphicon.hasClass('glyphicon-chevron-down')) {
         $('.panel-property table tr').show();
         glyphicon.removeClass('glyphicon-chevron-down');
         glyphicon.addClass('glyphicon-chevron-up');
       }
       else {
         hideMoreRows();
         glyphicon.removeClass('glyphicon-chevron-up');
         glyphicon.addClass('glyphicon-chevron-down');
       }
     });

     if ($('.panel-property table tr').length > 11) {
       hideMoreRows();
       $('.panel-property .panel-body').append(bt);
     }
   });

  </script>

@stop
