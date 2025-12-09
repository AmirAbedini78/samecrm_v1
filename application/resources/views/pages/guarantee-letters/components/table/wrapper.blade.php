<!--bulk actions-->
@include('pages.guarantee-letters.components.actions.checkbox-actions')

<!--main table view-->
@include('pages.guarantee-letters.components.table.table')

<!--filter-->
@if(auth()->user()->is_team)
@include('pages.guarantee-letters.components.misc.filter-guarantee-letters')
@endif
<!--filter-->

<!--custom table view-->
@include('pages.guarantee-letters.components.misc.table-config')

