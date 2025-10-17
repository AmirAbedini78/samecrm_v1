<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for accounting
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\AccountingStoreValidation;
use App\Http\Responses\Accounting\CommonResponse;
use App\Http\Responses\Accounting\CreateResponse;
use App\Http\Responses\Accounting\DestroyResponse;
use App\Http\Responses\Accounting\DetailsResponse;
use App\Http\Responses\Accounting\EditResponse;
use App\Http\Responses\Accounting\IndexResponse;
use App\Http\Responses\Accounting\PinningResponse;
use App\Http\Responses\Accounting\ShowResponse;
use App\Http\Responses\Accounting\StoreResponse;
use App\Http\Responses\Accounting\UpdateResponse;
use App\Repositories\AccountingRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\TagRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Validator;

class Accounting extends Controller {

    /**
     * The users repository instance.
     */
    protected $userrepo;

    /**
     * The accounting repository instance.
     */
    protected $accountingrepo;

    /**
     * The tags repository instance.
     */
    protected $tagrepo;

    public function __construct(UserRepository $userrepo, AccountingRepository $accountingrepo, TagRepository $tagrepo) {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        $this->middleware('accountingMiddlewareIndex')->only([
            'index',
            'update',
            'store',
        ]);

        $this->middleware('accountingMiddlewareEdit')->only([
            'edit',
            'update',
        ]);

        $this->middleware('accountingMiddlewareCreate')->only([
            'create',
            'store',
        ]);

        $this->middleware('accountingMiddlewareDestroy')->only(['destroy']);

        $this->middleware('accountingMiddlewareShow')->only([
            'show',
            'details',
        ]);

        //dependencies
        $this->userrepo = $userrepo;
        $this->accountingrepo = $accountingrepo;
        $this->tagrepo = $tagrepo;

    }

    /**
     * Display a listing of accounting records
     * @param object CategoryRepository category repository
     * @return blade view | ajax view
     */
    public function index(CategoryRepository $categoryrepo) {

        //basic page settings
        $page = $this->pageSettings('accounting');

        //get accounting records
        $accounting = $this->accountingrepo->search();

        //accounting categories
        $categories = $categoryrepo->get('accounting');

        //get tags
        $tags = $this->tagrepo->getByType('accounting');

        //reponse payload
        $payload = [
            'page' => $page,
            'accounting' => $accounting,
            'categories' => $categories,
            'tags' => $tags,
        ];

        //show the view
        return new IndexResponse($payload);
    }

    /**
     * Show the form for creating a new accounting record
     * @param object CategoryRepository category repository
     * @return \Illuminate\Http\Response
     */
    public function create(CategoryRepository $categoryrepo) {

        //basic page settings
        $page = $this->pageSettings('create');

        //accounting categories
        $categories = $categoryrepo->get('accounting');

        //get tags
        $tags = $this->tagrepo->getByType('accounting');

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
     * Store a newly created accounting record in storage.
     * @param object AccountingStoreValidation validation
     * @return \Illuminate\Http\Response
     */
    public function store(AccountingStoreValidation $request) {

        //create the accounting record
        if (!$accounting_id = $this->accountingrepo->create()) {
            abort(409, __('lang.error_request_could_not_be_completed'));
        }

        //get the accounting record object (friendly for dispatching events)
        $accounting = $this->accountingrepo->search($accounting_id);

        //reponse payload
        $payload = [
            'accounting' => $accounting,
        ];

        //show the view
        return new StoreResponse($payload);
    }

    /**
     * Display the specified accounting record
     * @param int $id accounting id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {

        //get the accounting record
        $accounting = $this->accountingrepo->search($id);

        //not found
        if (!$accounting = $accounting->first()) {
            abort(404);
        }

        //basic page settings
        $page = $this->pageSettings('accounting');

        //reponse payload
        $payload = [
            'page' => $page,
            'accounting' => $accounting,
        ];

        //show the view
        return new ShowResponse($payload);
    }

    /**
     * Show the form for editing the specified accounting record
     * @param int $id accounting id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {

        //get the accounting record
        $accounting = $this->accountingrepo->search($id);

        //not found
        if (!$accounting = $accounting->first()) {
            abort(404);
        }

        //basic page settings
        $page = $this->pageSettings('edit');

        //reponse payload
        $payload = [
            'page' => $page,
            'accounting' => $accounting,
        ];

        //show the view
        return new EditResponse($payload);
    }

    /**
     * Update the specified accounting record in storage.
     * @param object AccountingStoreValidation validation
     * @param int $id accounting id
     * @return \Illuminate\Http\Response
     */
    public function update(AccountingStoreValidation $request, $id) {

        //update the accounting record
        if (!$this->accountingrepo->update($id)) {
            abort(409);
        }

        //get the accounting record object (friendly for dispatching events)
        $accounting = $this->accountingrepo->search($id);

        //reponse payload
        $payload = [
            'accounting' => $accounting,
        ];

        //show the view
        return new UpdateResponse($payload);
    }

    /**
     * Remove the specified accounting record from storage.
     * @param int $id accounting id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {

        //get the accounting record
        $accounting = $this->accountingrepo->search($id);

        //not found
        if (!$accounting = $accounting->first()) {
            abort(404);
        }

        //remove the accounting record
        $accounting->delete();

        //reponse payload
        $payload = [
            'accounting' => $accounting,
        ];

        //show the view
        return new DestroyResponse($payload);
    }

    /**
     * Show the form for editing the specified accounting record
     * @param int $id accounting id
     * @return \Illuminate\Http\Response
     */
    public function details($id) {

        //get the accounting record
        $accounting = $this->accountingrepo->search($id);

        //not found
        if (!$accounting = $accounting->first()) {
            abort(404);
        }

        //basic page settings
        $page = $this->pageSettings('accounting');

        //reponse payload
        $payload = [
            'page' => $page,
            'accounting' => $accounting,
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
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => __('lang.accounting'),
            'heading' => __('lang.accounting'),
            'mainmenu_accounting' => 'active',
        ];

        return $page;
    }

}

