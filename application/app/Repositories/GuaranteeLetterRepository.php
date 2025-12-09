<?php

namespace App\Repositories;

use App\Models\GuaranteeLetter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class GuaranteeLetterRepository {

    /**
     * The guarantee letter repository instance.
     */
    protected $guaranteeletter;

    public function __construct(GuaranteeLetter $guaranteeletter) {
        $this->guaranteeletter = $guaranteeletter;
    }

    /**
     * get guarantee letter records
     * @param string $id optional id of the record
     * @return object
     */
    public function search($id = '') {

        $guarantees = $this->guaranteeletter->newQuery();

        //filter by id
        if (is_numeric($id)) {
            $guarantees->where('guarantee_id', $id);
            // For single record, return the query builder without pagination
            $guarantees->with(['creator', 'assignedUser', 'assignments.user', 'tags', 'notifications']);
            return $guarantees;
        }

        //filter by guarantee type
        if (request()->filled('filter_guarantee_type')) {
            $guarantees->where('guarantee_type', request('filter_guarantee_type'));
        }

        //filter by industrial type
        if (request()->filled('filter_industrial_type')) {
            $guarantees->where('industrial_type', request('filter_industrial_type'));
        }

        //filter by status
        if (request()->filled('filter_status')) {
            $guarantees->where('status', request('filter_status'));
        }

        //filter by assigned user
        if (request()->filled('filter_assigned_user_id')) {
            $guarantees->where('assigned_user_id', request('filter_assigned_user_id'));
        }

        //filter by creator
        if (request()->filled('filter_guarantee_creatorid')) {
            $guarantees->where('guarantee_creatorid', request('filter_guarantee_creatorid'));
        }

        //filter by issue date from
        if (request()->filled('filter_issue_date_from')) {
            $guarantees->where('issue_date', '>=', request('filter_issue_date_from'));
        }

        //filter by issue date to
        if (request()->filled('filter_issue_date_to')) {
            $guarantees->where('issue_date', '<=', request('filter_issue_date_to'));
        }

        //filter by expiry date from
        if (request()->filled('filter_expiry_date_from')) {
            $guarantees->where('expiry_date', '>=', request('filter_expiry_date_from'));
        }

        //filter by expiry date to
        if (request()->filled('filter_expiry_date_to')) {
            $guarantees->where('expiry_date', '<=', request('filter_expiry_date_to'));
        }

        //filter by assigned users (check assignments table)
        if (request()->filled('filter_assigned_users')) {
            $userIds = is_array(request('filter_assigned_users')) 
                ? request('filter_assigned_users') 
                : [request('filter_assigned_users')];
            $guarantees->whereHas('assignments', function ($query) use ($userIds) {
                $query->whereIn('user_id', $userIds);
            });
        }

        //search: multiple guarantee letter fields
        if (request()->filled('search_query')) {
            $guarantees->where(function ($query) {
                $query->where('guarantee_number', 'LIKE', '%' . request('search_query') . '%')
                    ->orWhere('issuing_bank', 'LIKE', '%' . request('search_query') . '%')
                    ->orWhere('beneficiary', 'LIKE', '%' . request('search_query') . '%')
                    ->orWhere('description', 'LIKE', '%' . request('search_query') . '%');
            });
        }
        
        //search: general search (support DataTables format: search[value])
        $globalSearch = request('search');
        if (is_array($globalSearch)) {
            $globalSearch = $globalSearch['value'] ?? '';
        }
        if (!empty($globalSearch)) {
            $guarantees->where(function ($query) use ($globalSearch) {
                $query->where('guarantee_number', 'LIKE', '%' . $globalSearch . '%')
                    ->orWhere('issuing_bank', 'LIKE', '%' . $globalSearch . '%')
                    ->orWhere('beneficiary', 'LIKE', '%' . $globalSearch . '%')
                    ->orWhere('description', 'LIKE', '%' . $globalSearch . '%');
            });
        }
        
        //column-specific search
        foreach (request()->all() as $key => $value) {
            if (strpos($key, 'column_search_') === 0) {
                $column = str_replace('column_search_', '', $key);
                
                // Only apply search if value is not empty
                if (!empty($value)) {
                    // Decode URL encoded values
                    $value = urldecode($value);
                    
                    // Handle different column types
                    switch ($column) {
                        case 'creator':
                            $guarantees->whereHas('creator', function ($query) use ($value) {
                                $query->where('first_name', 'LIKE', '%' . $value . '%')
                                      ->orWhere('last_name', 'LIKE', '%' . $value . '%');
                            });
                            break;
                        case 'assigned_user':
                            $guarantees->whereHas('assignedUser', function ($query) use ($value) {
                                $query->where('first_name', 'LIKE', '%' . $value . '%')
                                      ->orWhere('last_name', 'LIKE', '%' . $value . '%');
                            });
                            break;
                        case 'tags':
                            $guarantees->whereHas('tags', function ($query) use ($value) {
                                $query->where('tag_title', 'LIKE', '%' . $value . '%');
                            });
                            break;
                        default:
                            // Direct column search
                            if (Schema::hasColumn('guarantee_letters', $column)) {
                                $guarantees->where($column, 'LIKE', '%' . $value . '%');
                            }
                            break;
                    }
                }
            }
        }

        //sorting
        if (in_array(request('sortorder'), array('desc', 'asc')) && request('orderby') != '') {
            $guarantees->orderBy(request('orderby'), request('sortorder'));
        } else {
            $guarantees->orderBy('created_at', 'desc');
        }

        //eager load
        $guarantees->with([
            'creator',
            'assignedUser',
            'assignments.user',
            'tags',
        ]);

        //return paginated results
        return $guarantees->paginate(config('system.settings_system_pagination_limits'));
    }

    /**
     * create a new record
     * @param int $id optional id of the record
     * @return mixed int|bool
     */
    public function create($id = '') {

        //save new guarantee letter
        $guarantee = new $this->guaranteeletter;

        //data
        $guarantee->guarantee_number = request('guarantee_number');
        $guarantee->guarantee_type = request('guarantee_type');
        $guarantee->industrial_type = request('industrial_type');
        $guarantee->issue_date = request('issue_date');
        $guarantee->expiry_date = request('expiry_date');
        $guarantee->renewal_date = request('renewal_date');
        $guarantee->settlement_date = request('settlement_date');
        $guarantee->amount = request('amount', 0);
        $guarantee->currency = request('currency', 'IRR');
        $guarantee->issuing_bank = request('issuing_bank');
        $guarantee->beneficiary = request('beneficiary');
        $guarantee->status = request('status', 'active');
        $guarantee->assigned_user_id = request('assigned_user_id');
        $guarantee->description = request('description');
        $guarantee->guarantee_creatorid = auth()->id();

        //save and return id
        if ($guarantee->save()) {
            // Create assignment if user is assigned
            if ($guarantee->assigned_user_id) {
                \App\Models\GuaranteeLetterAssignment::create([
                    'guarantee_id' => $guarantee->guarantee_id,
                    'user_id' => $guarantee->assigned_user_id,
                    'assigned_at' => now(),
                    'assigned_by' => auth()->id(),
                ]);
            }
            return $guarantee->guarantee_id;
        } else {
            Log::error("record could not be created - database error", ['process' => '[GuaranteeLetterRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }

    /**
     * update a record
     * @param int $id record id
     * @return mixed int|bool
     */
    public function update($id) {

        //get the record
        if (!$guarantee = $this->guaranteeletter->find($id)) {
            Log::error("record could not be found - database error", ['process' => '[GuaranteeLetterRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }

        //data
        $guarantee->guarantee_number = request('guarantee_number');
        $guarantee->guarantee_type = request('guarantee_type');
        $guarantee->industrial_type = request('industrial_type');
        $guarantee->issue_date = request('issue_date');
        $guarantee->expiry_date = request('expiry_date');
        $guarantee->renewal_date = request('renewal_date');
        $guarantee->settlement_date = request('settlement_date');
        $guarantee->amount = request('amount', 0);
        $guarantee->currency = request('currency', 'IRR');
        $guarantee->issuing_bank = request('issuing_bank');
        $guarantee->beneficiary = request('beneficiary');
        $guarantee->status = request('status', 'active');
        $guarantee->description = request('description');

        // Handle assignment change
        $oldAssignedUserId = $guarantee->assigned_user_id;
        $newAssignedUserId = request('assigned_user_id');
        $guarantee->assigned_user_id = $newAssignedUserId;

        //save and return id
        if ($guarantee->save()) {
            // Update assignment if user changed
            if ($oldAssignedUserId != $newAssignedUserId) {
                // Remove old assignment
                if ($oldAssignedUserId) {
                    \App\Models\GuaranteeLetterAssignment::where('guarantee_id', $guarantee->guarantee_id)
                        ->where('user_id', $oldAssignedUserId)
                        ->delete();
                }
                // Create new assignment
                if ($newAssignedUserId) {
                    \App\Models\GuaranteeLetterAssignment::create([
                        'guarantee_id' => $guarantee->guarantee_id,
                        'user_id' => $newAssignedUserId,
                        'assigned_at' => now(),
                        'assigned_by' => auth()->id(),
                    ]);
                }
            }
            return $guarantee->guarantee_id;
        } else {
            Log::error("record could not be updated - database error", ['process' => '[GuaranteeLetterRepository]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__]);
            return false;
        }
    }

    /**
     * Get unique values for a specific column
     * @param string $column
     * @return array
     */
    public function getUniqueValues($column) {
        $guarantees = new GuaranteeLetter();
        
        // Handle different column types
        switch ($column) {
            case 'creator':
                $values = $guarantees->join('users', 'guarantee_letters.guarantee_creatorid', '=', 'users.id')
                    ->select('users.first_name', 'users.last_name')
                    ->distinct()
                    ->whereNotNull('users.first_name')
                    ->where('users.first_name', '!=', '')
                    ->get()
                    ->map(function($user) {
                        return $user->first_name . ' ' . $user->last_name;
                    })
                    ->toArray();
                break;
            case 'assigned_user':
                $values = $guarantees->join('users', 'guarantee_letters.assigned_user_id', '=', 'users.id')
                    ->select('users.first_name', 'users.last_name')
                    ->distinct()
                    ->whereNotNull('users.first_name')
                    ->where('users.first_name', '!=', '')
                    ->get()
                    ->map(function($user) {
                        return $user->first_name . ' ' . $user->last_name;
                    })
                    ->toArray();
                break;
            default:
                // Direct column search
                if (Schema::hasColumn('guarantee_letters', $column)) {
                    $values = $guarantees->select($column)
                        ->distinct()
                        ->whereNotNull($column)
                        ->where($column, '!=', '')
                        ->pluck($column)
                        ->toArray();
                } else {
                    $values = [];
                }
                break;
        }
        
        // Sort values and return
        sort($values);
        return array_values(array_unique($values));
    }

    /**
     * Calculate stats for filtered guarantee letters
     * @return array
     */
    public function calculateStats() {
        $guarantees = $this->guaranteeletter->newQuery();
        
        // Apply same filters as search method
        if (request()->filled('filter_guarantee_type')) {
            $guarantees->where('guarantee_type', request('filter_guarantee_type'));
        }

        if (request()->filled('filter_industrial_type')) {
            $guarantees->where('industrial_type', request('filter_industrial_type'));
        }

        if (request()->filled('filter_status')) {
            $guarantees->where('status', request('filter_status'));
        }

        if (request()->filled('filter_assigned_user_id')) {
            $guarantees->where('assigned_user_id', request('filter_assigned_user_id'));
        }

        if (request()->filled('filter_issue_date_from')) {
            $guarantees->where('issue_date', '>=', request('filter_issue_date_from'));
        }

        if (request()->filled('filter_issue_date_to')) {
            $guarantees->where('issue_date', '<=', request('filter_issue_date_to'));
        }

        if (request()->filled('search_query')) {
            $guarantees->where(function ($query) {
                $query->where('guarantee_number', 'LIKE', '%' . request('search_query') . '%')
                    ->orWhere('issuing_bank', 'LIKE', '%' . request('search_query') . '%')
                    ->orWhere('beneficiary', 'LIKE', '%' . request('search_query') . '%');
            });
        }
        
        // Get stats
        $stats = $guarantees->selectRaw('
            COUNT(*) as total_count,
            COALESCE(SUM(amount), 0) as total_amount,
            SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as active_count,
            SUM(CASE WHEN status = "expired" THEN 1 ELSE 0 END) as expired_count,
            SUM(CASE WHEN expiry_date < CURDATE() AND status = "active" THEN 1 ELSE 0 END) as expired_but_active_count
        ')->first();
        
        return [
            'total_count' => (int) $stats->total_count,
            'total_amount' => (float) $stats->total_amount,
            'active_count' => (int) $stats->active_count,
            'expired_count' => (int) $stats->expired_count,
            'expired_but_active_count' => (int) $stats->expired_but_active_count,
        ];
    }
}

