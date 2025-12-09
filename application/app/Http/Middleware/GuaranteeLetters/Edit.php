<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [edit] precheck processes for guarantee letters
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\GuaranteeLetters;

use App\Models\GuaranteeLetter;
use App\Permissions\GuaranteeLetterPermissions;
use Closure;
use Log;

class Edit {

    /**
     * The permission repository instance.
     */
    protected $guaranteepermissions;

    /**
     * Inject any dependencies here
     *
     */
    public function __construct(GuaranteeLetterPermissions $guaranteepermissions, GuaranteeLetter $guarantee_model) {

        //guarantee letter permissions repo
        $this->guaranteepermissions = $guaranteepermissions;

    }

    /**
     * This middleware does the following
     *   1. validates that the guarantee letter exists
     *   2. checks users permissions to [edit] the resource
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

        //guarantee letter id
        $guarantee_id = $request->route('guarantee');

        //frontend
        $this->fronteEnd();

        //basic validation
        if (!$guarantee = \App\Models\GuaranteeLetter::Where('guarantee_id', $guarantee_id)->first()) {
            Log::error("guarantee letter could not be found", ['process' => '[permissions][guarantee-letters][edit]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'guarantee id' => $guarantee_id ?? '']);
            abort(409, __('lang.guarantee_letter_not_found'));
        }

        //permission: does user have permission to edit this guarantee letter
        if ($this->guaranteepermissions->check('edit', $guarantee_id)) {
            return $next($request);
        }

        //permission denied
        Log::error("permission denied", ['process' => '[permissions][guarantee-letters][edit]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'guarantee id' => $guarantee_id ?? '']);
        abort(403);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd() {

        //assigning a guarantee letter
        if (auth()->user()->role->role_guarantee_letters >= 2) {
            config(['visibility.guarantee_letter_modal_assign_fields' => true]);
            request()->merge([
                'edit_assigned' => true,
            ]);
        }
    }
}

