<?php

/** --------------------------------------------------------------------------------
 * This middleware class handles [index] precheck processes for guarantee letters
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\GuaranteeLetters;

use App\Models\GuaranteeLetter;
use Closure;
use Log;

class Index {

    /**
     * This middleware does the following
     *   2. checks users permissions to [view] guarantee letters
     *   3. modifies the request object as needed
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

        //table config
        $this->tableConfig();

        //various frontend and visibility settings
        $this->fronteEnd();

        //admin user permission - admin always has access
        if (auth()->user()->is_team) {
            //admin (role_id == 1) always has full access
            if (auth()->user()->role_id == 1) {
                //toggle 'my guarantee letters' button options
                $this->toggleOwnFilter();
                return $next($request);
            }
            //other team users need permission
            if (auth()->user()->role->role_guarantee_letters >= 1) {
                //[limit] - for users with only local level scope
                if (auth()->user()->role->role_guarantee_letters_scope == 'own') {
                    request()->merge(['filter_assigned_user_id' => auth()->id()]);
                }
                //toggle 'my guarantee letters' button options
                $this->toggleOwnFilter();
                return $next($request);
            }
        }

        //client user
        if (auth()->user()->is_client) {
            abort(403);
        }

        //permission denied
        Log::error("permission denied", ['process' => '[permissions][guarantee-letters][index]', 'ref' => config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
        abort(403);
    }

    /*
     * Set the users tables column visibility preferences
     * 
     * @tablename - guarantee_letters
     */
    private function tableConfig() {

        //get current settings or create for user
        if (!$table = \App\Models\TableConfig::Where('tableconfig_userid', auth()->id())->Where('tableconfig_table_name', 'guarantee_letters')->first()) {

            //create for this user and set the visible columns
            $table = new \App\Models\TableConfig();
            $table->tableconfig_userid = auth()->id();
            $table->tableconfig_table_name = 'guarantee_letters';
            $table->tableconfig_column_1 = 'displayed';
            $table->tableconfig_column_2 = 'displayed';
            $table->tableconfig_column_3 = 'displayed';
            $table->tableconfig_column_4 = 'displayed';
            $table->tableconfig_column_5 = 'displayed';
            $table->tableconfig_column_6 = 'displayed';
            $table->tableconfig_column_7 = 'displayed';
            $table->save();
        }

        //get row
        $table = \App\Models\TableConfig::Where('tableconfig_userid', auth()->id())->Where('tableconfig_table_name', 'guarantee_letters')->first();

        //default show some table columns
        config(['table' => $table]);

    }

    /*
     * various frontend and visibility settings
     */
    private function fronteEnd() {

        //default show some table columns
        config([
            'visibility.guarantee_letters_col_checkboxes' => true,
        ]);

        request()->merge([
            'resource_query' => 'ref=list',
        ]);

        //admin always has full access
        if (auth()->user()->role_id == 1) {
            config([
                //visibility
                'visibility.list_page_actions_filter_button' => true,
                'visibility.list_page_actions_search' => true,
                'visibility.list_page_actions_add_button' => true,
                'visibility.action_buttons_edit' => true,
                'visibility.action_buttons_delete' => true,
                'visibility.guarantee_letters_col_checkboxes' => true,
                'visibility.guarantee_letters_checkboxes' => true,
            ]);
        } else {
            //permissions -viewing
            if (auth()->user()->role->role_guarantee_letters >= 1) {
                if (auth()->user()->is_team) {
                    config([
                        //visibility
                        'visibility.list_page_actions_filter_button' => true,
                        'visibility.list_page_actions_search' => true,
                    ]);
                }
            }

            //permissions -adding
            if (auth()->user()->role->role_guarantee_letters >= 2) {
                config([
                    //visibility
                    'visibility.list_page_actions_add_button' => true,
                    'visibility.action_buttons_edit' => true,
                    'visibility.guarantee_letters_col_checkboxes' => true,
                ]);
            }

            //permissions -deleting
            if (auth()->user()->role->role_guarantee_letters >= 3) {
                config([
                    //visibility
                    'visibility.action_buttons_delete' => true,
                    'visibility.guarantee_letters_checkboxes' => true,
                ]);
            }
        }

        //visibility of 'filter assigned" in filter panel
        if (auth()->user()->is_team) {
            //admin always has global scope
            if (auth()->user()->role_id == 1 || (isset(auth()->user()->role->role_guarantee_letters_scope) && auth()->user()->role->role_guarantee_letters_scope == 'global')) {
                config([
                    //visibility
                    'visibility.filter_panel_assigned' => true,
                ]);
            }
        }

        //importing and exporting
        config([
            'visibility.list_page_actions_exporting' => (auth()->user()->role->role_content_export == 'yes') ? true : false,
            'visibility.list_page_actions_importing' => (auth()->user()->role->role_content_import == 'yes') ? true : false,
        ]);

    }

    function toggleOwnFilter() {

        //visibility of 'my guarantee letters" button - only users with global scope need this button
        //admin always has global scope
        if (auth()->user()->role_id == 1 || (isset(auth()->user()->role->role_guarantee_letters_scope) && auth()->user()->role->role_guarantee_letters_scope == 'global')) {
            config([
                //visibility
                'visibility.own_guarantee_letters_toggle_button' => true,
            ]);
        }

        //update 'own guarantee letters filter'
        if (request('toggle') == 'pref_filter_own_guarantee_letters') {
            //toggle database settings
            auth()->user()->pref_filter_own_guarantee_letters = (auth()->user()->pref_filter_own_guarantee_letters == 'yes') ? 'no' : 'yes';
            auth()->user()->save();
        }

        //a filter panel search has been done with assigned - so reset 'my guarantee letters' to 'no'
        if (request()->filled('filter_assigned_user_id')) {
            if (auth()->user()->pref_filter_own_guarantee_letters == 'yes') {
                auth()->user()->pref_filter_own_guarantee_letters = 'no';
                auth()->user()->save();
            }
        }

        //set
        if (auth()->user()->pref_filter_own_guarantee_letters == 'yes') {
            request()->merge(['filter_assigned_user_id' => auth()->id()]);
        }

    }
}

