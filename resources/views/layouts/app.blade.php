<!DOCTYPE html>
<html lang="en" data-footer="true">

@include('layouts.head')

<body>
    <div id="root">
        @include('layouts.navbar')

        <main>
            @yield('content')
        </main>
        <!-- Layout Footer Start -->
        @include('layouts.footer')
        <!-- Layout Footer End -->
    </div>

    <!-- Theme Settings Modal Start -->
    @include('layouts.theme-settings')
    <!-- Theme Settings Modal End -->
    
    <!-- Niches Modal Start -->

    <!-- Niches Modal End -->

    <!-- Theme Settings & Niches Buttons Start -->
    @include('layouts.theme-modals')
    <!-- Theme Settings & Niches Buttons End -->

    <!-- Search Modal Start -->
   
    <!-- Search Modal End -->

    @stack('modals')

    <!-- Vendor Scripts Start -->
    @include('layouts.scripts')
    <!-- Page Specific Scripts End -->
</body>

</html>