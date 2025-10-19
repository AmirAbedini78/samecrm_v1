<?php

/** --------------------------------------------------------------------------------
 * This middleware handles the index process for the inventory
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\Inventory;

use Closure;
use Log;

class Index {

    /**
     * This middleware handles the index process for the inventory
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        //set various data and settings
        $this->setTableConfig();
        $this->setFrontend();

        //continue
        return $next($request);
    }

    /*
     * Set table configuration
     *
     *
     */
    private function setTableConfig() {

        //get current settings or create for user
        if (!$table = \App\Models\TableConfig::Where('tableconfig_userid', auth()->id())->Where('tableconfig_table_name', 'inventory')->first()) {

            //create for this user and set the visible columns (by setting them to `null`)
            $table = new \App\Models\TableConfig();
            $table->tableconfig_userid = auth()->id();
            $table->tableconfig_table_name = 'inventory';
            $table->tableconfig_column_1 = 'displayed'; //id
            $table->tableconfig_column_2 = 'displayed'; //inventory name
            $table->tableconfig_column_3 = 'displayed'; //inventory code
            $table->tableconfig_column_4 = 'displayed'; //current quantity
            $table->tableconfig_column_5 = 'displayed'; //current avg price
            $table->tableconfig_column_6 = 'displayed'; //current amount
            $table->tableconfig_column_7 = 'displayed'; //minimum stock
            $table->tableconfig_column_8 = 'hidden'; //category
            $table->tableconfig_column_9 = 'hidden'; //created by
            $table->tableconfig_column_10 = 'hidden'; //date created
            $table->tableconfig_column_11 = 'displayed'; //status
            $table->save();
        }

        //get row
        $table = \App\Models\TableConfig::Where('tableconfig_userid', auth()->id())->Where('tableconfig_table_name', 'inventory')->first();

        //default show some table columns
        config(['table' => $table]);

    }

    /*
     * various frontend and visibility settings
     */
    private function setFrontend() {

        //default show some table columns
        config([
            'visibility.inventory_col_client' => true,
            'visibility.inventory_col_category' => true,
            'visibility.filter_panel_client_project' => true,
        ]);

        //permissions -viewing
        if (auth()->user()->role->role_inventory >= 1) {
            config([
                //visibility
                'visibility.list_page_actions_filter_button' => true,
                'visibility.list_page_actions_search' => true,
                'visibility.stats_toggle_button' => true,
            ]);
        }
        if (auth()->user()->is_client) {
            config([
                //visibility
                'visibility.list_page_actions_search' => true,
                'visibility.inventory_col_client' => false,
            ]);
        }

        //permissions -adding
        if (auth()->user()->role->role_inventory >= 2) {
            config([
                //visibility
                'visibility.list_page_actions_add_button' => true,
                'visibility.action_buttons_edit' => true,
                'visibility.inventory_col_checkboxes' => true,
            ]);
        }

        //permissions -deleting
        if (auth()->user()->role->role_inventory >= 3) {
            config([
                //visibility
                'visibility.action_buttons_delete' => true,
            ]);
        }

    }

}