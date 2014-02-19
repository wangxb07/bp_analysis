@extends('layout')

@section('head')
  <style>
   .front-content {
     padding: 40px 15px;
     text-align: center;
   }
  </style>
@stop

@section('content')
  <div class="row main">
    <div class="front-content">
      <h1>当天的房产销售数据</h1>
    </div>
    <button class="btn btn-primary" data-load-text="Loading..." id="fetchDataBtn" type="button">获取最新数据</button>
  </div>
@stop

@section('footer')

  <script type="text/javascript">
   require(['jquery', 'bootstrap'], function($) {
     $('#fetchDataBtn').click(function() {
       var btn = $(this);
       btn.button('loading');

       $.ajax('{{ action('HomeController@grabNewestInfo') }}', {
         type: 'post',
         success: function(data, textStatus, jqXHR) {
           if (textStatus == 'success') {
             console.log(data);
           }
           btn.button('reset');
         },
         error: function(jqXHR, textStatus, errorThrown) {
           console.log(textStatus);
           btn.button('reset');
         }
       });
     });
   });
  </script>

@stop
