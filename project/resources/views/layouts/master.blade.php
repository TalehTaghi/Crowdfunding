<!DOCTYPE html>
<html lang="en">
@include('layouts.head')
<body>
<div class="body">
    <div class="site-header-wrapper">
        @include('layouts.header')
    </div>
    <div id="main-container">
        @yield('content')
    </div>
    <div class="site-footer-bottom">
        @include('layouts.footer')
    </div>
</div>
@include('layouts.js')
</body>
</html>
