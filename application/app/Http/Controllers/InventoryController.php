<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for inventory
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\InventoryStoreValidation;
use App\Http\Responses\Inventory\CommonResponse;
use App\Http\Responses\Inventory\CreateResponse;
use App\Models\Inventory;
use App\Http\Responses\Inventory\DestroyResponse;
use App\Http\Responses\Inventory\DetailsResponse;
use App\Http\Responses\Inventory\EditResponse;
use App\Http\Responses\Inventory\IndexResponse;
use App\Http\Responses\Inventory\PinningResponse;
use App\Http\Responses\Inventory\ShowResponse;
use App\Http\Responses\Inventory\StoreResponse;
use App\Http\Responses\Inventory\UpdateResponse;
use App\Repositories\InventoryRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\TagRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Validator;

class InventoryController extends Controller {

    /**
     * The users repository instance.
     */
    protected $userrepo;

    /**
     * The inventory repository instance.
     */
    protected $inventoryrepo;

    /**
     * The tags repository instance.
     */
    protected $tagrepo;

    public function __construct(UserRepository $userrepo, InventoryRepository $inventoryrepo, TagRepository $tagrepo) {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        // Module-specific middleware can be added here if needed

        //dependencies
        $this->userrepo = $userrepo;
        $this->inventoryrepo = $inventoryrepo;
        $this->tagrepo = $tagrepo;

    }

    /**
     * Display a listing of inventory records
     * @param object CategoryRepository category repository
     * @return blade view | ajax view
     */
    public function index(CategoryRepository $categoryrepo) {

        //basic page settings
        $page = $this->pageSettings('inventory');

        //get inventory records
        $inventory = $this->inventoryrepo->search();

        //inventory categories
        $categories = $categoryrepo->get('inventory');

        //get tags
        $tags = $this->tagrepo->getByType('inventory');

        //calculate stats
        $stats = [
            'total_items' => $inventory->total(),
            'active_items' => Inventory::where('inventory_status', 'active')->count(),
            'low_stock' => Inventory::whereRaw('current_quantity <= minimum_stock')->count(),
            'total_value' => Inventory::sum('current_amount') ?? 0,
        ];

        //reponse payload
        $payload = [
            'page' => $page,
            'inventory' => $inventory,
            'categories' => $categories,
            'tags' => $tags,
            'stats' => $stats,
        ];

        //show the view
        return new IndexResponse($payload);
    }

    /**
     * Show the form for creating a new inventory record
     * @param object CategoryRepository category repository
     * @return \Illuminate\Http\Response
     */
    public function create(CategoryRepository $categoryrepo) {

        //basic page settings
        $page = $this->pageSettings('create');

        //inventory categories
        $categories = $categoryrepo->get('inventory');

        //get tags
        $tags = $this->tagrepo->getByType('inventory');

        //reponse payload
        $payload = [
            'page' => $page,
            'categories' => $categories,
            'tags' => $tags,
        ];

        //show the view
        return new CreateResponse($payload);
    }

    /**
     * Store a newly created inventory record in storage.
     * @param object InventoryStoreValidation validation
     * @return \Illuminate\Http\Response
     */
    public function store(InventoryStoreValidation $request) {

        //create the inventory record
        if (!$inventory_id = $this->inventoryrepo->create()) {
            abort(409, __('lang.error_request_could_not_be_completed'));
        }

        //get the inventory record object (friendly for dispatching events)
        $inventory = $this->inventoryrepo->search($inventory_id);

        //reponse payload
        $payload = [
            'inventory' => $inventory,
        ];

        //show the view
        return new StoreResponse($payload);
    }

    /**
     * Display the specified inventory record
     * @param int $id inventory id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {

        //get the inventory record
        $inventory = $this->inventoryrepo->search($id);

        //not found
        if (!$inventory = $inventory->first()) {
            abort(404);
        }

        //basic page settings
        $page = $this->pageSettings('inventory');

        //reponse payload
        $payload = [
            'page' => $page,
            'inventory' => $inventory,
        ];

        //show the view
        return new ShowResponse($payload);
    }

    /**
     * Show the form for editing the specified inventory record
     * @param int $id inventory id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {

        //get the inventory record
        $inventory = $this->inventoryrepo->search($id);

        //not found
        if (!$inventory = $inventory->first()) {
            abort(404);
        }

        //basic page settings
        $page = $this->pageSettings('edit');

        //reponse payload
        $payload = [
            'page' => $page,
            'inventory' => $inventory,
        ];

        //show the view
        return new EditResponse($payload);
    }

    /**
     * Update the specified inventory record in storage.
     * @param object InventoryStoreValidation validation
     * @param int $id inventory id
     * @return \Illuminate\Http\Response
     */
    public function update(InventoryStoreValidation $request, $id) {

        //update the inventory record
        if (!$this->inventoryrepo->update($id)) {
            abort(409);
        }

        //get the inventory record object (friendly for dispatching events)
        $inventory = $this->inventoryrepo->search($id);

        //reponse payload
        $payload = [
            'inventory' => $inventory,
        ];

        //show the view
        return new UpdateResponse($payload);
    }

    /**
     * Remove the specified inventory record from storage.
     * @param int $id inventory id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {

        //get the inventory record
        $inventory = $this->inventoryrepo->search($id);

        //not found
        if (!$inventory = $inventory->first()) {
            abort(404);
        }

        //remove the inventory record
        $inventory->delete();

        //reponse payload
        $payload = [
            'inventory' => $inventory,
        ];

        //show the view
        return new DestroyResponse($payload);
    }

    /**
     * Show the form for editing the specified inventory record
     * @param int $id inventory id
     * @return \Illuminate\Http\Response
     */
    public function details($id) {

        //get the inventory record
        $inventory = $this->inventoryrepo->search($id);

        //not found
        if (!$inventory = $inventory->first()) {
            abort(404);
        }

        //basic page settings
        $page = $this->pageSettings('inventory');

        //reponse payload
        $payload = [
            'page' => $page,
            'inventory' => $inventory,
        ];

        //show the view
        return new DetailsResponse($payload);
    }

    /**
     * basic page settings for this section of the app
     * @param string $section name
     * @param array $data any other data
     * @return array
     */
    private function pageSettings($section = '', $data = []) {

        $page = [
            'page' => $section,
            'crumbs' => [
                __('lang.accounting'),
                __('lang.inventory'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => __('lang.inventory'),
            'heading' => __('lang.inventory'),
            'mainmenu_accounting' => 'active',
        ];

        return $page;
    }

}
