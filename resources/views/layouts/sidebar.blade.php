<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Industry

  </title>
  <link rel="icon" href="{{ asset('img/core-img/favicon.ico') }}">
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/animate.css') }}">
  <link rel="stylesheet" href="{{ asset('style.css') }}">



  <style>
    .treeview>a {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 15px;
      color: #333;
      cursor: pointer;
    }

    .treeview-menu {
      display: none;
      list-style: none;
      padding-left: 20px;
    }

    .treeview.active>.treeview-menu {
      display: block;
    }

    .treeview.active>a .fa-angle-right {
      transform: rotate(90deg);
      transition: transform 0.3s ease;
    }
  </style>
</head>

<body>

  <!-- Logo -->
  <div class="flapt-logo">
    <div style="margin-left:20px; margin-top:10px; margin-right:15px">
      <img class="small-logo" src="{{ asset('img/logo.png') }}" alt="Mobile Logo" style="cursor: pointer;"
        onclick="window.location.href='{{ url('/') }}'" />
    </div>
  </div>


  <!-- Sidebar Navigation -->
  <div class="flapt-sidenav" id="flaptSideNav">
    <div class="side-menu-area">
      <nav style="margin-top:-65px">
        <ul class="sidebar-menu">
          <!-- <li class="menu-header-title">Main</li> -->
          <li><a href="{{ route('dashboard') }}"><i class="bx bx-home-heart"></i> Dashboard</a></li>
      @if(session('admin'))
        <li class="treeview">
        <a href="#">
          <i class="bx bx-user-circle me-2"></i>
          <span>Employees</span>
          <i class="fa fa-angle-right toggle-arrow"></i>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{ asset('add-employee') }}">Add Employee</a></li>
          <li><a href="{{ asset('admin-list') }}">Employees List</a></li>
          <!--<li><a href="{{ asset('store-list') }}">Store Operators</a></li>-->
          <!--<li><a href="{{ asset('production-list') }}">Production Operators</a></li>-->
          <!--<li><a href="{{ asset('inspection-list') }}">Inspection Operators</a></li>-->
        </ul>
        </li>

        <li>
        <a href="{{ asset('customer') }}">
          <i class="bx bx-buildings me-2"></i> {{-- changed from bx-cog --}}
          Customers
        </a>
        </li>

        <li>
        <a href="{{ asset('component') }}">
          <i class="bx bx-wrench me-2"></i> {{-- changed from bx-cog --}}
          Components
        </a>
        </li>
        <li>
        <a href="{{ asset('tools') }}">
          <i class="bx bx-wrench me-2"></i> {{-- changed from bx-cog --}}
          Tools
        </a>
        </li>
        <li>
        <a href="{{ asset('vendor') }}">
          <i class="bx bx-store me-2"></i>{{-- changed from bx-cog --}}
          Vendors
        </a>
        </li>
@endif


    @php
      $employeeType = session('employee_type');
    @endphp
        
        
        
    @if($employeeType === 'Admin')
            

        <li>
        <a href="{{ asset('component') }}">
          <i class="bx bx-wrench me-2"></i> {{-- changed from bx-cog --}}
          Components
        </a>
        </li>
        <li>
        <a href="{{ asset('tools') }}">
          <i class="bx bx-wrench me-2"></i> {{-- changed from bx-cog --}}
          Tools
        </a>
        </li>
        <li>
        <a href="{{ asset('vendor') }}">
          <i class="bx bx-store me-2"></i>{{-- changed from bx-cog --}}
          Vendors
        </a>
        </li>
    @endif
    
    
    
    
      @if($employeeType === 'Store')
         <li><a href="{{ route('dashboard') }}"><i class="bx bx-home-heart"></i> Dashboard</a></li>
        <li class="treeview">
        <a href="#">
          <i class="bx bx-package me-2"></i> {{-- new icon for Raw Material --}}
          <span>Raw Material</span>
          <i class="fa fa-angle-right toggle-arrow"></i>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{ asset('raw-material') }}">Add Raw Materials</a></li>
          <!-- <li><a href="{{ asset('available-raw-material-type') }}">In</a></li> -->
          <li><a href="{{ asset('in-warding') }}">In warding</a></li>
          <!-- <li><a href="{{ asset('consumable-raw-material') }}">Material Issued</a></li> -->
          <li><a href="{{ asset('material-issued') }}">Material Issued</a></li>
          <li><a href="{{ asset('available-raw-materials') }}">Available Raw Materials</a></li>
        </ul>
        </li>
    @endif



          @if($employeeType === 'Production')
             <li><a href="{{ route('dashboard') }}"><i class="bx bx-home-heart"></i> Dashboard</a></li>

        <li>
        <a href="{{ asset('casting') }}">
          <i class="bx bx-building me-2"></i> {{-- changed from bx-buildings --}}
          Castings
        </a>
        </li>
        <li>
        <a href="{{ asset('triming') }}">
          <i class="bx bx-cut me-2"></i> {{-- changed to cut icon --}}
          Triming
        </a>
        </li>
     @endif

         @if($employeeType === 'Inspection')
            <li><a href="{{ route('dashboard') }}"><i class="bx bx-home-heart"></i> Dashboard</a></li>
        <li>
        <a href="{{ asset('inspection') }}">
          <i class="bx bx-shield-quarter me-2"></i>
          Inspection
        </a>
        </li>
        <li>

        <a href="{{ asset('dispatch') }}">
          <i class="bx bx-send me-2"></i>
          Dispatch
        </a>
        </li>

@endif

        </ul>
      </nav>
    </div>
  </div>

  <!-- JS -->
  <script src="{{ asset('js/jquery.min.js') }}"> </script>
  <script src="{{ asset('js/bootstrap.bundle.min.js') }}"> </script>


  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Treeview Toggle Script -->
  <script>
    $(document).ready(function () {
      $('.treeview > a').click(function (e) {
        e.preventDefault();
        var $menu = $(this).next('.treeview-menu');
        var $arrow = $(this).find('.toggle-arrow');

        // Close all other menus and reset arrows
        $('.treeview-menu').not($menu).slideUp();
        $('.toggle-arrow').not($arrow).removeClass('fa-angle-down').addClass('fa-angle-right');

        // Toggle current
        $menu.slideToggle();

        // Toggle arrow icon
        $arrow.toggleClass('fa-angle-right fa-angle-down');
      });
    });
  </script>

</body>

</html>