<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Logo — Jadwal Kajian</title>
    <link rel="icon" type="image/png" href="{{ asset('admin-template/img/favicon.png') }}">
    @include('partials.assets')
    @livewireStyles
  </head>
  <body class="framed main-scrollable">
    <div class="wrapper">

      @include('partials.navbar')

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
