<aside>
    <div id="sidebar"  class="nav-collapse ">
    <!-- sidebar menu start-->
    <ul class="sidebar-menu" id="nav-accordion">

        <!-- <p class="centered"><a href="profile.html"><img src="assets/img/ui-sam.jpg" class="img-circle" width="80"></a></p>
        <h5 class="centered">Sam Soffes</h5> -->
        @if(Auth::user()->type == 1)
        <p class="centered"><button class="btn btn-success btn-sm"  data-toggle="modal" data-target="#uploadStockDataModal" data-backdrop="static" data-keyboard="false"><i class="fa fa-plus"></i> Upload new Data</button></p>
        @endif
	  	
        <li>
            <a href="{{ url('/') }}">
                <i class="fa fa-dashboard"></i>
                <span>Dashboard</span>
            </a>
        </li>

        @if(Auth::user()->type == 0)
        <!-- <li>
            <a href="{{ url('/') }}">
                <i class="fa fa-envelope"></i>
                <span>Support</span>
            </a>
        </li> -->
        @else
        <li>
            <a href="{{ url('/users') }}">
                <i class="fa fa-user"></i>
                <span>Users</span>
            </a>
        </li>
        <li>
            <a href="{{ url('/usage') }}">
                <i class="fa fa-bar-chart-o"></i>
                <span>Usage</span>
            </a>
        </li>
        @endif
        
        <li>
            <a href="{{ url('/settings') }}">
                <i class="fa fa-gear"></i>
                <span>Settings</span>
            </a>
        </li>

        <li>
            <a href="{{ url('/auth/logout') }}">
                <i class="fa fa-power-off"></i>
                <span>Logout ({{ Auth::user()->name }})</span>
            </a>
        </li>

    </ul>
    <!-- sidebar menu end-->
    </div>
</aside>
@include('stockdata.create');