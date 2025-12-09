<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [show] precheck processes for guarantee letters
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\GuaranteeLetters;

use App\Models\GuaranteeLetter;
use App\Permissions\GuaranteeLetterPermissions;
use App\Repositories\GuaranteeLetterRepository;
use Closure;
use Log;

class Show {

    //vars
    protected $guaranteepermissions;
    protected $guaranteemodel;
    protected $guaranteerepo;

    /**
     * Inject any dependencies here
     *
     */
    public function __construct(GuaranteeLetterPermissions $guaranteepermissions, GuaranteeLetter $guaranteemodel, GuaranteeLetterRepository $guaranteerepo) {

        $this->guaranteepermissions = $guaranteepermissions;
        $this->guaranteemodel = $guaranteemodel;
        $this->guaranteerepo = $guaranteerepo;

    }

    /**
     * This middleware does the following:
     *   1. validates that the guarantee letter exists
     *   2. checks users permissions to [show] the resource
     *   3. sets various visibility and permissions settings (e.g. menu items, edit buttons etc)
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

        //basic validation
        if (!$guarantee = $this->guaranteemodel::find($guarantee_id)) {
            Log::error("guarantee letter could not be found", ['process' => '[permissions][guarantee-letters][show]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'guarantee id' => $guarantee_id ?? '']);
            abort(404);
        }

        //friendly format
        $guarantees = $this->guaranteerepo->search($guarantee_id);
        $guarantee = $guarantees->first();

        //frontend
        $this->fronteEnd($guarantee);

        //permission: does user have permission to view this guarantee letter
        if ($this->guaranteepermissions->check('view', $guarantee)) {
            //permission granted
            return $next($request);
        }

        Log::error("permission denied", ['process' => '[permissions][guarantee-letters][show]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'guarantee id' => $guarantee_id ?? '']);
        abort(403);
    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd($guarantee = '') {

        if (auth()->user()->is_team) {
            if ($this->guaranteepermissions->check('edit', $guarantee)) {
                config([
                    'visibility.guarantee_letter_edit_button' => true,
                    'visibility.guarantee_letter_editing_buttons' => true,
                ]);
            }
        }
    }

}

