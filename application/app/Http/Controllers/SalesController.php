<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for sales
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sales\SalesStoreValidation;
use App\Http\Responses\Sales\CommonResponse;
use App\Http\Responses\Sales\CreateResponse;
use App\Models\Sales;
use App\Http\Responses\Sales\DestroyResponse;
use App\Http\Responses\Sales\DetailsResponse;
use App\Http\Responses\Sales\EditResponse;
use App\Http\Responses\Sales\IndexResponse;
use App\Http\Responses\Sales\PinningResponse;
use App\Http\Responses\Sales\ShowResponse;
use App\Http\Responses\Sales\StoreResponse;
use App\Http\Responses\Sales\UpdateResponse;
use App\Repositories\SalesRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\TagRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Validator;

class SalesController extends Controller {

    /**
     * The users repository instance.
     */
    protected $userrepo;

    /**
     * The sales repository instance.
     */
    protected $salesrepo;

    /**
     * The tags repository instance.
     */
    protected $tagrepo;

    public function __construct(UserRepository $userrepo, SalesRepository $salesrepo, TagRepository $tagrepo) {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        // Module-specific middleware can be added here if needed

        //dependencies
        $this->userrepo = $userrepo;
        $this->salesrepo = $salesrepo;
        $this->tagrepo = $tagrepo;

    }

    /**
     * Display a listing of sales records
     * @param object CategoryRepository category repository
     * @return blade view | ajax view
     */
    public function index(CategoryRepository $categoryrepo) {

        //basic page settings
        $page = $this->pageSettings('sales');

        //get sales records
        $sales = $this->salesrepo->search();

        //sales categories
        $categories = $categoryrepo->get('sales');

        //get tags
        $tags = $this->tagrepo->getByType('sales');

        //calculate stats
        $stats = [
            'total_sales' => $sales->total(),
            'completed_sales' => Sales::where('sales_status', 'completed')->count(),
            'pending_sales' => Sales::where('sales_status', 'pending')->count(),
            'total_revenue' => Sales::sum('base_net_amount') ?? 0,
        ];

        //reponse payload
        $payload = [
            'page' => $page,
            'sales' => $sales,
            'categories' => $categories,
            'tags' => $tags,
            'stats' => $stats,
        ];

        //show the view
        return new IndexResponse($payload);
    }

    /**
     * Show the form for creating a new sales record
     * @param object CategoryRepository category repository
     * @return \Illuminate\Http\Response
     */
    public function create(CategoryRepository $categoryrepo) {

        //basic page settings
        $page = $this->pageSettings('create');

        //sales categories
        $categories = $categoryrepo->get('sales');

        //get tags
        $tags = $this->tagrepo->getByType('sales');

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
     * Store a newly created sales record in storage.
     * @param object SalesStoreValidation validation
     * @return \Illuminate\Http\Response
     */
    public function store(SalesStoreValidation $request) {

        //create the sales record
        if (!$sales_id = $this->salesrepo->create()) {
            abort(409, __('lang.error_request_could_not_be_completed'));
        }

        //get the sales record object (friendly for dispatching events)
        $sales = $this->salesrepo->search($sales_id);

        //reponse payload
        $payload = [
            'sales' => $sales,
        ];

        //show the view
        return new StoreResponse($payload);
    }

    /**
     * Display the specified sales record
     * @param int $id sales id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {

        //get the sales record
        $sales = $this->salesrepo->search($id);

        //not found
        if (!$sales = $sales->first()) {
            abort(404);
        }

        //basic page settings
        $page = $this->pageSettings('sales');

        //reponse payload
        $payload = [
            'page' => $page,
            'sales' => $sales,
        ];

        //show the view
        return new ShowResponse($payload);
    }

    /**
     * Show the form for editing the specified sales record
     * @param int $id sales id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {

        //get the sales record
        $sales = $this->salesrepo->search($id);

        //not found
        if (!$sales = $sales->first()) {
            abort(404);
        }

        //basic page settings
        $page = $this->pageSettings('edit');

        //reponse payload
        $payload = [
            'page' => $page,
            'sales' => $sales,
        ];

        //show the view
        return new EditResponse($payload);
    }

    /**
     * Update the specified sales record in storage.
     * @param object SalesStoreValidation validation
     * @param int $id sales id
     * @return \Illuminate\Http\Response
     */
    public function update(SalesStoreValidation $request, $id) {

        //update the sales record
        if (!$this->salesrepo->update($id)) {
            abort(409);
        }

        //get the sales record object (friendly for dispatching events)
        $sales = $this->salesrepo->search($id);

        //reponse payload
        $payload = [
            'sales' => $sales,
        ];

        //show the view
        return new UpdateResponse($payload);
    }

    /**
     * Remove the specified sales record from storage.
     * @param int $id sales id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {

        //get the sales record
        $sales = $this->salesrepo->search($id);

        //not found
        if (!$sales = $sales->first()) {
            abort(404);
        }

        //remove the sales record
        $sales->delete();

        //reponse payload
        $payload = [
            'sales' => $sales,
        ];

        //show the view
        return new DestroyResponse($payload);
    }

    /**
     * Show the form for editing the specified sales record
     * @param int $id sales id
     * @return \Illuminate\Http\Response
     */
    public function details($id) {

        //get the sales record
        $sales = $this->salesrepo->search($id);

        //not found
        if (!$sales = $sales->first()) {
            abort(404);
        }

        //basic page settings
        $page = $this->pageSettings('sales');

        //reponse payload
        $payload = [
            'page' => $page,
            'sales' => $sales,
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
                __('lang.sales'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => __('lang.sales'),
            'heading' => __('lang.sales'),
            'mainmenu_accounting' => 'active',
        ];

        return $page;
    }

}
