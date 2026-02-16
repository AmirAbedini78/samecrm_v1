@extends('pages.settings.ajaxwrapper')
@section('settings-page')
<!--settings-->
@if(empty($isModal))<form class="form" id="theme-settings-form">@endif
    @if(!empty($isModal))
    <input type="hidden" name="url_type" value="modal">
    @endif
    <!--form text tem-->
    <div class="form-group row">
        <label class="col-12 control-label col-form-label">{{ cleanLang(__('lang.main_theme')) }}</label>
        <div class="col-12">
            <select class="select2-basic form-control form-control-sm" id="settings_theme_name"
                name="settings_theme_name">
                @foreach(config('theme.list') as $theme)
                <option value="{{ $theme }}" {{ runtimePreselected($theme, $settings->settings_theme_name ?? '') }}>
                    {{ runtimeThemeName($theme) }}</option>
                @endforeach
            </select>
        </div>
    </div>


    <div class="form-group form-group-checkbox row">
        <div class="col-12 p-t-5">
            <input type="checkbox" id="reset_users_theme" name="reset_users_theme" class="filled-in chk-col-light-blue">
            <label class="p-l-30" for="reset_users_theme">@lang('lang.reset_users_theme')</label>
        </div>
    </div>

    <div class="line"></div>

    <div class="alert alert-info hidden">
        {{ cleanLang(__('lang.head_body_information')) }}
    </div>

    <!--form checkbox item-->
    <div class="form-group form-group-checkbox row">
        <label class="col-12 col-form-label">{{ cleanLang(__('lang.head')) }}</label>
        <div class="col-12 p-t-5">
            <textarea class="form-control form-control-sm" rows="10" name="settings_theme_head"
                id="settings_theme_head">{{ $settings->settings_theme_head }}</textarea>
        </div>
    </div>

    <!--form checkbox item-->
    <div class="form-group form-group-checkbox row">
        <label class="col-12 col-form-label">{{ cleanLang(__('lang.body')) }}</label>
        <div class="col-12 p-t-5">
            <textarea class="form-control form-control-sm" rows="10" name="settings_theme_body"
                id="settings_theme_body">{{ $settings->settings_theme_body }}</textarea>
        </div>
    </div>

    <!--css_style-->
    <div class="form-group form-group-checkbox row m-b-30">
        <label class="col-12 col-form-label">{{ cleanLang(__('lang.css_style')) }} <span
                class="align-middle text-info font-16" data-toggle="tooltip" title="@lang('lang.custom_css_crm')"
                data-placement="top"><i class="ti-info-alt"></i></span></label>
        <div class="col-12 p-t-5">
            <textarea id="css-editor-textarea" class="hidden" name="settings2_theme_css"
                data-crm-theme="{{ auth()->user()->pref_theme }}">{{  $settings2->settings2_theme_css ?? '' }}</textarea>
        </div>
    </div>

    <div class="line"></div>
    <h5 class="col-12 m-b-15">{{ cleanLang(__('lang.font_and_appearance')) }}</h5>

    <div class="form-group row">
        <label class="col-12 control-label col-form-label">{{ cleanLang(__('lang.font_scope')) }}</label>
        <div class="col-12">
            <select class="form-control form-control-sm" id="font_scope" name="font_scope">
                <option value="whole_app" {{ runtimePreselected('whole_app', $fontSettings['scope'] ?? 'whole_app') }}>{{ cleanLang(__('lang.font_scope_whole_app')) }}</option>
                <option value="this_page" {{ runtimePreselected('this_page', $fontSettings['scope'] ?? '') }}>{{ cleanLang(__('lang.font_scope_this_page')) }}</option>
            </select>
        </div>
    </div>
    <div class="form-group row font-page-route-row" style="{{ ($fontSettings['scope'] ?? '') == 'this_page' ? '' : 'display:none' }}">
        <label class="col-12 control-label col-form-label">{{ cleanLang(__('lang.font_page_apply_to')) }}</label>
        <div class="col-12">
            <select class="form-control form-control-sm" name="font_page_route">
                <option value="belzona-inventory" {{ runtimePreselected('belzona-inventory', $fontSettings['page_route'] ?? '') }}>انبار بلزونا</option>
            </select>
        </div>
    </div>

    @php
        $fs = $fontSettings ?? [];
        $section = function($key, $label) use ($fs) {
            $d = $fs[$key] ?? ['font_family' => '', 'font_size' => '', 'color' => ''];
            return [
                'label' => $label,
                'family' => $d['font_family'] ?? '',
                'size' => $d['font_size'] ?? '',
                'color' => $d['color'] ?? '',
                'name_prefix' => 'font_' . $key,
            ];
        };
    @endphp

    <div class="form-group row">
        <label class="col-12 col-form-label">{{ cleanLang(__('lang.font_datatable_title')) }}</label>
        <div class="col-12">
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" class="form-control form-control-sm" name="font_datatable_title_family" placeholder="{{ cleanLang(__('lang.font_family')) }}" value="{{ $section('datatable_title', '')['family'] }}">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control form-control-sm" name="font_datatable_title_size" placeholder="{{ cleanLang(__('lang.font_size')) }}" value="{{ $section('datatable_title', '')['size'] }}">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control form-control-sm" name="font_datatable_title_color" placeholder="{{ cleanLang(__('lang.color')) }}" value="{{ $section('datatable_title', '')['color'] }}">
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-12 col-form-label">{{ cleanLang(__('lang.font_page_titles')) }}</label>
        <div class="col-12">
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" class="form-control form-control-sm" name="font_page_titles_family" placeholder="{{ cleanLang(__('lang.font_family')) }}" value="{{ $section('page_titles', '')['family'] }}">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control form-control-sm" name="font_page_titles_size" placeholder="{{ cleanLang(__('lang.font_size')) }}" value="{{ $section('page_titles', '')['size'] }}">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control form-control-sm" name="font_page_titles_color" placeholder="{{ cleanLang(__('lang.color')) }}" value="{{ $section('page_titles', '')['color'] }}">
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-12 col-form-label">{{ cleanLang(__('lang.font_datatable_text')) }}</label>
        <div class="col-12">
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" class="form-control form-control-sm" name="font_datatable_text_family" placeholder="{{ cleanLang(__('lang.font_family')) }}" value="{{ $section('datatable_text', '')['family'] }}">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control form-control-sm" name="font_datatable_text_size" placeholder="{{ cleanLang(__('lang.font_size')) }}" value="{{ $section('datatable_text', '')['size'] }}">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control form-control-sm" name="font_datatable_text_color" placeholder="{{ cleanLang(__('lang.color')) }}" value="{{ $section('datatable_text', '')['color'] }}">
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-12 col-form-label">{{ cleanLang(__('lang.font_page_text')) }}</label>
        <div class="col-12">
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" class="form-control form-control-sm" name="font_page_text_family" placeholder="{{ cleanLang(__('lang.font_family')) }}" value="{{ $section('page_text', '')['family'] }}">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control form-control-sm" name="font_page_text_size" placeholder="{{ cleanLang(__('lang.font_size')) }}" value="{{ $section('page_text', '')['size'] }}">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control form-control-sm" name="font_page_text_color" placeholder="{{ cleanLang(__('lang.color')) }}" value="{{ $section('page_text', '')['color'] }}">
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-12 col-form-label">{{ cleanLang(__('lang.font_buttons')) }}</label>
        <div class="col-12">
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" class="form-control form-control-sm" name="font_buttons_family" placeholder="{{ cleanLang(__('lang.font_family')) }}" value="{{ $section('buttons', '')['family'] }}">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control form-control-sm" name="font_buttons_size" placeholder="{{ cleanLang(__('lang.font_size')) }}" value="{{ $section('buttons', '')['size'] }}">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control form-control-sm" name="font_buttons_color" placeholder="{{ cleanLang(__('lang.color')) }}" value="{{ $section('buttons', '')['color'] }}">
                </div>
            </div>
        </div>
    </div>
    <script>
    (function(){ document.getElementById('font_scope').addEventListener('change', function(){
        var row = document.querySelector('.font-page-route-row'); if(row) row.style.display = this.value === 'this_page' ? '' : 'none';
    }); })();
    </script>

    @if(config('system.settings_type') == 'standalone')
    <!--[standalone] - settings documentation help-->
    <div>
        <a href="https://growcrm.io/documentation" target="_blank" class="btn btn-sm btn-info help-documentation"><i
                class="ti-info-alt"></i>
            {{ cleanLang(__('lang.help_documentation')) }}
        </a>
    </div>
    @endif

    <!--buttons-->
    @if(empty($isModal))
    <div class="text-right">
        <button type="submit" id="commonModalSubmitButton"
            class="btn btn-rounded-x btn-danger waves-effect text-left js-ajax-ux-request" data-url="/settings/theme"
            data-loading-target="" data-ajax-type="PUT" data-type="form"
            data-on-start-submit-button="disable">{{ cleanLang(__('lang.save_changes')) }}</button>
    </div>
    @endif
@if(empty($isModal))</form>@endif
@endsection