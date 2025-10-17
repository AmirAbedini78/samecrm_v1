@extends('layout.wrapper')
@section('content')
<!-- main content -->
<div class="container-fluid">

    <!--page heading-->
    <div class="row page-titles">

        <!-- Page Title & Bread Crumbs -->
        @include('misc.heading-crumbs')
        <!--Page Title & Bread Crumbs -->

        <!-- action buttons -->
        @include('pages.sales.components.misc.list-page-actions')
        <!-- action buttons -->

    </div>
    <!--page heading-->

    <!--stats panel-->
    @include('pages.sales.components.misc.stats-panel')
    <!--stats panel-->

    <!--filter panel-->
    @include('pages.sales.components.misc.filter-panel')
    <!--filter panel-->

    <!--page content-->
    @yield('content')
    <!--page content-->

</div>
<!--main content-->
@endsection
