<!-- CSS Stylesheets -->
<link href="{{ asset('admin-template/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,400italic,500,700,900" rel="stylesheet" type="text/css">
<link href="{{ asset('admin-template/libs/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin-template/libs/jquery.scrollbar/jquery.scrollbar.css') }}" rel="stylesheet">
<link href="{{ asset('admin-template/css/right.dark.css') }}" rel="stylesheet">
<link href="{{ asset('admin-template/libs/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin-template/libs/ionrangeslider/css/ion.rangeSlider.css') }}" rel="stylesheet">
<link href="{{ asset('admin-template/css/custom-admin.css') }}" rel="stylesheet">

<!-- JS Core & Library Scripts -->
<script src="{{ asset('admin-template/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('admin-template/libs/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('admin-template/libs/jquery.scrollbar/jquery.scrollbar.min.js') }}"></script>
<script src="{{ asset('admin-template/libs/bootstrap-tabdrop/bootstrap-tabdrop.min.js') }}"></script>
<script src="{{ asset('admin-template/libs/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
<script src="{{ asset('admin-template/libs/ionrangeslider/js/ion.rangeSlider.min.js') }}"></script>
<script src="{{ asset('admin-template/libs/inputNumber/js/inputNumber.js') }}"></script>

<meta name="user-id" content="{{ auth()->id() }}">

<!-- Safeguard jQuery overrides for missing theme dependencies -->
<script>
  if (window.jQuery) {
    var $ = window.jQuery;
    if (!$.fn.ionRangeSlider) {
      $.fn.ionRangeSlider = function() { return this; };
    }
    if (!$.fn.inputNumber) {
      $.fn.inputNumber = function() { return this; };
    }
    if (!$.fn.bootstrapSwitch) {
      $.fn.bootstrapSwitch = function() { return this; };
    }
    if (!$.fn.tabdrop) {
      $.fn.tabdrop = function() { return this; };
    }
  }

  // Global robust tooltip helper functions
  window.updateTooltip = function(input, defaultText) {
    const container = input.closest('.tooltip-container');
    if (!container) return;
    const tooltip = container.querySelector('.tooltiptext');
    if (!tooltip) return;
    
    const val = input.value;
    tooltip.textContent = val;
  };

  window.hideTooltip = function(input) {
    const container = input.closest('.tooltip-container');
    if (container) {
      container.classList.remove('show-tooltip');
    }
  };
</script>

<!--  Laravel Echo dan WebSocket -->
@vite(['resources/js/app.js'])
