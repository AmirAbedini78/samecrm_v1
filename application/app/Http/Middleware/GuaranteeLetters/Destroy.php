<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [destroy] precheck processes for guarantee letters
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\GuaranteeLetters;
use App\Models\GuaranteeLetter;
use App\Permissions\GuaranteeLetterPermissions;
use Closure;
use Log;

class Destroy {

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
     * This 'bulk actions' middleware does the following
     *   1. If the request was for a single item
     *         - single item actions must have a query string '?id=123'
     *         - this id will be merged into the expected 'ids' request array (just as if it was a bulk request)
     *   2. loop through all the 'ids' that are in the post request
     *
     * HTML for the checkbox is expected to be in this format:
     *   <input type="checkbox" name="ids[{{ $guarantee->guarantee_id }}]"
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

        //for a single item request - merge into an $ids[x] array and set as if checkbox is selected (on)
        if (is_numeric($request->route('guarantee'))) {
            $ids[$request->route('guarantee')] = 'on';
            request()->merge([
                'ids' => $ids,
            ]);
        }

        //loop through each guarantee letter and check permissions
        if (is_array(request('ids'))) {

            //validate each item in the list exists
            foreach (request('ids') as $id => $value) {
                //only checked items
                if ($value == 'on') {
                    //validate
                    if (!$guarantee = \App\Models\GuaranteeLetter::Where('guarantee_id', $id)->first()) {
                        abort(409, __('lang.one_of_the_selected_items_nolonger_exists'));
                    }
                }
                //permission on each one
                if (!$this->guaranteepermissions->check('delete', $id)) {
                    abort(403, __('lang.permission_denied_for_this_item') . " - #$id");
                }
            }
            //client - no permissions
            if (auth()->user()->is_client) {
                abort(403);
            }
        } else {
            //no items were passed with this request
            Log::error("no items were sent with this request", ['process' => '[permissions][guarantee-letters][destroy]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            abort(409);
        }

        //all is on - passed
        return $next($request);
    }
}

