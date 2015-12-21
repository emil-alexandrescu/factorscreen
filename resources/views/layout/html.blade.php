<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="http://alvarez.is/demo/dashio/favicon.png">

    <title>Stock Screener | {{ empty($title) ? 'Welcome to Stock Screener!' : $title}}</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('/dashio/css/bootstrap.css') }}" rel="stylesheet">
    <!--external css-->
    <link href="{{ asset('/dashio/font-awesome/css/font-awesome.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/dashio/css/zabuto_calendar.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/dashio/js/gritter/css/jquery.gritter.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/dashio/js/bootstrap-taginput/bootstrap-tagsinput.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/dashio/js/bootstrap-datepicker/css/datepicker.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/dashio/js/bootstrap-daterangepicker/daterangepicker.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/dashio/js/bootstrap-timepicker/compiled/timepicker.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/dashio/js/bootstrap-datetimepicker/css/datetimepicker.css') }}" />
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.css">


    
    <link rel="stylesheet" href="{{ asset('/bower_components/angular-datatables/dist/plugins/bootstrap/datatables.bootstrap.min.css') }}">
    <!-- Custom styles for this template -->
    <link href="{{ asset('/dashio/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('/dashio/css/style-responsive.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">

    <script src="{{ asset('/dashio/js/chart-master/Chart.js') }}"></script>
    
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->



    <!-- js placed at the end of the document so the pages load faster -->
    <script src="{{ asset('/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('/dashio/js/bootstrap.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
    <script class="include" type="text/javascript" src="{{ asset('/dashio/js/jquery.dcjqaccordion.2.7.js') }}"></script>
    <script src="{{ asset('/dashio/js/jquery.scrollTo.min.js') }}"></script>
    <script src="{{ asset('/dashio/js/jquery.nicescroll.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/dashio/js/jquery.sparkline.js') }}"></script>
    <script src="{{ asset('/dashio/js/bootstrap-taginput/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ asset('/dashio/js/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/dashio/js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/dashio/js/bootstrap-daterangepicker/date.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/dashio/js/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.js"></script>


    
    <script type="text/javascript" src="{{ asset('/dashio/js/gritter/js/jquery.gritter.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/dashio/js/gritter-conf.js') }}"></script>

    <!--script for this page-->
    <script src="{{ asset('/dashio/js/sparkline-chart.js') }}"></script>    
    <script src="{{ asset('/dashio/js/zabuto_calendar.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashio/js/jquery.backstretch.min.js') }}"></script>

    <!--angular-->
    <script type="text/javascript" src="{{ asset('bower_components/angular/angular.min.js') }}"></script>   
    <script type="text/javascript" src="{{ asset('bower_components/angular-datatables/dist/angular-datatables.min.js') }}"></script>
    <script src="{{ asset('bower_components/angular-datatables/dist/plugins/bootstrap/angular-datatables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('bower_components/angular-datatables/dist/plugins/colvis/angular-datatables.colvis.min.js') }}"></script>
    <script src="{{ asset('bower_components/angular-datatables/dist/plugins/tabletools/angular-datatables.tabletools.min.js') }}"></script>
    <script src="{{ asset('bower_components/angular-bootstrap/ui-bootstrap.min.js') }}"></script>
    <script src="{{ asset('bower_components/angular-bootstrap/ui-bootstrap-tpls.min.js') }}"></script>

    <script type="text/javascript" src="{{ asset('bower_components/checklist-model/checklist-model.js') }}"></script>
    
    <script src="{{ asset('bower_components/angular-rangeslider/angular.rangeSlider.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('bower_components/angular-rangeslider/angular.rangeSlider.css') }}">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js"></script>

    <script src="{{ asset('bower_components/angular-busy/angular-busy.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('bower_components/angular-busy/angular-busy.css') }}">


    <!-- stock screener -->
    <script src="{{ asset('/js/lib/main.js') }}"></script>
  </head>

  <body>

    @yield('layout')

    <!--common script for all pages-->
    <script src="{{ asset('/dashio/js/common-scripts.js') }}"></script>
  </body>
</html>
