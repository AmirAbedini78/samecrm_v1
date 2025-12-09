<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [create] precheck processes for guarantee letters
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\GuaranteeLetters;
use Closure;
use Log;

class Create {

    /**
     * This middleware does the following:
     *   1. checks users permissions to [create] a new resource
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //validate module status
        if (!config('visibility.modules.guarantee_letters')) {
            abort(404, __('lang.the_requested_service_not_found'));
            return $next($request);
        }

        //frontend
        $this->fronteEnd();

        //does user have permission to create a new guarantee letter
        if (auth()->user()->is_team) {
            if (auth()->user()->role->role_guarantee_letters >= 2) {
                //permission granted
                return $next($request);
            }
        }

        //permission denied
        Log::error("permission denied", ['process' => '[permissions][guarantee-letters][create]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd() {

        //assigning a guarantee letter
        if (auth()->user()->role->role_guarantee_letters >= 2) {
            config(['visibility.guarantee_letter_modal_assign_fields' => true]);
        } else {
            //assign only to current user
            request()->merge([
                'assigned_user_id' => auth()->id(),
            ]);
        }

        //clicked from topnav 'add' button
        if (request('ref') == 'quickadd') {
            config([
                'visibility.guarantee_letter_show_guarantee_option' => true,
            ]);
        }

    }
}

