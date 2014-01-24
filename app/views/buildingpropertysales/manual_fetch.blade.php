@extends('layout')

@section('content')
  <div class="col-md-8" id="manual-fetch-form">
    {{ Former::open()->action(action('BuildingPropertySalesController@postManualFetch')) }}
    {{ Former::text('url')->require()->placeholder('Please input the URL') }}
    {{ Former::text('date')->require()->placeholder('YYYY-MM-DD')->value(date('Y-m-d')) }}
    {{ Former::text('charset')->require()->placeholder('UTF-8,GB2312,GBK,etc')->value('gb2312') }}
    {{ Former::actions(Former::submit('Submit')
                       ->dataLoadingText('Fetching...')
                       ->addClass('btn-primary')) }}
    {{ Former::close() }}
  </div>
@stop
