@extends('layout.html')

@section('layout')
<section id="container" >
    
    @include ('layout.header')

    @include ('layout.sidebar')
    
    
    <!-- **********************************************************************************************************************************************************
    MAIN CONTENT
    *********************************************************************************************************************************************************** -->
    <!--main content start-->
    <section id="main-content">
      <section class="wrapper">

        @if( (isset($messages) && count($messages) > 0) || (session()->has('messages') && count(session('messages')) > 0) )
        <?php if(session()->has('messages')) $messages = session('messages'); ?>
        <div class="col-md-12 padding-15">
            @foreach($messages as $message)
            <div class="alert alert-{{ $message['type'] }}"><center>{{ $message['text'] }}</center></div>
            @endforeach
        </div>
        @endif

        @yield ('content')

      </section>
    </section>


    @if( isset($notice) || session()->has('notice') )
    <?php if(session()->has('notice')) $notice = session('notice'); ?>
    <script type="text/javascript">
          $(document).ready(function () {
          var unique_id = $.gritter.add({
              // (string | mandatory) the heading of the notification
              title: '{{ $notice['title'] }}',
              // (string | mandatory) the text inside the notification
              text: '{{ $notice['text'] }}',
              // (string | optional) the image to display on the left
              // image: '{{ asset('/dashio/img/ui-sam.jpg') }}',
              // (int | optional) the time you want it to be alive for before fading out
              time: 8000,
              // (string | optional) the class name you want to apply to that specific message
              // class_name: 'my-sticky-class'
          });

          return false;
          });
    </script>
    @endif

    <!-- include ('layout.footer') -->
</section>

@endsection