@extends('layout')

@section('content')
  <div class="row history-url-index">
    <div class="btn-group">
      <button type="button" class="btn btn-primary" onclick="window.location.href='{{ action('HistoryUrlController@getCreate') }}'">Create More</button>
    </div>

    <table class="table table-striped">
      <thead>
        <tr>
          <th>Url</th>      
          <th>Date</th>
          <th>Grabbed</th>
          <th>Timeout Count</th>
          <th>Property Grabbed</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($urls as $url)
          <tr>
            <td><a href="{{ $url->url }}" target='_blank'>{{ $url->url }}</a></td>
            <td>{{ $url->sales_date }}</td>
            <td>{{ $url->grabbed }}</td>
            <td>{{ $url->timeout_count }}</td>
            <td>{{ $url->bps_id }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <?php echo $urls->links(); ?>
  </div>
@stop
