<!--bulk actions-->
@include('pages.inventory.components.actions.checkbox-actions')

<!--main table view-->
@include('pages.inventory.components.table.table')

<!--filter-->
@if(auth()->user()->is_team)
@include('pages.inventory.components.misc.filter-inventory')
@endif
<!--filter-->

<!--custom table view-->
@include('pages.inventory.components.misc.table-config')

<!--export-->
@if(config('visibility.list_page_actions_exporting'))
@include('pages.export.inventory.export')
@endif
