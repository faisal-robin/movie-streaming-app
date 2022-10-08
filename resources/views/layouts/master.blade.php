@include('layouts.header_link')

<div class="wrapper">

  @include('layouts.header_top')
  @include('layouts.side_bar')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <section class="content">
      <div class="container-fluid">
        @yield('content')
      </div>
    </section>
  </div>

  @include('layouts.side_bar')
  @include('layouts.footer')
</div>
<!-- ./wrapper -->

@include('layouts.footer_link')
