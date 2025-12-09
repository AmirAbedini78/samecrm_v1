<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for guarantee letters
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Responses\GuaranteeLetters\CreateResponse;
use App\Http\Responses\GuaranteeLetters\DestroyResponse;
use App\Http\Responses\GuaranteeLetters\EditResponse;
use App\Http\Responses\GuaranteeLetters\IndexResponse;
use App\Http\Responses\GuaranteeLetters\ShowResponse;
use App\Http\Responses\GuaranteeLetters\StoreResponse;
use App\Http\Responses\GuaranteeLetters\UpdateResponse;
use App\Repositories\GuaranteeLetterRepository;
use App\Repositories\TagRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class GuaranteeLetters extends Controller {

    /**
     * The users repository instance.
     */
    protected $userrepo;

    /**
     * The guarantee letter repository instance.
     */
    protected $guaranteerepo;

    /**
     * The tags repository instance.
     */
    protected $tagrepo;

    public function __construct(UserRepository $userrepo, GuaranteeLetterRepository $guaranteerepo, TagRepository $tagrepo) {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        $this->middleware('guaranteeLettersMiddlewareIndex')->only([
            'index',
        ]);

        $this->middleware('guaranteeLettersMiddlewareEdit')->only([
            'edit',
            'update',
        ]);

        $this->middleware('guaranteeLettersMiddlewareCreate')->only([
            'create',
            'store',
        ]);

        $this->middleware('guaranteeLettersMiddlewareDestroy')->only(['destroy']);

        $this->middleware('guaranteeLettersMiddlewareShow')->only([
            'show',
        ]);

        //dependencies
        $this->userrepo = $userrepo;
        $this->guaranteerepo = $guaranteerepo;
        $this->tagrepo = $tagrepo;

    }

    /**
     * Display a listing of guarantee letters
     * @return blade view | ajax view
     */
    public function index() {

        //basic page settings
        $page = $this->pageSettings('guarantee_letters');

        //get guarantee letter records
        $guarantees = $this->guaranteerepo->search();

        //get tags
        $tags = $this->tagrepo->getByType('guarantee_letter');

        //get users for filter
        $users = $this->userrepo->search('', ['filter_user_type' => 'team']);

        //reponse payload
        $payload = [
            'page' => $page,
            'guarantees' => $guarantees,
            'tags' => $tags,
            'users' => $users,
        ];

        //show the view
        return new IndexResponse($payload);
    }

    /**
     * Show the form for creating a new guarantee letter
     * @return \Illuminate\Http\Response
     */
    public function create() {

        //basic page settings
        $page = $this->pageSettings('create');

        //get tags
        $tags = $this->tagrepo->getByType('guarantee_letter');

        //get users for assignment
        $users = $this->userrepo->search('', ['filter_user_type' => 'team']);

        //reponse payload
        $payload = [
            'page' => $page,
            'tags' => $tags,
            'users' => $users,
        ];

        //show the view
        return new CreateResponse($payload);
    }

    /**
     * Store a newly created guarantee letter in storage.
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        //validate
        $request->validate([
            'guarantee_number' => 'required|unique:guarantee_letters,guarantee_number',
            'guarantee_type' => 'required|in:tender_participation,performance,advance_payment',
            'industrial_type' => 'required|in:bearing,belzona,pipe',
            'issue_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
        ]);

        //create the guarantee letter record
        if (!$guarantee_id = $this->guaranteerepo->create()) {
            abort(409, __('lang.error_request_could_not_be_completed'));
        }

        //get the guarantee letter record object
        $guarantees = $this->guaranteerepo->search($guarantee_id);
        $guarantee = $guarantees->first();

        //reponse payload
        $payload = [
            'guarantee' => $guarantee,
        ];

        //show the view
        return new StoreResponse($payload);
    }

    /**
     * Display the specified guarantee letter
     * @param int $id guarantee letter id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {

        //get the guarantee letter record
        $guarantees = $this->guaranteerepo->search($id);

        //not found
        if (!$guarantee = $guarantees->first()) {
            abort(404);
        }

        //basic page settings
        $page = $this->pageSettings('guarantee_letters');

        //reponse payload
        $payload = [
            'page' => $page,
            'guarantee' => $guarantee,
        ];

        //show the view
        return new ShowResponse($payload);
    }

    /**
     * Show the form for editing the specified guarantee letter
     * @param int $id guarantee letter id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {

        //get the guarantee letter record
        $guarantees = $this->guaranteerepo->search($id);

        //not found
        if (!$guarantee = $guarantees->first()) {
            abort(404);
        }

        //basic page settings
        $page = $this->pageSettings('edit');

        //get tags
        $tags = $this->tagrepo->getByType('guarantee_letter');

        //get users for assignment
        $users = $this->userrepo->search('', ['filter_user_type' => 'team']);

        //reponse payload
        $payload = [
            'page' => $page,
            'guarantee' => $guarantee,
            'tags' => $tags,
            'users' => $users,
        ];

        //show the view
        return new EditResponse($payload);
    }

    /**
     * Update the specified guarantee letter in storage.
     * @param int $id guarantee letter id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        //validate
        $request->validate([
            'guarantee_number' => 'required|unique:guarantee_letters,guarantee_number,' . $id . ',guarantee_id',
            'guarantee_type' => 'required|in:tender_participation,performance,advance_payment',
            'industrial_type' => 'required|in:bearing,belzona,pipe',
            'issue_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
        ]);

        //update the guarantee letter record
        if (!$this->guaranteerepo->update($id)) {
            abort(409);
        }

        //get the guarantee letter record object
        $guarantee = $this->guaranteerepo->search($id);

        //reponse payload
        $payload = [
            'guarantee' => $guarantee,
        ];

        //show the view
        return new UpdateResponse($payload);
    }

    /**
     * Remove the specified guarantee letter from storage.
     * @param int $id guarantee letter id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {

        //get the guarantee letter record
        $guarantees = $this->guaranteerepo->search($id);

        //not found
        if (!$guarantee = $guarantees->first()) {
            abort(404);
        }

        //remove the guarantee letter record
        $guarantee->delete();

        //reponse payload
        $payload = [
            'guarantee' => $guarantee,
        ];

        //show the view
        return new DestroyResponse($payload);
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
                __('lang.guarantee_letters'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => __('lang.guarantee_letters'),
            'heading' => __('lang.guarantee_letters'),
            'mainmenu_accounting' => 'active',
        ];

        return $page;
    }

}

