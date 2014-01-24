@extends('layout')

@section('content')
  <div class="col-md-8" id="manual-fetch-form">
    {{ Former::open() }}
    {{ Former::text('url')->requried()->description('Seed page url') }}
    {{ Former::actions(Former::submit('Submit')
                       ->dataLoadingText('Fetching...')
                       ->addClass('btn-primary')) }}
    {{ Former::close() }}
  </div>
@stop
