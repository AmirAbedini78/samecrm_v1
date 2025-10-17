{{-- RTL Helper Functions --}}

@php
    use App\Helpers\RTLHelper;
    
    $isRTL = RTLHelper::isRTL();
    $direction = RTLHelper::getDirection();
    $rtlClass = RTLHelper::getRTLClass();
    $textAlign = RTLHelper::getTextAlignClass();
@endphp

{{-- RTL CSS Variables --}}
@if($isRTL)
<style>
    :root {
        --rtl-direction: rtl;
        --rtl-text-align: right;
        --rtl-margin-left: 0;
        --rtl-margin-right: var(--margin-value);
        --rtl-padding-left: 0;
        --rtl-padding-right: var(--padding-value);
        --rtl-float: right;
        --rtl-border-radius-left: 0;
        --rtl-border-radius-right: var(--border-radius-value);
    }
</style>
@endif

{{-- RTL JavaScript Variables --}}
<script>
    window.RTL = {
        isRTL: {{ $isRTL ? 'true' : 'false' }},
        direction: '{{ $direction }}',
        textAlign: '{{ $isRTL ? 'right' : 'left' }}',
        marginDirection: '{{ $isRTL ? 'margin-right' : 'margin-left' }}',
        paddingDirection: '{{ $isRTL ? 'padding-right' : 'padding-left' }}',
        floatDirection: '{{ $isRTL ? 'float-right' : 'float-left' }}',
        locale: '{{ app()->getLocale() }}'
    };
</script>

