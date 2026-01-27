<?php

/** --------------------------------------------------------------------------------
 * This middleware handles the index process for the BelzonaInventory
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Middleware\BelzonaInventory;

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
        if (!$table = \App\Models\TableConfig::Where('tableconfig_userid', auth()->id())->Where('tableconfig_table_name', 'belzona_inventory')->first()) {

            //create for this user and set the visible columns (by setting them to `null`)
            $table = new \App\Models\TableConfig();
            $table->tableconfig_userid = auth()->id();
            $table->tableconfig_table_name = 'belzona_inventory';
            // minimal defaults (used by some UI components)
            $table->tableconfig_column_1 = 'displayed'; //id
            $table->tableconfig_column_2 = 'displayed'; //product
            $table->tableconfig_column_3 = 'displayed'; //weight
            $table->tableconfig_column_4 = 'displayed'; //date
            $table->tableconfig_column_5 = 'displayed'; //input
            $table->tableconfig_column_6 = 'displayed'; //output
            $table->tableconfig_column_7 = 'displayed'; //balance
            $table->tableconfig_column_8 = 'displayed'; //invoice
            $table->tableconfig_column_9 = 'displayed'; //customer
            $table->tableconfig_column_10 = 'hidden'; //notes
            $table->tableconfig_column_11 = 'hidden'; //sheet
            $table->save();
        }

        //get row
        $table = \App\Models\TableConfig::Where('tableconfig_userid', auth()->id())->Where('tableconfig_table_name', 'belzona_inventory')->first();

        //default show some table columns
        config(['table' => $table]);

    }

    /*
     * various frontend and visibility settings
     */
    private function setFrontend() {

        //default show some table columns
        config([
            'visibility.belzona_inventory_col_notes' => true,
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