<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Clean Meister') }}</title>

  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}" defer></script>

  <!-- Fonts -->
  <link rel="dns-prefetch" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper" id="app">

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('home') }}" class="nav-link">Home</a>
      </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      <message-component base_url="{{ url('/') }}"></message-component>
      <!-- Notifications Dropdown Menu -->
      <notification-component base_url="{{ url('/') }}"></notification-component>
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          {{ Auth::user()->username }} <i class="far fa-user"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="{{ url('/') }}/page_profile" class="dropdown-item">
              <i class="fas fa-user"></i> Profile
          </a>
          <!-- <a href="{{ url('/') }}/page_change_password" class="dropdown-item">
              <i class="fas fa-lock"></i> Change Password
          </a> -->
          <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="fas fa-sign-out-alt"></i> Logout
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
          </form>
        </div>
      </li>
    </ul>
  </nav>

  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link">
      <img src=""  class="brand-image img-circle elevation-3"style="opacity: .8">
      <span class="brand-text font-weight-light">{{ config('app.name', 'Clean Meister') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
              @if((Auth::user()->profile->profile_pic != null || Auth::user()->profile->profile_pic != '') && file_exists(public_path('img/profiles/'.Auth::user()->profile->profile_pic)))
                  <img style="" src="{{ url('/') }}/img/profiles/{{ Auth::user()->profile->profile_pic }}" class="img-circle elevation-2">
              @else
                  <img style="" src="{{ url('/') }}/img/user-circle.png" class="img-circle elevation-2">
              @endif
          </div>
          <div class="info">
              <a href="{{ url('/') }}/page_profile" class="d-block"><strong>{{ Auth::user()->profile->firstname }} {{ Auth::user()->profile->lastname }}</strong></a>
          </div>
      </div>

      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          @if(Auth::user()->role_id == 1)
          <li class="nav-item">
            <a href="{{ route('home') }}" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/') }}/manage_accounts" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Manage Accounts
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/') }}/pending_requests" class="nav-link">
              <i class="nav-icon far fa-file-alt"></i>
              <p>
                Pending Requests
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('/') }}/admin_report" class="nav-link">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>
                Reports
              </p>
            </a>
          </li>
          @endif
          @if(Auth::user()->role_id == 2)
            @if(Auth::user()->approved == 1)
              <li class="nav-item">
                <a href="{{ url('/') }}/service_provider_services" class="nav-link">
                  <i class="nav-icon fas fa-tasks"></i>
                  <p>
                    Services
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/') }}/cleaners" class="nav-link">
                  <i class="nav-icon fas fa-broom"></i>
                  <p>
                    Cleaners
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/') }}/page_clients" class="nav-link">
                  <i class="nav-icon fas fa-user-friends"></i>
                  <p>
                    Clients
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/') }}/service_provider_booking" class="nav-link">
                <i class="nav-icon fas fa-book"></i>
                  <p>
                    Booking Transaction
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/') }}/complaints" class="nav-link">
                  <i class="nav-icon fas fa-exclamation" data-fa-mask="fas fa-comment-alt" data-fa-transform="shrink-7 up-1.5"></i>
                  <p>
                    Complaints
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/') }}/service_provider_report" class="nav-link">
                <i class="nav-icon fas fa-chart-bar"></i>
                  <p>
                    Reports
                  </p>
                </a>
              </li>
            @endif
          @endif
          @if(Auth::user()->role_id == 3)
            <li class="nav-item">
              <a href="{{ url('/') }}/cleaner_booking" class="nav-link">
                <i class="nav-icon fas fa-book"></i>
                <p>
                  Booking Transaction
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/') }}/schedules" class="nav-link">
                <i class="nav-icon far fa-calendar-alt"></i>
                <p>
                  Schedules
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/') }}/cleaner_report" class="nav-link">
                <i class="nav-icon fas fa-chart-bar"></i>
                <p>
                  Reports
                </p>
              </a>
            </li>
          @endif
          @if(Auth::user()->role_id == 4)
            <li class="nav-item">
              <a href="{{ url('/') }}/client_service_provider" class="nav-link">
                <i class="nav-icon fas fa-book"></i>
                <p>
                  Book A Service
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/') }}/client_booking" class="nav-link">
                <i class="nav-icon far fa-file-alt"></i>
                <p>
                  Transactions
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/') }}/client_report" class="nav-link">
                <i class="nav-icon fas fa-chart-bar"></i>
                <p>
                  Reports
                </p>
              </a>
            </li>
          @endif
        </ul>
      </nav>

    </div>

  </aside>
    <scrolledtobottomcomponent></scrolledtobottomcomponent>
  <div class="content-wrapper {{ Request::is('messaging') ? 'chat-wrapper' : '' }}">
    @yield('content')
  </div>
</div>
</body>
</html>
