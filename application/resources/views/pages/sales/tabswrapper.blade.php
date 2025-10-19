<!--bulk actions-->
@include('pages.sales.components.actions.checkbox-actions')

<!--main table view-->
@include('pages.sales.components.table.table')

<!--filter-->
@if(auth()->user()->is_team)
@include('pages.sales.components.misc.filter-sales')
@endif
<!--filter-->

<!--custom table view-->
@include('pages.sales.components.misc.table-config')

<!--export-->
@if(config('visibility.list_page_actions_exporting'))
@include('pages.export.sales.export')
@endif
