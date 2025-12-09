<?php

namespace App\Permissions;

use App\Repositories\GuaranteeLetterRepository;
use Illuminate\Support\Facades\Log;

class GuaranteeLetterPermissions {

    /**
     * The repository instance.
     */
    protected $guaranteerepo;

    /**
     * Inject dependecies
     */
    public function __construct(GuaranteeLetterRepository $guaranteerepo) {
        $this->guaranteerepo = $guaranteerepo;
    }

    /**
     * The array of checks that are available.
     * NOTE: when a new check is added, you must also add it to this array
     */
    public function permissionChecksArray() {
        $checks = [
            'view',
            'edit',
            'delete',
            'users',
            'assigned',
        ];
        return $checks;
    }

    /**
     * This method checks a users permissions for a particular, specified guarantee letter ONLY.
     *
     * [EXAMPLE USAGE]
     *          if (!$this->guaranteepermissions->check('delete', $guarantee->guarantee_id)) {
     *                 abort(413)
     *          }
     *
     * @param numeric $resource id of the resource
     * @param string $action [required] intended action on the resource see list above
     * @param mixed $guarantee can be the guarantee id or the actual guarantee object. [IMPORTANT]: passed guarantee object must from guaranteerepo->search()
     * @return bool true if user has permission
     */
    public function check($action = '', $guarantee = '') {

        //VALIDATION
        if (!in_array($action, $this->permissionChecksArray())) {
            Log::info("the requested check is invalid", ['process' => '[permissions][guarantee-letter]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'check' => $action ?? '']);
            return false;
        }

        //GET THE RESOURCE
        if (is_numeric($guarantee)) {
            if (!$guarantee = \App\Models\GuaranteeLetter::Where('guarantee_id', $guarantee)->first()) {
                Log::error("the guarantee letter could not be found", ['process' => '[permissions][guarantee-letter]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'guarantee_id' => $guarantee ?? '']);
                return false;
            }
        }

        //[IMPORTANT]: any passed guarantee object must from guaranteerepo->search() method, not the guarantee model
        if ($guarantee instanceof \App\Models\GuaranteeLetter || $guarantee instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            //array of assigned users from many-to-many relationship
            $assigned_users = $guarantee->assignments->pluck('user_id');
            // Also include the direct assigned_user_id if exists
            if (isset($guarantee->assigned_user_id) && $guarantee->assigned_user_id) {
                $assigned_users = $assigned_users->merge([$guarantee->assigned_user_id])->unique();
            }
        } else {
            Log::error("the guarantee letter could not be found", ['process' => '[permissions][guarantee-letter]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

        /**
         * [ARRAY OF USERS (with view level permissions)]
         * [NOTES] this must have the same logic as $action == 'view' below
         */
        if ($action == 'users') {
            $list = [];
            $users = \App\Models\User::with('role')->get();
            foreach ($users as $user) {
                if ($user->type == 'team' && isset($user->role->role_guarantee_letters)) {
                    if ($user->id > 0) {
                        //creator of the content
                        if ($guarantee->guarantee_creatorid == $user->id) {
                            $list[] = $user->id;
                            continue;
                        }
                        //global user
                        if ($user->role->role_guarantee_letters >= 1 && $user->role->role_guarantee_letters_scope == 'global') {
                            $list[] = $user->id;
                            continue;
                        }
                        //assigned
                        if ($assigned_users->contains($user->id)) {
                            $list[] = $user->id;
                            continue;
                        }
                    }
                }
            }
            return $list;
        }

        /**
         * [ADMIN]
         * Grant full permission for whatever request
         *
         */
        if (auth()->user()->role_id == 1) {
            return true;
        }

        /**
         * Check is logged in user is assigned to this guarantee letter
         */
        if ($action == 'assigned') {
            if ($assigned_users->contains(auth()->id())) {
                return true;
            }
        }

        /**
         * [CLIENT]
         * Deny all clients
         *
         */
        if (auth()->user()->is_client) {
            return false;
        }

        /**
         * [VIEW A GUARANTEE LETTER]
         */
        if ($action == 'view') {
            //creator
            if ($guarantee->guarantee_creatorid == auth()->id()) {
                return true;
            }
            //global user
            if (auth()->user()->role->role_guarantee_letters >= 1 && auth()->user()->role->role_guarantee_letters_scope == 'global') {
                return true;
            }
            //assigned
            if ($assigned_users->contains(auth()->id())) {
                return true;
            }
        }

        /**
         * [EDITING A GUARANTEE LETTER]
         */
        if ($action == 'edit') {
            //creator
            if ($guarantee->guarantee_creatorid == auth()->id()) {
                return true;
            }
            //global user
            if (auth()->user()->role->role_guarantee_letters >= 2 && auth()->user()->role->role_guarantee_letters_scope == 'global') {
                return true;
            }
            //assigned
            if ($assigned_users->contains(auth()->id())) {
                return true;
            }
        }

        /**
         * [DELETING A GUARANTEE LETTER]
         */
        if ($action == 'delete') {
            //creator
            if ($guarantee->guarantee_creatorid == auth()->id()) {
                return true;
            }
            //global user
            if (auth()->user()->role->role_guarantee_letters >= 3 && auth()->user()->role->role_guarantee_letters_scope == 'global') {
                return true;
            }
        }

        //passed
        Log::info("user does not have the requested permission level ($action) for this guarantee letter", ['process' => '[permissions][guarantee-letter]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'check' => $action ?? '']);
        return false;
    }

}

