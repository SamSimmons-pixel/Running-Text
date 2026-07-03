<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Logo — Jadwal Kajian</title>
    <link rel="icon" type="image/png" href="{{ asset('admin-template/img/favicon.png') }}">
    <link href="{{ asset('admin-template/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,400italic,500,700,900" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin-template/libs/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-template/libs/jquery.scrollbar/jquery.scrollbar.css') }}" rel="stylesheet">
    <link href="{{ asset('admin-template/css/right.dark.css') }}" rel="stylesheet">

    <link href="{{ asset('admin-template/css/custom-admin.css') }}" rel="stylesheet">
    @livewireStyles
  </head>
  <body class="framed main-scrollable">
    <div class="wrapper">

      {{-- ── Top Navbar ── --}}
      <nav class="navbar navbar-static-top header-navbar">
        <div class="header-navbar-mobile">
          <div class="header-navbar-mobile__menu">
            <button class="btn" type="button"><i class="fa fa-bars"></i></button>
          </div>
          <div class="header-navbar-mobile__title"><span>Kelola Logo</span></div>
          <div class="header-navbar-mobile__settings dropdown">
            <a class="btn dropdown-toggle" href="" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-power-off"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-right">
              <li>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                  <i class="fa fa-sign-out"></i> Logout
                </a>
                <form id="logout-form-mobile" method="POST" action="{{ route('logout') }}" style="display:none;">@csrf</form>
              </li>
            </ul>
          </div>
        </div>

        <div class="navbar-header">
          <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
            <div class="logo text-nowrap">
              <div class="logo__img"><i class="fa fa-book"></i></div>
              <span class="logo__text">Jadwal Kajian</span>
            </div>
          </a>
        </div>

        <div class="topnavbar">
          <ul class="nav navbar-nav navbar-left">
            <li><a href="{{ route('admin.dashboard') }}"><span>Dashboard</span></a></li>
            <li>
              <a href="{{ url('/') }}" target="_blank">
                <span><i class="fa fa-external-link" style="font-size:0.75em;"></i> MainPage</span>
              </a>
            </li>
          </ul>
          <ul class="userbar nav navbar-nav">
            <li class="dropdown">
              <a class="userbar__settings dropdown-toggle" href="" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-power-off"></i>
              </a>
              <ul class="dropdown-menu dropdown-menu-right">
                <li>
                  <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-top').submit();">
                    <i class="fa fa-sign-out"></i> Logout
                  </a>
                  <form id="logout-form-top" method="POST" action="{{ route('logout') }}" style="display:none;">@csrf</form>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </nav>

      {{-- ── Dashboard Wrapper ── --}}
      <div class="dashboard">

        @include('partials.sidebar')

        {{-- ── Main Content ── --}}
        <div class="main">
          <div class="main__scroll scrollbar-macosx">
            <div class="main__cont">

              <div class="main-heading">
                <div class="main-title">
                  <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                    <li class="active">Kelola Logo</li>
                  </ol>
                </div>
              </div>

              <div class="container-fluid half-padding">

                {{-- Alerts --}}
                @if (session('success'))
                  <div class="alert-kajian success">
                    <i class="fa fa-check-circle"></i>
                    {{ session('success') }}
                  </div>
                @endif
                @if (session('error'))
                  <div class="alert-kajian error">
                    <i class="fa fa-exclamation-circle"></i>
                    {{ session('error') }}
                  </div>
                @endif
                @if ($errors->any())
                  <div class="alert-kajian error">
                    <i class="fa fa-exclamation-circle"></i>
                    <div>
                      @foreach ($errors->all() as $e) <div>{{ $e }}</div> @endforeach
                    </div>
                  </div>
                @endif

                {{-- ── Global Logo Upload Panel ── --}}
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-image"></i> Logo Global</h3>
                  </div>
                  <div class="panel-body">
                    <form method="POST" action="{{ route('admin.logo.update') }}" enctype="multipart/form-data" class="form-inline" style="display:flex; align-items:center; gap:20px; flex-wrap:wrap; justify-content:space-between;">
                      @csrf
                      <div style="display:flex; justify-content:space-between; gap:20px; width: 89%;">
                        <div class="form-group" style="margin-bottom:0; display:flex; align-items:center; gap:10px;">
                          <label class="control-label" style="margin-bottom:0;">Logo Berjalan Saat Ini:</label>
                          <div class="td-logo" style="display:inline-block;">
                            @if ($logoUrl)
                              <img src="{{ $logoUrl }}" alt="Logo Global" style="max-height:80px; width:auto; border-radius:6px; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.07); padding:3px;">
                            @else
                              <span class="no-logo">Belum ada logo</span>
                            @endif
                          </div>
                        </div>
                        <div class="form-group" style="margin-bottom:0; display:flex; align-items:center; gap:10px;">
                          <label class="control-label" style="margin-bottom:0;">Ganti Logo Global:</label>
                          <input type="file" name="Logo" class="form-control form-control-custom" accept="image/*" required style="display:inline-block; width:auto;">
                        </div>
                      </div>
                      <button type="submit" class="btn btn-primary" style="margin-top:0;">
                        <i class="fa fa-upload"></i> Upload Logo
                      </button>
                    </form>
                  </div>
                </div>

              </div>{{-- end container-fluid --}}
            </div>{{-- end main__cont --}}
          </div>{{-- end main__scroll --}}
        </div>{{-- end main --}}

      </div>{{-- end dashboard --}}
    </div>{{-- end wrapper --}}

    <script src="{{ asset('admin-template/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin-template/libs/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin-template/libs/jquery.scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('admin-template/js/main.js') }}"></script>
    @livewireScripts
    <script>
      document.addEventListener('livewire:navigated', function() {
        if (window.jQuery) {
          var $ = window.jQuery;
          $('body.main-scrollable .main__scroll').scrollbar();
          $('.scrollable').scrollbar({'disableBodyScroll' : true});
          
          $('.header-navbar-mobile__menu button').off('click').on('click', function() {
            $('.dashboard').toggleClass('dashboard_menu');
          });
        }
      });
    </script>
  </body>
</html>
