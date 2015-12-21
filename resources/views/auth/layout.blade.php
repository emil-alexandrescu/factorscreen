@extends('layout.html')

@section('layout')

@yield('content')

<!--BACKSTRETCH-->
<!-- You can use an image of whatever size. This script will stretch to fit in any screen size.-->
<script>
   	// $.backstretch("{{ asset('dashio/img/login-bg.jpg') }}", {speed: 500});
</script>

@endsection