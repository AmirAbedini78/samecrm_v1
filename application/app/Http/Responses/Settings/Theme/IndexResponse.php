<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [index] process for the theme settings
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Settings\Theme;
use Illuminate\Contracts\Support\Responsable;

class IndexResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for theme
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        $isModal = request('url_type') === 'modal';
        $html = view('pages/settings/sections/theme/page', compact('page', 'settings', 'settings2', 'fontSettings', 'isModal'))->render();

        $targetSelector = $isModal ? '#commonModalBody' : '#settings-wrapper';
        $jsondata['dom_html'][] = array(
            'selector' => $targetSelector,
            'action' => 'replace',
            'value' => $html);

        //left menu activate (skip when opened as modal)
        if (request('url_type') == 'dynamic' && !$isModal) {
            $jsondata['dom_attributes'][] = [
                'selector' => '#settings-menu-main',
                'attr' => 'aria-expanded',
                'value' => false,
            ];
            $jsondata['dom_action'][] = [
                'selector' => '#settings-menu-main',
                'action' => 'trigger',
                'value' => 'click',
            ];
            $jsondata['dom_classes'][] = [
                'selector' => '#settings-menu-main-theme',
                'action' => 'add',
                'value' => 'active',
            ];
        }

        // postrun function (CodeMirror for CSS editor - only when not modal or when modal has the textarea)
        $jsondata['postrun_functions'][] = [
            'value' => 'NXCodeMirrorCSSEditor',
        ];

        //ajax response
        return response()->json($jsondata);
    }
}
