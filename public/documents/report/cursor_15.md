# ایجاد زیرمنوی فاکتور و تسویه
_Exported on 11/26/2025 at 11:26:11 GMT+3:30 from Cursor (2.1.20)_

---

**User**

از شما میخوام که در ساید بار در منوی حسابداری ی اهمان اکانتینگ یم زیر منوی دیگر بسازید به نام فاکتور و تسویه و دقیقا مانند صفحه ایندکس بخش فروش باشد که دیتاتیبل دارد  وفیلتر و تنظیمات ستون های دیتا تیبل و همچنین بهش ایمپورت داده. مایگریشن و مدل و کنترلر و ویو  ودیتاتیبل و ستون ها  و تمام بخش های مربوطه رو هم از فایل اکسلی که برای شما اتچ کردم پیاده کنید آن را بررسی کنید و پیاده کنید @فاكتور و تسويه.xlsx 

---

**Cursor**

```php
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceSettlementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_settlements', function (Blueprint $table) {
            $table->bigIncrements('invoice_settlement_id');
            $table->string('document_number')->index();
            $table->string('document_date', 32)->nullable();
            $table->string('customer_name');
            $table->decimal('base_net_amount', 20, 2)->default(0);
            $table->decimal('paid_amount', 20, 2)->default(0);
            $table->decimal('balance_amount', 20, 2)->default(0);
            $table->string('currency', 3)->default('IRR');
            $table->unsignedBigInteger('creator_id')->nullable();
            $table->timestamps();

            $table->index('customer_name');
            $table->index('document_date');
            $table->index('balance_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_settlements');
    }
}

```

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceSettlement extends Model
{
    protected $table = 'invoice_settlements';
    protected $primaryKey = 'invoice_settlement_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['invoice_settlement_id'];

    protected $fillable = [
        'document_number',
        'document_date',
        'customer_name',
        'base_net_amount',
        'paid_amount',
        'balance_amount',
        'currency',
        'creator_id',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }
}

```

```php
*** Begin Patch
*** Update File: application/app/Models/InvoiceSettlement.php
@@
-namespace App\Models;
-
-use Illuminate\Database\Eloquent.Model;
+namespace App\Models;
+
+use Illuminate\Database\Eloquent\Model;
 
 class InvoiceSettlement extends Model
 {
@@
-    public function creator()
-    {
-        return $this->belongsTo(User::class, 'creator_id', 'id');
-    }
+    public function creator()
+    {
+        return $this->belongsTo(User::class, 'creator_id', 'id');
+    }
 }
*** End Patch
```

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class InvoiceSettlement extends Model
{
    protected $table = 'invoice_settlements';
    protected $primaryKey = 'invoice_settlement_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['invoice_settlement_id'];

    protected $fillable = [
        'document_number',
        'document_date',
        'customer_name',
        'base_net_amount',
        'paid_amount',
        'balance_amount',
        'currency',
        'creator_id',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }
}


```

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceSettlement extends Model
{
    protected $table = 'invoice_settlements';
    protected $primaryKey = 'invoice_settlement_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $guarded = ['invoice_settlement_id'];

    protected $fillable = [
        'document_number',
        'document_date',
        'customer_name',
        'base_net_amount',
        'paid_amount',
        'balance_amount',
        'currency',
        'creator_id',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }
}


```

```php
<?php

namespace App\Repositories;

use App\Models\InvoiceSettlement;
use Illuminate\Support\Facades\Schema;

class InvoiceSettlementRepository
{
    protected $model;

    public function __construct(InvoiceSettlement $model)
    {
        $this->model = $model;
    }

    public function search($id = '')
    {
        $query = $this->model->newQuery();

        if (is_numeric($id)) {
            $query->where('invoice_settlement_id', $id)->with('creator');
            return $query;
        }

        if ($search = request('search_query')) {
            $query->where(function ($q) use ($search) {
                $q->where('document_number', 'LIKE', '%' . $search . '%')
                    ->orWhere('customer_name', 'LIKE', '%' . $search . '%');
            });
        }

        $columnSearch = request('column_search', []);
        if (is_array($columnSearch)) {
            foreach ($columnSearch as $column => $value) {
                $this->applyColumnSearch($query, $column, $value);
            }
        }

        foreach (request()->all() as $key => $value) {
            if (strpos($key, 'column_search_') === 0) {
                $column = str_replace('column_search_', '', $key);
                $this->applyColumnSearch($query, $column, $value);
            }
        }

        if ($value = request('filter_document_number')) {
            $query->where('document_number', 'LIKE', '%' . $value . '%');
        }

        if ($value = request('filter_customer_name')) {
            $query->where('customer_name', 'LIKE', '%' . $value . '%');
        }

        $this->applyRangeFilter($query, 'base_net_amount', request('filter_base_net_amount_min'), request('filter_base_net_amount_max'));
        $this->applyRangeFilter($query, 'paid_amount', request('filter_paid_amount_min'), request('filter_paid_amount_max'));
        $this->applyRangeFilter($query, 'balance_amount', request('filter_balance_amount_min'), request('filter_balance_amount_max'));

        if ($value = request('filter_document_date_start')) {
            $query->where('document_date', '>=', $value);
        }

        if ($value = request('filter_document_date_end')) {
            $query->where('document_date', '<=', $value);
        }

        if ($currencies = request('filter_currency')) {
            $query->whereIn('currency', (array) $currencies);
        }

        if (in_array(request('sortorder'), ['asc', 'desc']) && Schema::hasColumn('invoice_settlements', request('orderby'))) {
            $query->orderBy(request('orderby'), request('sortorder'));
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $query->with('creator');

        return $query->paginate(config('system.settings_system_pagination_limits'));
    }

    public function getUniqueValues($column)
    {
        if (!Schema::hasColumn('invoice_settlements', $column)) {
            return [];
        }

        return $this->model->select($column)
            ->distinct()
            ->whereNotNull($column)
            ->where($column, '!=', '')
            ->pluck($column)
            ->toArray();
    }

    public function calculateStats()
    {
        $query = $this->model->newQuery();
        $query = $this->applySharedFilters($query);

        $stats = $query->selectRaw('
            COUNT(*) as total_records,
            COALESCE(SUM(base_net_amount),0) as total_net,
            COALESCE(SUM(paid_amount),0) as total_paid,
            COALESCE(SUM(balance_amount),0) as total_balance
        ')->first();

        return [
            'total_records' => (int) $stats->total_records,
            'total_net' => (float) $stats->total_net,
            'total_paid' => (float) $stats->total_paid,
            'total_balance' => (float) $stats->total_balance,
        ];
    }

    protected function applySharedFilters($query)
    {
        if ($search = request('search_query')) {
            $query->where(function ($q) use ($search) {
                $q->where('document_number', 'LIKE', '%' . $search . '%')
                    ->orWhere('customer_name', 'LIKE', '%' . $search . '%');
            });
        }

        $columnSearch = request('column_search', []);
        if (is_array($columnSearch)) {
            foreach ($columnSearch as $column => $value) {
                $this->applyColumnSearch($query, $column, $value);
            }
        }

        foreach (request()->all() as $key => $value) {
            if (strpos($key, 'column_search_') === 0) {
                $column = str_replace('column_search_', '', $key);
                $this->applyColumnSearch($query, $column, $value);
            }
        }

        if ($value = request('filter_document_number')) {
            $query->where('document_number', 'LIKE', '%' . $value . '%');
        }

        if ($value = request('filter_customer_name')) {
            $query->where('customer_name', 'LIKE', '%' . $value . '%');
        }

        $this->applyRangeFilter($query, 'base_net_amount', request('filter_base_net_amount_min'), request('filter_base_net_amount_max'));
        $this->applyRangeFilter($query, 'paid_amount', request('filter_paid_amount_min'), request('filter_paid_amount_max'));
        $this->applyRangeFilter($query, 'balance_amount', request('filter_balance_amount_min'), request('filter_balance_amount_max'));

        if ($value = request('filter_document_date_start')) {
            $query->where('document_date', '>=', $value);
        }

        if ($value = request('filter_document_date_end')) {
            $query->where('document_date', '<=', $value);
        }

        if ($currencies = request('filter_currency')) {
            $query->whereIn('currency', (array) $currencies);
        }

        return $query;
    }

    protected function applyColumnSearch($query, $column, $value)
    {
        if ($value === null || $value === '') {
            return;
        }

        $value = urldecode($value);

        if (!Schema::hasColumn('invoice_settlements', $column) && $column !== 'creator') {
            return;
        }

        if ($column === 'creator') {
            $query->whereHas('creator', function ($q) use ($value) {
                $q->where('first_name', 'LIKE', '%' . $value . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $value . '%');
            });
            return;
        }

        $query->where($column, 'LIKE', '%' . $value . '%');
    }

    protected function applyRangeFilter($query, $column, $min, $max)
    {
        if ($min !== null && $min !== '') {
            $query->where($column, '>=', $this->toDecimal($min));
        }

        if ($max !== null && $max !== '') {
            $query->where($column, '<=', $this->toDecimal($max));
        }
    }

    protected function toDecimal($value)
    {
        if ($value === null || $value === '') {
            return 0;
        }

        $value = preg_replace('/[^0-9E\.\-]/', '', (string) $value);
        return (float) $value;
    }
}

```

```php
<?php

namespace App\Http\Middleware\InvoiceSettlements;

use Closure;
use App\Models\TableConfig;

class Index
{
    public function handle($request, Closure $next)
    {
        $this->setTableConfig();
        $this->setFrontend();

        return $next($request);
    }

    protected function setTableConfig()
    {
        $table = TableConfig::where('tableconfig_userid', auth()->id())
            ->where('tableconfig_table_name', 'invoice_settlements')
            ->first();

        if (!$table) {
            $table = new TableConfig();
            $table->tableconfig_userid = auth()->id();
            $table->tableconfig_table_name = 'invoice_settlements';
            $table->tableconfig_column_1 = 'displayed'; // document number
            $table->tableconfig_column_2 = 'displayed'; // document date
            $table->tableconfig_column_3 = 'displayed'; // customer name
            $table->tableconfig_column_4 = 'displayed'; // base net amount
            $table->tableconfig_column_5 = 'displayed'; // paid amount
            $table->tableconfig_column_6 = 'displayed'; // balance amount
            $table->tableconfig_column_7 = 'displayed'; // currency
            $table->tableconfig_column_8 = 'hidden';    // creator
            $table->tableconfig_column_9 = 'hidden';    // created_at
            $table->save();
        }

        config(['table' => $table]);
    }

    protected function setFrontend()
    {
        config([
            'visibility.list_page_actions_filter_button' => true,
            'visibility.list_page_actions_search' => true,
            'visibility.invoice_settlements_col_checkboxes' => false,
        ]);
    }
}

```

```php
<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel {
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [

            //[growcrm] make sure we have no session during setup
            \App\Http\Middleware\General\Setup::class,

            //system middleware
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,

            //[GROWNCRM] BOOTING
            \App\Http\Middleware\General\BootSystem::class,
            \App\Http\Middleware\General\BootTheme::class,
            \App\Http\Middleware\General\BootMail::class,

            //[growcrm] [settings middleware]
            \App\Http\Middleware\General\Settings::class,
            //[growcrm] [general middleware]
            \App\Http\Middleware\General\SanityCheck::class,
            //[growcrm] [general middleware]
            \App\Http\Middleware\General\General::class,
            //[growcrm] [modules middleware]
            \App\Http\Middleware\Modules\Status::class,
            //[growcrm] [modules middleware]
            \App\Http\Middleware\Modules\Visibility::class,

            //[MODULES] [growcrm] [modules main menus]
            \App\Http\Middleware\Modules\Bootstrap::class,
            \App\Http\Middleware\Modules\Menus::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [

        //system
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'memo' => \App\Http\Middleware\General\Memo::class, //[memo]

        /** ---------------------------------------------------------------------------------
         * [SAAS] MIDDLEWARE
         *-----------------------------------------------------------------------------------*/
        'accountStatus' => \App\Http\Middleware\Account\AccountStatus::class,
        'accountLimitsClients' => \App\Http\Middleware\Account\AccountLimitsClients::class,
        'accountLimitsTeam' => \App\Http\Middleware\Account\AccountLimitsTeam::class,
        'accountLimitsProjects' => \App\Http\Middleware\Account\AccountLimitsProjects::class,

        /** ---------------------------------------------------------------------------------
         * CRM MIDDLEWARE
         *-----------------------------------------------------------------------------------*/

        //[growcrm] - [general]
        'adminCheck' => \App\Http\Middleware\General\AdminCheck::class,
        'teamCheck' => \App\Http\Middleware\General\TeamCheck::class,
        'generalMiddleware' => \App\Http\Middleware\General\General::class,
        'demoModeCheck' => \App\Http\Middleware\General\DemoCheck::class,
        'FileSecurityCheck' => \App\Http\Middleware\Fileupload\FileSecurityCheck::class,

        //[growcrm] - [authentication]
        'authenticationMiddlewareGeneral' => \App\Http\Middleware\Authenticate\General::class,

        //[growcrm] - [authentication]
        'categoriesMiddlewareGeneral' => \App\Http\Middleware\Categories\General::class,

        //[growcrm] - [clients]
        'clientsMiddlewareIndex' => \App\Http\Middleware\Clients\Index::class,
        'clientsMiddlewareEdit' => \App\Http\Middleware\Clients\Edit::class,
        'clientsMiddlewareDestroy' => \App\Http\Middleware\Clients\Destroy::class,
        'clientsMiddlewareShow' => \App\Http\Middleware\Clients\Show::class,
        'clientsMiddlewareCreate' => \App\Http\Middleware\Clients\Create::class,
        'importClientsMiddlewareCreate' => \App\Http\Middleware\Import\Clients\Create::class,

        //[growcrm] - [projects]
        'projectsMiddlewareIndex' => \App\Http\Middleware\Projects\Index::class,
        'projectsMiddlewareShow' => \App\Http\Middleware\Projects\Show::class,
        'projectsMiddlewareEdit' => \App\Http\Middleware\Projects\Edit::class,
        'projectsMiddlewareCreate' => \App\Http\Middleware\Projects\Create::class,
        'projectsMiddlewareDestroy' => \App\Http\Middleware\Projects\Destroy::class,
        'projectsMiddlewareBulkEdit' => \App\Http\Middleware\Projects\BulkEdit::class,
        'projectsMiddlewareBulkAssign' => \App\Http\Middleware\Projects\BulkAssign::class,

        //[growcrm] - [knowledgebase]
        'knowledgebaseMiddlewareIndex' => \App\Http\Middleware\Knowledgebase\Index::class,
        'knowledgebaseMiddlewareCreate' => \App\Http\Middleware\Knowledgebase\Create::class,
        'knowledgebaseMiddlewareEdit' => \App\Http\Middleware\Knowledgebase\Edit::class,
        'knowledgebaseMiddlewareDestroy' => \App\Http\Middleware\Knowledgebase\Destroy::class,
        'knowledgebaseMiddlewareShow' => \App\Http\Middleware\Knowledgebase\Show::class,

        //[growcrm] - [knowledgebase]
        'knowledgebaseCategoriesMiddlewareEdit' => \App\Http\Middleware\Kbcategories\Edit::class,
        'knowledgebaseCategoriesMiddlewareDestroy' => \App\Http\Middleware\Kbcategories\Destroy::class,

        //[growcrm] - [timesheets]
        'timesheetsMiddlewareIndex' => \App\Http\Middleware\Timesheets\Index::class,
        'timesheetsMiddlewareDestroy' => \App\Http\Middleware\Timesheets\Destroy::class,
        'timesheetsMiddlewareEdit' => \App\Http\Middleware\Timesheets\Edit::class,

        //[growcrm] - [settings]
        'settingsMiddlewareIndex' => \App\Http\Middleware\Settings\Index::class,

        //[growcrm] - [expenses]
        'expensesMiddlewareIndex' => \App\Http\Middleware\Expenses\Index::class,
        'expensesMiddlewareShow' => \App\Http\Middleware\Expenses\Show::class,
        'expensesMiddlewareEdit' => \App\Http\Middleware\Expenses\Edit::class,
        'expensesMiddlewareCreate' => \App\Http\Middleware\Expenses\Create::class,
        'expensesMiddlewareDownloadAttachment' => \App\Http\Middleware\Expenses\DownloadAttachment::class,
        'expensesMiddlewareDeleteAttachment' => \App\Http\Middleware\Expenses\DeleteAttachment::class,
        'expensesMiddlewareDestroy' => \App\Http\Middleware\Expenses\Destroy::class,
        'expensesMiddlewareBulkEdit' => \App\Http\Middleware\Expenses\BulkEdit::class,
        'expensesMiddlewareGeneralSingleActions' => \App\Http\Middleware\Expenses\GeneralSingleActions::class,
        'expensesMiddlewareCreateInvoice' => \App\Http\Middleware\Expenses\Createinvoice::class,

        //[growcrm] - [invoices]
        'invoicesMiddlewareIndex' => \App\Http\Middleware\Invoices\Index::class,
        'invoicesMiddlewareCreate' => \App\Http\Middleware\Invoices\Create::class,
        'invoicesMiddlewareEdit' => \App\Http\Middleware\Invoices\Edit::class,
        'invoicesMiddlewareShow' => \App\Http\Middleware\Invoices\Show::class,
        'invoicesMiddlewareDestroy' => \App\Http\Middleware\Invoices\Destroy::class,
        'invoicesMiddlewareBulkEdit' => \App\Http\Middleware\Invoices\BulkEdit::class,
        'invoicesMiddlewareGeneralSingleActions' => \App\Http\Middleware\Invoices\GeneralSingleActions::class,

        //[growcrm] - [estimates]
        'estimatesMiddlewareIndex' => \App\Http\Middleware\Estimates\Index::class,
        'estimatesMiddlewareCreate' => \App\Http\Middleware\Estimates\Create::class,
        'estimatesMiddlewareShow' => \App\Http\Middleware\Estimates\Show::class,
        'estimatesMiddlewareDestroy' => \App\Http\Middleware\Estimates\Destroy::class,
        'estimatesMiddlewareBulkEdit' => \App\Http\Middleware\Estimates\BulkEdit::class,
        'estimatesMiddlewareEdit' => \App\Http\Middleware\Estimates\Edit::class,
        'estimatesMiddlewareShowPublic' => \App\Http\Middleware\Estimates\ShowPublic::class,

        //[growcrm] - [payments]
        'paymentsMiddlewareIndex' => \App\Http\Middleware\Payments\Index::class,
        'paymentsMiddlewareShow' => \App\Http\Middleware\Payments\Show::class,
        'paymentsMiddlewareDestroy' => \App\Http\Middleware\Payments\Destroy::class,
        'paymentsMiddlewareCreate' => \App\Http\Middleware\Payments\Create::class,
        'paymentsMiddlewareBulkEdit' => \App\Http\Middleware\Payments\BulkEdit::class, //DONE
        'paymentsMiddlewareEdit' => \App\Http\Middleware\Payments\Edit::class, //DONE

        //[growcrm] - [notes]
        'notesMiddlewareIndex' => \App\Http\Middleware\Notes\Index::class,
        'notesMiddlewareCreate' => \App\Http\Middleware\Notes\Create::class,
        'notesMiddlewareEdit' => \App\Http\Middleware\Notes\Edit::class,
        'notesMiddlewareDestroy' => \App\Http\Middleware\Notes\Destroy::class,
        'notesMiddlewareShow' => \App\Http\Middleware\Notes\Show::class,

        //[growcrm] - [items]
        'itemsMiddlewareIndex' => \App\Http\Middleware\Items\Index::class,
        'itemsMiddlewareCreate' => \App\Http\Middleware\Items\Create::class,
        'itemsMiddlewareEdit' => \App\Http\Middleware\Items\Edit::class,
        'itemsMiddlewareDestroy' => \App\Http\Middleware\Items\Destroy::class,
        'itemsMiddlewareBulkEdit' => \App\Http\Middleware\Items\BulkEdit::class, //DONE

        //[growcrm] - [contacts]
        'contactsMiddlewareIndex' => \App\Http\Middleware\Contacts\Index::class,
        'contactsMiddlewareCreate' => \App\Http\Middleware\Contacts\Create::class,
        'contactsMiddlewareEdit' => \App\Http\Middleware\Contacts\Edit::class,
        'contactsMiddlewareDestroy' => \App\Http\Middleware\Contacts\Destroy::class,
        'contactsMiddlewareShow' => \App\Http\Middleware\Contacts\Show::class,

        //[growcrm] - [tickets]
        'ticketsMiddlewareIndex' => \App\Http\Middleware\Tickets\Index::class,
        'ticketsMiddlewareCreate' => \App\Http\Middleware\Tickets\Create::class,
        'ticketsMiddlewareShow' => \App\Http\Middleware\Tickets\Show::class,
        'ticketsMiddlewareEdit' => \App\Http\Middleware\Tickets\Edit::class,
        'ticketsMiddlewareDestroy' => \App\Http\Middleware\Tickets\Destroy::class,
        'ticketsMiddlewareReply' => \App\Http\Middleware\Tickets\Reply::class,
        'ticketsMiddlewareDownloadAttachment' => \App\Http\Middleware\Tickets\DownloadAttachment::class,
        'ticketsMiddlewareEditReply' => \App\Http\Middleware\Tickets\EditReply::class,
        'ticketsMiddlewareBulkEdit' => \App\Http\Middleware\Tickets\BulkEdit::class, //DONE

        //[growcrm] - [leads]
        'leadsMiddlewareIndex' => \App\Http\Middleware\Leads\Index::class,
        'leadsMiddlewareCreate' => \App\Http\Middleware\Leads\Create::class,
        'leadsMiddlewareEdit' => \App\Http\Middleware\Leads\Edit::class,
        'leadsMiddlewareShow' => \App\Http\Middleware\Leads\Show::class,
        'leadsMiddlewareDestroy' => \App\Http\Middleware\Leads\Destroy::class,
        'leadsMiddlewareBulkEdit' => \App\Http\Middleware\Leads\BulkEdit::class,
        'leadsMiddlewareParticipate' => \App\Http\Middleware\Leads\Participate::class,
        'leadsMiddlewareDeleteAttachment' => \App\Http\Middleware\Leads\DeleteAttachment::class,
        'leadsMiddlewareDownloadAttachment' => \App\Http\Middleware\Leads\DownloadAttachment::class,
        'leadsMiddlewareDeleteComment' => \App\Http\Middleware\Leads\DeleteComment::class,
        'leadsMiddlewareEditDeleteChecklist' => \App\Http\Middleware\Leads\EditDeleteChecklist::class,
        'leadsMiddlewareAssign' => \App\Http\Middleware\Leads\Assign::class,
        'importLeadsMiddlewareCreate' => \App\Http\Middleware\Import\Leads\Create::class,
        'leadsMiddlewareCloning' => \App\Http\Middleware\Leads\Cloning::class,
        'leadsMiddlewareBulkAssign' => \App\Http\Middleware\Leads\BulkAssign::class,

        //[growcrm] - [tasks]
        'tasksMiddlewareIndex' => \App\Http\Middleware\Tasks\Index::class,
        'tasksMiddlewareShow' => \App\Http\Middleware\Tasks\Show::class,
        'tasksMiddlewareCreate' => \App\Http\Middleware\Tasks\Create::class,
        'tasksMiddlewareDestroy' => \App\Http\Middleware\Tasks\Destroy::class,
        'tasksMiddlewareTimer' => \App\Http\Middleware\Tasks\Timer::class,
        'tasksMiddlewareEdit' => \App\Http\Middleware\Tasks\Edit::class,
        'tasksMiddlewareParticipate' => \App\Http\Middleware\Tasks\Participate::class,
        'tasksMiddlewareDeleteAttachment' => \App\Http\Middleware\Tasks\DeleteAttachment::class,
        'tasksMiddlewareDownloadAttachment' => \App\Http\Middleware\Tasks\DownloadAttachment::class,
        'tasksMiddlewareDeleteComment' => \App\Http\Middleware\Tasks\DeleteComment::class,
        'tasksMiddlewareEditDeleteChecklist' => \App\Http\Middleware\Tasks\EditDeleteChecklist::class,
        'tasksMiddlewareAssign' => \App\Http\Middleware\Tasks\Assign::class,
        'tasksMiddlewareCloning' => \App\Http\Middleware\Tasks\Cloning::class,
        'tasksMiddlewareManageDependencies' => \App\Http\Middleware\Tasks\ManageDependencies::class,

        //[growcrm] - [files]
        'filesMiddlewareIndex' => \App\Http\Middleware\Files\Index::class,
        'filesMiddlewareCreate' => \App\Http\Middleware\Files\Create::class,
        'filesMiddlewareDownload' => \App\Http\Middleware\Files\Download::class,
        'filesMiddlewareDestroy' => \App\Http\Middleware\Files\Destroy::class,
        'filesMiddlewareEdit' => \App\Http\Middleware\Files\Edit::class,
        'filesMiddlewareMove' => \App\Http\Middleware\Files\Move::class,
        'filesMiddlewareBulkDownload' => \App\Http\Middleware\Files\BulkDownload::class,
        'manageFoldersMiddleware' => \App\Http\Middleware\Files\ManageFolders::class,
        'filesMiddlewareCopy' => \App\Http\Middleware\Files\Copy::class,

        //[growcrm] - [comments]
        'commentsMiddlewareIndex' => \App\Http\Middleware\Comments\Index::class,
        'commentsMiddlewareCreate' => \App\Http\Middleware\Comments\Create::class,
        'commentsMiddlewareDestroy' => \App\Http\Middleware\Comments\Destroy::class,

        //[growcrm] - [milestone]
        'milestonesMiddlewareIndex' => \App\Http\Middleware\Milestones\Index::class,
        'milestonesMiddlewareCreate' => \App\Http\Middleware\Milestones\Create::class,
        'milestonesMiddlewareEdit' => \App\Http\Middleware\Milestones\Edit::class,
        'milestonesMiddlewareDestroy' => \App\Http\Middleware\Milestones\Destroy::class,

        //[growcrm] - [subscription]
        'subscriptionsMiddlewareIndex' => \App\Http\Middleware\Subscriptions\Index::class,
        'subscriptionsMiddlewareCreate' => \App\Http\Middleware\Subscriptions\Create::class,
        'subscriptionsMiddlewareEdit' => \App\Http\Middleware\Subscriptions\Edit::class,
        'subscriptionsMiddlewareDestroy' => \App\Http\Middleware\Subscriptions\Destroy::class,
        'subscriptionsMiddlewareShow' => \App\Http\Middleware\Subscriptions\Show::class,
        'subscriptionsMiddlewareCancel' => \App\Http\Middleware\Subscriptions\Cancel::class,

        //[growcrm] - [milestone]
        'homeMiddlewareIndex' => \App\Http\Middleware\Home\Index::class,

        //[growcrm] - [project templates]
        'projectTemplatesGeneral' => \App\Http\Middleware\Projects\ProjectTemplatesGeneral::class,
        'projectTemplatesMiddlewareIndex' => \App\Http\Middleware\Templates\Projects\Index::class,
        'projectTemplatesMiddlewareShow' => \App\Http\Middleware\Templates\Projects\Show::class,
        'projectTemplatesMiddlewareEdit' => \App\Http\Middleware\Templates\Projects\Edit::class,
        'projectTemplatesMiddlewareCreate' => \App\Http\Middleware\Templates\Projects\Create::class,
        'projectTemplatesMiddlewareDestroy' => \App\Http\Middleware\Templates\Projects\Destroy::class,

        //[growcrm] - [customfields]
        'customfieldsMiddlewareEdit' => \App\Http\Middleware\Settings\CustomFields\Edit::class,

        //[growcrm] - [team]
        'teamMiddlewareIndex' => \App\Http\Middleware\Team\Index::class,
        'teamMiddlewareCreate' => \App\Http\Middleware\Team\Create::class,
        'teamMiddlewareEdit' => \App\Http\Middleware\Team\Edit::class,

        //[growcrm] - [proposals]
        'proposalsMiddlewareIndex' => \App\Http\Middleware\Proposals\Index::class,
        'proposalsMiddlewareShow' => \App\Http\Middleware\Proposals\Show::class,
        'proposalsMiddlewareCreate' => \App\Http\Middleware\Proposals\Create::class,
        'proposalsMiddlewareEdit' => \App\Http\Middleware\Proposals\Edit::class,
        'proposalsMiddlewareDestroy' => \App\Http\Middleware\Proposals\Destroy::class,
        'proposalsMiddlewareBulkEdit' => \App\Http\Middleware\Proposals\BulkEdit::class,
        'proposalsMiddlewareShowPublic' => \App\Http\Middleware\Proposals\ShowPublic::class,

        //[growcrm] - [contracts]
        'contractsMiddlewareIndex' => \App\Http\Middleware\Contracts\Index::class,
        'contractsMiddlewareShow' => \App\Http\Middleware\Contracts\Show::class,
        'contractsMiddlewareCreate' => \App\Http\Middleware\Contracts\Create::class,
        'contractsMiddlewareEdit' => \App\Http\Middleware\Contracts\Edit::class,
        'contractsMiddlewareDestroy' => \App\Http\Middleware\Contracts\Destroy::class,
        'contractsMiddlewareBulkEdit' => \App\Http\Middleware\Contracts\BulkEdit::class,
        'contractsMiddlewareShowPublic' => \App\Http\Middleware\Contracts\ShowPublic::class,
        'contractsMiddlewareSignClient' => \App\Http\Middleware\Contracts\SignClient::class,
        'contractsMiddlewareSignTeam' => \App\Http\Middleware\Contracts\SignTeam::class,

        //[growcrm] - [documents](proposals & contracts)
        'documentsMiddlewareEdit' => \App\Http\Middleware\Documents\Edit::class,

        //[growcrm] - [spaces]
        'spacesMiddlewareShow' => \App\Http\Middleware\Spaces\Show::class,

        //[growcrm] - [product tasks]
        'productTasksMiddlewareView' => \App\Http\Middleware\Items\TasksView::class,
        'productTasksMiddlewareEdit' => \App\Http\Middleware\Items\TasksEdit::class,

        //[growcrm] - [messages]
        'messagesMiddlewareIndex' => \App\Http\Middleware\Messages\Index::class,
        'messagesMiddlewareDestroy' => \App\Http\Middleware\Messages\Destroy::class,
        'messagesMiddlewareCreate' => \App\Http\Middleware\Messages\Create::class,

        //[growcrm] - [proposal templates]
        'proposalTemplatesMiddlewareIndex' => \App\Http\Middleware\Templates\Proposals\Index::class,
        'proposalTemplatesMiddlewareShow' => \App\Http\Middleware\Templates\Proposals\Show::class,
        'proposalTemplatesMiddlewareEdit' => \App\Http\Middleware\Templates\Proposals\Edit::class,
        'proposalTemplatesMiddlewareCreate' => \App\Http\Middleware\Templates\Proposals\Create::class,
        'proposalTemplatesMiddlewareDestroy' => \App\Http\Middleware\Templates\Proposals\Destroy::class,

        //[growcrm] - [contract templates]
        'contractTemplatesMiddlewareIndex' => \App\Http\Middleware\Templates\Contracts\Index::class,
        'contractTemplatesMiddlewareShow' => \App\Http\Middleware\Templates\Contracts\Show::class,
        'contractTemplatesMiddlewareEdit' => \App\Http\Middleware\Templates\Contracts\Edit::class,
        'contractTemplatesMiddlewareCreate' => \App\Http\Middleware\Templates\Contracts\Create::class,
        'contractTemplatesMiddlewareDestroy' => \App\Http\Middleware\Templates\Contracts\Destroy::class,

        //[growcrm] - [reports]
        'reportsMiddlewareShow' => \App\Http\Middleware\Reports\Show::class,

        //[growcrm] - [contract templates]
        'cannedMiddlewareIndex' => \App\Http\Middleware\Canned\Index::class,
        'cannedMiddlewareShow' => \App\Http\Middleware\Canned\Show::class,
        'cannedMiddlewareEdit' => \App\Http\Middleware\Canned\Edit::class,
        'cannedMiddlewareCreate' => \App\Http\Middleware\Canned\Create::class,
        'cannedMiddlewareDestroy' => \App\Http\Middleware\Canned\Destroy::class,

        //[growcrm] - [reports]
        'searchMiddlewareIndex' => \App\Http\Middleware\Search\Index::class,

        //[growcrm] - [inventory]
        'inventory.index' => \App\Http\Middleware\Inventory\Index::class,

        //[growcrm] - [invoice settlements]
        'invoice-settlements.index' => \App\Http\Middleware\InvoiceSettlements\Index::class,

        //[growcrm] - [sales]
        'sales.index' => \App\Http\Middleware\Sales\Index::class,
    ];
}

```

```php
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Responses\InvoiceSettlements\IndexResponse;
use App\Models\InvoiceSettlement;
use App\Repositories\InvoiceSettlementRepository;

class InvoiceSettlementController extends Controller
{
    protected $repo;

    public function __construct(InvoiceSettlementRepository $repo)
    {
        parent::__construct();

        $this->middleware('auth');
        $this->middleware('invoice-settlements.index')->only(['index']);

        $this->repo = $repo;
    }

    public function index()
    {
        if (request()->get('action') === 'unique_values' && request()->has('column')) {
            $values = $this->repo->getUniqueValues(request('column'));
            return response()->json([
                'success' => true,
                'data' => $values,
            ]);
        }

        if (request()->get('action') === 'datatables') {
            return $this->getDataTablesData();
        }

        $page = $this->pageSettings();
        $settlements = $this->repo->search();

        $stats = [
            'total_records' => $settlements->total(),
            'total_net' => InvoiceSettlement::sum('base_net_amount'),
            'total_paid' => InvoiceSettlement::sum('paid_amount'),
            'total_balance' => InvoiceSettlement::sum('balance_amount'),
        ];

        $payload = [
            'page' => $page,
            'settlements' => $settlements,
            'stats' => $stats,
            'settlementrepo' => $this->repo,
        ];

        return new IndexResponse($payload);
    }

    protected function getDataTablesData()
    {
        $settlements = $this->repo->search();

        $data = [];
        foreach ($settlements->items() as $item) {
            $data[] = [
                'invoice_settlement_id' => $item->invoice_settlement_id,
                'document_number' => $item->document_number,
                'document_date' => $item->document_date,
                'customer_name' => $item->customer_name,
                'base_net_amount' => $item->base_net_amount,
                'paid_amount' => $item->paid_amount,
                'balance_amount' => $item->balance_amount,
                'currency' => $item->currency,
                'creator' => optional($item->creator)->first_name . ' ' . optional($item->creator)->last_name,
            ];
        }

        return response()->json([
            'draw' => request('draw'),
            'recordsTotal' => $settlements->total(),
            'recordsFiltered' => $settlements->total(),
            'data' => $data,
        ]);
    }

    protected function pageSettings()
    {
        return [
            'page_title' => __('lang.invoice_settlements'),
            'heading' => __('lang.invoice_settlements'),
            'crumbs' => [
                __('lang.accounting'),
                __('lang.invoice_settlements'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'mainmenu_accounting' => 'active',
            'submenu_invoice_settlements' => 'active',
        ];
    }
}

```

```php
<?php

namespace App\Http\Responses\InvoiceSettlements;

use Illuminate\Contracts\Support\Responsable;

class IndexResponse implements Responsable
{
    private $payload;

    public function __construct($payload = [])
    {
        $this->payload = $payload;
    }

    public function toResponse($request)
    {
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        if (request('source') == 'ext' || request('action') == 'search' || request()->ajax()) {
            switch (request('action')) {
                case 'load':
                    $template = 'pages.invoice-settlements.components.table.ajax';
                    $dom_container = '#invoice-settlements-td-container';
                    $dom_action = 'append';
                    break;
                case 'sort':
                    $template = 'pages.invoice-settlements.components.table.ajax';
                    $dom_container = '#invoice-settlements-td-container';
                    $dom_action = 'replace';
                    break;
                case 'search':
                    $template = 'pages.invoice-settlements.components.table.table';
                    $dom_container = '#invoice-settlements-table-wrapper';
                    $dom_action = 'replace-with';
                    if (isset($settlementrepo)) {
                        $filteredStats = $settlementrepo->calculateStats();
                        $jsondata['stats'] = $filteredStats;
                    } else {
                        $jsondata['stats'] = $stats ?? [];
                    }
                    break;
                default:
                    $template = 'pages.invoice-settlements.components.table.datatables-wrapper';
                    $dom_container = '#embed-content-container';
                    $dom_action = 'replace';
                    break;
            }

            if (request('action') == 'sort') {
                $sort_url = flipSortingUrl(request()->fullUrl(), request('sortorder'));
                $element_id = '#sort_' . request('orderby');
                $jsondata['dom_attributes'][] = [
                    'selector' => $element_id,
                    'attr' => 'data-url',
                    'value' => $sort_url,
                ];
            }

            $html = view($template, compact('page', 'settlements', 'stats', 'settlementrepo'))->render();
            $jsondata['dom_html'][] = [
                'selector' => $dom_container,
                'action' => $dom_action,
                'value' => $html,
            ];

            return response()->json($jsondata);
        }

        return response()->view('pages.invoice-settlements.index', $this->payload);
    }
}

```

```php
<?php

namespace App\Imports;

use App\Models\InvoiceSettlement;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithStartRow;

class InvoiceSettlementsImport implements
    ToModel,
    WithStartRow,
    SkipsOnFailure,
    WithChunkReading,
    WithBatchInserts,
    WithProgressBar
{
    use Importable, SkipsFailures;

    protected $rows = 0;
    protected $skipped = 0;
    protected $rowIndex = 0;
    protected $skippedDetails = [];

    public function model(array $row)
    {
        $this->rowIndex++;

        if ($this->isEmptyRow($row)) {
            $this->recordSkip('empty_row', $row);
            return null;
        }

        ++$this->rows;

        try {
            return new InvoiceSettlement([
                'document_number' => $this->normalizeDocumentNumber($row[0] ?? null),
                'document_date' => $this->normalizeDate($row[1] ?? null),
                'customer_name' => trim($row[2] ?? ''),
                'base_net_amount' => $this->parseDecimal($row[3] ?? 0),
                'paid_amount' => $this->parseDecimal($row[4] ?? 0),
                'balance_amount' => $this->parseDecimal($row[5] ?? 0),
                'currency' => 'IRR',
                'creator_id' => auth()->id(),
            ]);
        } catch (\Exception $e) {
            Log::error('Invoice settlement import failed', ['error' => $e->getMessage()]);
            $this->recordSkip('exception: ' . $e->getMessage(), $row);
            return null;
        }
    }

    public function startRow(): int
    {
        return 2;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }

    public function getSkippedCount(): int
    {
        return $this->skipped;
    }

    public function getSkippedDetails(): array
    {
        return $this->skippedDetails;
    }

    protected function isEmptyRow(array $row): bool
    {
        return empty(array_filter($row));
    }

    protected function normalizeDocumentNumber($value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        $value = trim((string) $value);

        if (is_numeric($value)) {
            if (strpos($value, '.') !== false) {
                $value = rtrim(rtrim($value, '0'), '.');
            }
            return $value;
        }

        return $value;
    }

    protected function normalizeDate($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        $value = str_replace('\\', '', trim((string) $value));
        return $value;
    }

    protected function parseDecimal($value): float
    {
        if ($value === null || $value === '') {
            return 0;
        }

        $value = preg_replace('/[^0-9E\.\-]/', '', (string) $value);
        return (float) $value;
    }

    protected function recordSkip(string $reason, array $row): void
    {
        $this->skipped++;
        $excelRowNumber = $this->startRow() + $this->rowIndex - 1;

        $this->skippedDetails[] = [
            'row_number' => $excelRowNumber,
            'reason' => $reason,
            'document_number' => $row[0] ?? null,
            'customer_name' => $row[2] ?? null,
        ];
    }
}

```

```php
<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use App\Imports\InvoiceSettlementsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InvoiceSettlements extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function index()
    {
        $page = [
            'page' => 'import',
            'crumbs' => [
                __('lang.accounting'),
                __('lang.invoice_settlements'),
                __('lang.import'),
            ],
            'crumbs_special_class' => 'main-pages-crumbs',
            'page_title' => __('lang.import_invoice_settlements'),
            'heading' => __('lang.import_invoice_settlements'),
            'mainmenu_accounting' => 'active',
        ];

        return view('pages.import.invoice-settlements', compact('page'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'attachments' => 'required|array',
            'attachments.*' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        if (!$request->hasFile('attachments') || !$request->file('attachments')[0]) {
            return response()->json([
                'success' => false,
                'message' => __('lang.no_file_uploaded'),
                'imported' => 0,
                'skipped' => 0,
            ], 400);
        }

        $file = $request->file('attachments')[0];
        $file_path = $file->getPathname();
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, ['xlsx', 'xls', 'csv'])) {
            abort(409, __('lang.invalid_file_type'));
        }

        $importResults = [
            'success' => false,
            'imported' => 0,
            'skipped' => 0,
            'message' => '',
        ];

        try {
            $import = new InvoiceSettlementsImport();
            $import->import($file_path);

            $importResults = [
                'success' => true,
                'imported' => $import->getRowCount(),
                'skipped' => $import->getSkippedCount(),
                'skipped_details' => $import->getSkippedDetails(),
                'message' => __('lang.import_invoice_settlements_success', ['count' => $import->getRowCount()]),
            ];
        } catch (\Exception $e) {
            Log::error('Invoice settlements import failed', ['error' => $e->getMessage()]);
            $importResults = [
                'success' => false,
                'imported' => 0,
                'skipped' => 0,
                'message' => __('lang.import_failed_generic'),
            ];
        }

        if ($request->ajax()) {
            return response()->json($importResults);
        }

        return redirect()->back()->with('import_results', $importResults);
    }
}

```

```blade
@extends('layout.wrapper')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ _url('/') }}">{{ cleanLang(__('lang.dashboard')) }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ _url('/accounting') }}">{{ cleanLang(__('lang.accounting')) }}</a></li>
                        <li class="breadcrumb-item active">{{ cleanLang(__('lang.invoice_settlements')) }}</li>
                    </ol>
                </div>
                <h4 class="page-title">{{ cleanLang(__('lang.invoice_settlements')) }}</h4>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="ti-file font-24 text-primary"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-0">{{ cleanLang(__('lang.total_records')) }}</p>
                        <h4 class="my-1">{{ number_format($stats['total_records'] ?? 0) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="ti-money font-24 text-success"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-0">{{ cleanLang(__('lang.total_net_amount')) }}</p>
                        <h4 class="my-1 stats-total-net-amount">{{ formatCurrency($stats['total_net'] ?? 0, 'IRR') }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="ti-check-box font-24 text-info"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-0">{{ cleanLang(__('lang.total_paid_amount')) }}</p>
                        <h4 class="my-1 stats-total-paid-amount">{{ formatCurrency($stats['total_paid'] ?? 0, 'IRR') }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="ti-alert font-24 text-danger"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-0">{{ cleanLang(__('lang.total_balance_amount')) }}</p>
                        <h4 class="my-1 stats-total-balance-amount">{{ formatCurrency($stats['total_balance'] ?? 0, 'IRR') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6 mb-2 mb-md-0">
                            @if(config('visibility.list_page_actions_search'))
                            <input type="text" name="search_query" id="search_query" class="form-control"
                                placeholder="{{ cleanLang(__('lang.search_invoice_settlements')) }}"
                                value="{{ request('search_query') }}">
                            @endif
                        </div>
                        <div class="col-md-6 text-end">
                            @if(config('visibility.list_page_actions_filter_button'))
                            <button type="button" class="btn btn-outline-secondary js-toggle-side-panel"
                                data-target="sidepanel-filter-invoice-settlements">
                                <i class="ti-filter"></i> {{ cleanLang(__('lang.filter')) }}
                            </button>
                            @endif
                            <a href="{{ _url('/import/invoice-settlements') }}" class="btn btn-success me-2">
                                <i class="ti-upload"></i> {{ cleanLang(__('lang.import_invoice_settlements')) }}
                            </a>
                            <button type="button" class="btn btn-outline-warning me-2 clear-column-searches">
                                <i class="ti-refresh"></i> {{ cleanLang(__('lang.clear_searches')) }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @include('pages.invoice-settlements.components.table.wrapper')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footerjs')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.column-search-input').forEach(function (input) {
        input.addEventListener('change', function () {
            let column = this.dataset.column;
            let value = encodeURIComponent(this.value);
            let url = this.dataset.url + '?action=search&column_search_' + column + '=' + value;
            NX.ajaxUxRequest(url);
        });
    });

    const clearBtn = document.querySelector('.clear-column-searches');
    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            document.querySelectorAll('.column-search-input').forEach(function (input) {
                input.value = '';
            });
            NX.ajaxUxRequest('{{ urlResource('/invoice-settlements') }}?action=search');
        });
    }
});
</script>
@endsection

```

```blade
@include('pages.invoice-settlements.components.table.table')

@include('pages.invoice-settlements.components.misc.filter-panel')

@include('pages.invoice-settlements.components.misc.table-config')

```

```blade
<div class="right-sidebar" id="table-config-invoice-settlements">
    <form id="table-config-invoice-settlements-form">
        <div class="slimscrollright">
            <div class="rpanel-title">
                <i class="icon-Filter-2"></i>{{ cleanLang(__('lang.table_settings')) }}
                <span>
                    <i class="ti-close js-close-side-panels" data-target="table-config-invoice-settlements"></i>
                </span>
            </div>
            <div class="r-panel-body table-config-ajax"
                data-url="{{ url('preferences/tables') }}"
                data-type="form"
                data-form-id="table-config-invoice-settlements-form"
                data-ajax-type="post"
                data-progress-bar="hidden">

                @php
                    $columns = [
                        1 => __('lang.document_number'),
                        2 => __('lang.document_date'),
                        3 => __('lang.customer_name'),
                        4 => __('lang.base_net_amount'),
                        5 => __('lang.paid_amount'),
                        6 => __('lang.balance_amount'),
                        7 => __('lang.currency'),
                        8 => __('lang.created_by'),
                        9 => __('lang.created_at'),
                    ];
                @endphp

                @foreach($columns as $index => $label)
                <div class="p-b-5">
                    <label class="custom-control custom-checkbox table-config-checkbox-container">
                        <input name="tableconfig_column_{{ $index }}" type="checkbox"
                            class="custom-control-input table-config-checkbox cursor-pointer"
                            {{ runtimePrechecked(config('table.tableconfig_column_'.$index)) }}>
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">{{ cleanLang($label) }}</span>
                    </label>
                </div>
                @endforeach
            </div>
            <input type="hidden" name="tableconfig_table_name" value="invoice_settlements">
            <div class="buttons-block">
                <button type="button" class="btn btn-rounded-x btn-secondary js-close-side-panels"
                    data-target="table-config-invoice-settlements">{{ cleanLang(__('lang.close')) }}</button>
            </div>
        </div>
    </form>
</div>

```

```blade
<div class="right-sidebar" id="sidepanel-filter-invoice-settlements">
    <form>
        <div class="slimscrollright">
            <div class="rpanel-title">
                <i class="icon-Filter-2"></i>{{ cleanLang(__('lang.filter_invoice_settlements')) }}
                <span>
                    <i class="ti-close js-close-side-panels" data-target="sidepanel-filter-invoice-settlements"></i>
                </span>
            </div>
            <div class="r-panel-body">
                <div class="filter-block">
                    <div class="title">{{ cleanLang(__('lang.document_number')) }}</div>
                    <div class="fields">
                        <input type="text" name="filter_document_number" class="form-control form-control-sm"
                            placeholder="{{ cleanLang(__('lang.document_number')) }}">
                    </div>
                </div>

                <div class="filter-block">
                    <div class="title">{{ cleanLang(__('lang.customer_name')) }}</div>
                    <div class="fields">
                        <input type="text" name="filter_customer_name" class="form-control form-control-sm"
                            placeholder="{{ cleanLang(__('lang.customer_name')) }}">
                    </div>
                </div>

                <div class="filter-block">
                    <div class="title">{{ cleanLang(__('lang.base_net_amount')) }}</div>
                    <div class="fields">
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="number" step="0.01" name="filter_base_net_amount_min"
                                    class="form-control form-control-sm" placeholder="{{ cleanLang(__('lang.min')) }}">
                            </div>
                            <div class="col-6">
                                <input type="number" step="0.01" name="filter_base_net_amount_max"
                                    class="form-control form-control-sm" placeholder="{{ cleanLang(__('lang.max')) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="filter-block">
                    <div class="title">{{ cleanLang(__('lang.paid_amount')) }}</div>
                    <div class="fields">
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="number" step="0.01" name="filter_paid_amount_min"
                                    class="form-control form-control-sm" placeholder="{{ cleanLang(__('lang.min')) }}">
                            </div>
                            <div class="col-6">
                                <input type="number" step="0.01" name="filter_paid_amount_max"
                                    class="form-control form-control-sm" placeholder="{{ cleanLang(__('lang.max')) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="filter-block">
                    <div class="title">{{ cleanLang(__('lang.balance_amount')) }}</div>
                    <div class="fields">
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="number" step="0.01" name="filter_balance_amount_min"
                                    class="form-control form-control-sm" placeholder="{{ cleanLang(__('lang.min')) }}">
                            </div>
                            <div class="col-6">
                                <input type="number" step="0.01" name="filter_balance_amount_max"
                                    class="form-control form-control-sm" placeholder="{{ cleanLang(__('lang.max')) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="filter-block">
                    <div class="title">{{ cleanLang(__('lang.document_date')) }}</div>
                    <div class="fields">
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="text" name="filter_document_date_start" class="form-control form-control-sm"
                                    placeholder="{{ cleanLang(__('lang.start')) }}">
                            </div>
                            <div class="col-6">
                                <input type="text" name="filter_document_date_end" class="form-control form-control-sm"
                                    placeholder="{{ cleanLang(__('lang.end')) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="filter-block">
                    <div class="title">{{ cleanLang(__('lang.currency')) }}</div>
                    <div class="fields">
                        <select name="filter_currency[]" class="form-control form-control-sm select2-basic" multiple>
                            <option value="IRR">IRR</option>
                        </select>
                    </div>
                </div>

                <div class="buttons-block">
                    <button type="button" class="btn btn-rounded-x btn-secondary js-reset-filter-side-panel">
                        {{ cleanLang(__('lang.reset')) }}
                    </button>
                    <button type="button"
                        class="btn btn-rounded-x btn-danger js-ajax-ux-request apply-filter-button"
                        data-url="{{ urlResource('/invoice-settlements/search') }}"
                        data-type="form"
                        data-ajax-type="GET">
                        {{ cleanLang(__('lang.apply_filter')) }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

```

```blade
<div class="card count-{{ @count($settlements ?? []) }}" id="invoice-settlements-table-wrapper">
    <div class="card-body">
        <div class="table-responsive list-table-wrapper min-h-400">
            @if(@count($settlements ?? []) > 0)
            <table class="table m-t-0 m-b-0 table-hover no-wrap contact-list" data-page-size="10">
                <thead>
                    <tr>
                        <!-- document number -->
                        <th class="invoice_settlements_col_tableconfig_column_1 {{ config('table.tableconfig_column_1') }} tableconfig_column_1">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_document_number"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/invoice-settlements?action=sort&orderby=document_number&sortorder=asc') }}">
                                    {{ cleanLang(__('lang.document_number')) }}
                                    <span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                </a>
                                <span class="column-filter-dropdown" data-column="document_number">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!-- document date -->
                        <th class="invoice_settlements_col_tableconfig_column_2 {{ config('table.tableconfig_column_2') }} tableconfig_column_2">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_document_date"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/invoice-settlements?action=sort&orderby=document_date&sortorder=asc') }}">
                                    {{ cleanLang(__('lang.document_date')) }}
                                    <span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                </a>
                                <span class="column-filter-dropdown" data-column="document_date">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!-- customer -->
                        <th class="invoice_settlements_col_tableconfig_column_3 {{ config('table.tableconfig_column_3') }} tableconfig_column_3">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_customer_name"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/invoice-settlements?action=sort&orderby=customer_name&sortorder=asc') }}">
                                    {{ cleanLang(__('lang.customer_name')) }}
                                    <span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                </a>
                                <span class="column-filter-dropdown" data-column="customer_name">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!-- base net -->
                        <th class="invoice_settlements_col_tableconfig_column_4 {{ config('table.tableconfig_column_4') }} tableconfig_column_4">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_base_net_amount"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/invoice-settlements?action=sort&orderby=base_net_amount&sortorder=asc') }}">
                                    {{ cleanLang(__('lang.base_net_amount')) }}
                                    <span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                </a>
                            </div>
                        </th>

                        <!-- paid -->
                        <th class="invoice_settlements_col_tableconfig_column_5 {{ config('table.tableconfig_column_5') }} tableconfig_column_5">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_paid_amount"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/invoice-settlements?action=sort&orderby=paid_amount&sortorder=asc') }}">
                                    {{ cleanLang(__('lang.paid_amount')) }}
                                    <span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                </a>
                            </div>
                        </th>

                        <!-- balance -->
                        <th class="invoice_settlements_col_tableconfig_column_6 {{ config('table.tableconfig_column_6') }} tableconfig_column_6">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_balance_amount"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/invoice-settlements?action=sort&orderby=balance_amount&sortorder=asc') }}">
                                    {{ cleanLang(__('lang.balance_amount')) }}
                                    <span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                </a>
                            </div>
                        </th>

                        <!-- currency -->
                        <th class="invoice_settlements_col_tableconfig_column_7 {{ config('table.tableconfig_column_7') }} tableconfig_column_7">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_currency"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/invoice-settlements?action=sort&orderby=currency&sortorder=asc') }}">
                                    {{ cleanLang(__('lang.currency')) }}
                                    <span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                </a>
                            </div>
                        </th>

                        <!-- creator -->
                        <th class="invoice_settlements_col_tableconfig_column_8 {{ config('table.tableconfig_column_8') }} tableconfig_column_8">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_creator"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/invoice-settlements?action=sort&orderby=creator_id&sortorder=asc') }}">
                                    {{ cleanLang(__('lang.created_by')) }}
                                    <span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                </a>
                                <span class="column-filter-dropdown" data-column="creator">
                                    <i class="ti-angle-down"></i>
                                </span>
                            </div>
                        </th>

                        <!-- created at -->
                        <th class="invoice_settlements_col_tableconfig_column_9 {{ config('table.tableconfig_column_9') }} tableconfig_column_9">
                            <div class="column-header-container">
                                <a class="js-ajax-ux-request" id="sort_created_at"
                                    href="javascript:void(0)"
                                    data-url="{{ urlResource('/invoice-settlements?action=sort&orderby=created_at&sortorder=asc') }}">
                                    {{ cleanLang(__('lang.created_at')) }}
                                    <span class="sorting-icons"><i class="ti-arrows-vertical"></i></span>
                                </a>
                            </div>
                        </th>

                        <th class="invoice_settlements_col_action with-table-config-icon actions_column">
                            <span>{{ cleanLang(__('lang.columns')) }}</span>
                            <div class="table-config-icon">
                                <span class="text-default js-toggle-table-config-panel"
                                    data-target="table-config-invoice-settlements">
                                    <i class="sl-icon-settings"></i>
                                </span>
                            </div>
                        </th>
                    </tr>

                    <tr class="column-search-row" style="background-color:#f8f9fa;">
                        <th class="invoice_settlements_col_tableconfig_column_1 {{ config('table.tableconfig_column_1') }} tableconfig_column_1">
                            <input type="text" class="form-control form-control-sm column-search-input"
                                placeholder="{{ cleanLang(__('lang.search')) }}"
                                data-column="document_number"
                                data-url="{{ urlResource('/invoice-settlements') }}">
                        </th>
                        <th class="invoice_settlements_col_tableconfig_column_2 {{ config('table.tableconfig_column_2') }} tableconfig_column_2">
                            <input type="text" class="form-control form-control-sm column-search-input"
                                placeholder="{{ cleanLang(__('lang.search')) }}"
                                data-column="document_date"
                                data-url="{{ urlResource('/invoice-settlements') }}">
                        </th>
                        <th class="invoice_settlements_col_tableconfig_column_3 {{ config('table.tableconfig_column_3') }} tableconfig_column_3">
                            <input type="text" class="form-control form-control-sm column-search-input"
                                placeholder="{{ cleanLang(__('lang.search')) }}"
                                data-column="customer_name"
                                data-url="{{ urlResource('/invoice-settlements') }}">
                        </th>
                        <th class="invoice_settlements_col_tableconfig_column_4 {{ config('table.tableconfig_column_4') }} tableconfig_column_4"></th>
                        <th class="invoice_settlements_col_tableconfig_column_5 {{ config('table.tableconfig_column_5') }} tableconfig_column_5"></th>
                        <th class="invoice_settlements_col_tableconfig_column_6 {{ config('table.tableconfig_column_6') }} tableconfig_column_6"></th>
                        <th class="invoice_settlements_col_tableconfig_column_7 {{ config('table.tableconfig_column_7') }} tableconfig_column_7">
                            <input type="text" class="form-control form-control-sm column-search-input"
                                placeholder="{{ cleanLang(__('lang.search')) }}"
                                data-column="currency"
                                data-url="{{ urlResource('/invoice-settlements') }}">
                        </th>
                        <th class="invoice_settlements_col_tableconfig_column_8 {{ config('table.tableconfig_column_8') }} tableconfig_column_8">
                            <input type="text" class="form-control form-control-sm column-search-input"
                                placeholder="{{ cleanLang(__('lang.search')) }}"
                                data-column="creator"
                                data-url="{{ urlResource('/invoice-settlements') }}">
                        </th>
                        <th class="invoice_settlements_col_tableconfig_column_9 {{ config('table.tableconfig_column_9') }} tableconfig_column_9"></th>
                        <th class="invoice_settlements_col_action actions_column"></th>
                    </tr>
                </thead>
                <tbody id="invoice-settlements-td-container">
                    @include('pages.invoice-settlements.components.table.ajax')
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="10">
                            @include('misc.load-more-button')
                        </td>
                    </tr>
                </tfoot>
            </table>
            @endif

            @if(@count($settlements ?? []) == 0)
                @include('notifications.no-results-found')
            @endif
        </div>
    </div>
</div>

@if($settlements && $settlements->hasPages())
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted">
                {{ cleanLang(__('lang.showing')) }} {{ $settlements->firstItem() }}
                {{ cleanLang(__('lang.to')) }} {{ $settlements->lastItem() }}
                {{ cleanLang(__('lang.of')) }} {{ $settlements->total() }} {{ cleanLang(__('lang.results')) }}
            </div>
            <div>
                {{ $settlements->links() }}
            </div>
        </div>
    </div>
</div>
@endif

```

```blade
@foreach($settlements as $item)
<tr id="invoice_settlement_{{ $item->invoice_settlement_id }}">
    <td class="invoice_settlements_col_tableconfig_column_1 {{ config('table.tableconfig_column_1') }} tableconfig_column_1">
        {{ $item->document_number ?? '---' }}
    </td>
    <td class="invoice_settlements_col_tableconfig_column_2 {{ config('table.tableconfig_column_2') }} tableconfig_column_2">
        {{ $item->document_date ?? '---' }}
    </td>
    <td class="invoice_settlements_col_tableconfig_column_3 {{ config('table.tableconfig_column_3') }} tableconfig_column_3">
        {{ $item->customer_name ?? '---' }}
    </td>
    <td class="invoice_settlements_col_tableconfig_column_4 {{ config('table.tableconfig_column_4') }} tableconfig_column_4">
        {{ formatCurrency($item->base_net_amount, $item->currency ?? 'IRR') }}
    </td>
    <td class="invoice_settlements_col_tableconfig_column_5 {{ config('table.tableconfig_column_5') }} tableconfig_column_5">
        {{ formatCurrency($item->paid_amount, $item->currency ?? 'IRR') }}
    </td>
    <td class="invoice_settlements_col_tableconfig_column_6 {{ config('table.tableconfig_column_6') }} tableconfig_column_6">
        {{ formatCurrency($item->balance_amount, $item->currency ?? 'IRR') }}
    </td>
    <td class="invoice_settlements_col_tableconfig_column_7 {{ config('table.tableconfig_column_7') }} tableconfig_column_7">
        {{ $item->currency ?? 'IRR' }}
    </td>
    <td class="invoice_settlements_col_tableconfig_column_8 {{ config('table.tableconfig_column_8') }} tableconfig_column_8">
        {{ optional($item->creator)->first_name }} {{ optional($item->creator)->last_name }}
    </td>
    <td class="invoice_settlements_col_tableconfig_column_9 {{ config('table.tableconfig_column_9') }} tableconfig_column_9">
        {{ runtimeDate($item->created_at) }}
    </td>
    <td class="invoice_settlements_col_action actions_column text-muted">—</td>
</tr>
@endforeach

```

```blade
<div class="row">
    <div class="col-12">
        @include('pages.invoice-settlements.components.table.table')
    </div>
</div>

```

```blade
@extends('layout.wrapper')

@section('content')
<div class="content">
    <div class="page-header">
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <h3 class="page-title">{{ cleanLang(__('lang.import_invoice_settlements')) }}</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">{{ cleanLang(__('lang.dashboard')) }}</a></li>
                    <li class="breadcrumb-item"><a href="/accounting">{{ cleanLang(__('lang.accounting')) }}</a></li>
                    <li class="breadcrumb-item active">{{ cleanLang(__('lang.invoice_settlements')) }}</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ cleanLang(__('lang.import_invoice_settlements_data')) }}</h5>
                        <p class="text-muted">{{ cleanLang(__('lang.import_invoice_settlements_description')) }}</p>

                        @if(session('import_results'))
                            @php $results = session('import_results'); @endphp
                            <div class="alert alert-{{ $results['success'] ? 'success' : 'danger' }} alert-dismissible fade show" role="alert">
                                <h6 class="alert-heading">{{ cleanLang(__('lang.import_results')) }}</h6>
                                <p>{{ $results['message'] }}</p>
                                <hr>
                                <p class="mb-0">
                                    <strong>{{ cleanLang(__('lang.imported')) }}:</strong> {{ $results['imported'] }} |
                                    <strong>{{ cleanLang(__('lang.skipped')) }}:</strong> {{ $results['skipped'] }}
                                </p>
                                @if(!empty($results['skipped_details']))
                                    <hr>
                                    <h6 class="text-warning">{{ cleanLang(__('lang.skipped_details')) }}</h6>
                                    <div class="table-responsive mt-2">
                                        <table class="table table-sm table-bordered">
                                            <thead class="table-warning">
                                                <tr>
                                                    <th>{{ cleanLang(__('lang.row_number')) }}</th>
                                                    <th>{{ cleanLang(__('lang.reason')) }}</th>
                                                    <th>{{ cleanLang(__('lang.document_number')) }}</th>
                                                    <th>{{ cleanLang(__('lang.customer_name')) }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($results['skipped_details'] as $detail)
                                                <tr>
                                                    <td>{{ $detail['row_number'] }}</td>
                                                    <td>{{ $detail['reason'] }}</td>
                                                    <td>{{ $detail['document_number'] ?? '-' }}</td>
                                                    <td>{{ $detail['customer_name'] ?? '-' }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form id="invoice-settlements-import-form" method="POST" action="/import/invoice-settlements" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ cleanLang(__('lang.select_file')) }} <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" name="attachments[]" accept=".xlsx,.xls,.csv" required>
                                        <small class="form-text text-muted">
                                            {{ cleanLang(__('lang.supported_formats')) }}: XLSX, XLS, CSV ({{ cleanLang(__('lang.max_file_size')) }}: 10MB)
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h6>{{ cleanLang(__('lang.sample_format')) }}</h6>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>{{ cleanLang(__('lang.document_number')) }}</th>
                                                    <th>{{ cleanLang(__('lang.document_date')) }}</th>
                                                    <th>{{ cleanLang(__('lang.customer_name')) }}</th>
                                                    <th>{{ cleanLang(__('lang.base_net_amount')) }}</th>
                                                    <th>{{ cleanLang(__('lang.paid_amount')) }}</th>
                                                    <th>{{ cleanLang(__('lang.balance_amount')) }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>7240</td>
                                                    <td>1404/04/17</td>
                                                    <td>Sample Customer</td>
                                                    <td>1358500000</td>
                                                    <td>500000000</td>
                                                    <td>858500000</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti-upload"></i> {{ cleanLang(__('lang.import_data')) }}
                                        </button>
                                        <a href="/invoice-settlements" class="btn btn-secondary">
                                            <i class="ti-arrow-left"></i> {{ cleanLang(__('lang.back_to_invoice_settlements')) }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footerjs')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('invoice-settlements-import-form');
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="ti-spinner"></i> {{ cleanLang(__('lang.uploading')) }}';

        fetch('/import/invoice-settlements', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        }).then(response => response.json())
          .then(data => {
            alert(data.message);
            if (data.success) {
                location.reload();
            }
          }).catch(() => {
            alert('{{ cleanLang(__('lang.import_failed_generic')) }}');
          }).finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="ti-upload"></i> {{ cleanLang(__('lang.import_data')) }}';
          });
    });
});
</script>
@endsection

```

```php
<?php

// Include debug routes
require_once __DIR__ . '/debug.php';

// Include test middleware routes
require_once __DIR__ . '/test-middleware.php';

// Include live debug routes
require_once __DIR__ . '/debug-live.php';

// Include test inventory routes
require_once __DIR__ . '/test-inventory.php';

//TESTING [DEV]
Route::get("test", "Test@index");
Route::post("test", "Test@index");

//RTL TEST
Route::get("test-rtl", function() {
    return view('test-rtl');
});

//HOME PAGE
Route::any('/', function () {
    return redirect('/home');
});
Route::any('home', 'Home@index')->name('home');
Route::post('home/update-stats', 'Home@updateIncomeExpensesChart');

//LOGIN & SIGNUP
Route::get("/login", "Authenticate@logIn")->name('login');
Route::post("/login", "Authenticate@logInAction");
Route::get("/forgotpassword", "Authenticate@forgotPassword");
Route::post("/forgotpassword", "Authenticate@forgotPasswordAction");
Route::get("/signup", "Authenticate@signUp");
Route::post("/signup", "Authenticate@signUpAction");
Route::get("/resetpassword", "Authenticate@resetPassword");
Route::post("/resetpassword", "Authenticate@resetPasswordAction");
Route::get("/access", "Authenticate@directLoginAccess"); //SAAS

//LOGOUT
Route::any('logout', function () {
    Auth::logout();
    return redirect('/login');
});

//CLIENTS
Route::group(['prefix' => 'clients'], function () {
    Route::any("/search", "Clients@index");
    Route::post("/delete", "Clients@destroy")->middleware(['demoModeCheck']);
    Route::get("/change-category", "Clients@changeCategory");
    Route::post("/change-category", "Clients@changeCategoryUpdate");
    Route::get("/{client}/client-details", "Clients@details")->where('client', '[0-9]+');
    Route::post("/{client}/client-details", "Clients@updateDescription")->where('client', '[0-9]+');
    Route::get("/logo", "Clients@logo");
    Route::put("/logo", "Clients@updateLogo")->middleware(['demoModeCheck']);
    Route::get("/{client}/billing-details", "Clients@editBillingDetails")->where('client', '[0-9]+');
    Route::put("/{client}/billing-details", "Clients@updatebillingDetails")->where('client', '[0-9]+');
    Route::get("/{client}/change-account-owner", "Clients@changeAccountOwner");
    Route::post("/{client}/change-account-owner", "Clients@changeAccountOwnerUpdate");
    Route::get("/{client}/pinning", "Clients@togglePinning")->where('client', '[0-9]+');
    Route::get("/{client}/impersonate", "Clients@ImpersonateClient")->where('client', '[0-9]+');
    //dynamic load
    Route::any("/{client}/{section}", "Clients@showDynamic")
        ->where(['client' => '[0-9]+', 'section' => 'details|contacts|projects|files|client-files|tickets|invoices|expenses|payments|timesheets|estimates|notes|project-files|client-files']);
});
Route::any("/client/{x}/profile", "Clients@profile")->where('x', '[0-9]+');
Route::resource('clients', 'Clients');

//CONTACTS
Route::group(['prefix' => 'contacts'], function () {
    Route::any("/search", "Contacts@index");
    Route::get("/updatepreferences", "Contacts@updatePreferences");
    Route::post("/delete", "Contacts@destroy")->middleware(['demoModeCheck']);
});
Route::resource('contacts', 'Contacts');
Route::resource('users', 'Contacts');

//TEAM
Route::group(['prefix' => 'team'], function () {
    Route::any("/search", "Team@index");
    Route::get("/updatepreferences", "Team@updatePreferences");
});
Route::resource('team', 'Team');

//SETTINGS - USER
Route::group(['prefix' => 'user'], function () {
    Route::get("/avatar", "User@avatar");
    Route::put("/avatar", "User@updateAvatar")->middleware(['demoModeCheck']);
    Route::get("/notifications", "User@notifications");
    Route::put("/notifications", "User@updateNotifications");
    Route::get("/updatepassword", "User@updatePassword");
    Route::put("/updatepassword", "User@updatePasswordAction")->middleware(['demoModeCheck']);
    Route::get("/updatenotifications", "User@updateNotifications");
    Route::put("/updatenotifications", "User@updateNotificationsAction")->middleware(['demoModeCheck']);
    Route::post("/updatelanguage", "User@updateLanguage")->middleware(['demoModeCheck']);
    Route::get("/updatetheme", "User@updateTheme");
    Route::put("/updatetheme", "User@updateThemeAction")->middleware(['demoModeCheck']);
});

//INVOICES
Route::group(['prefix' => 'invoices'], function () {
    Route::any("/search", "Invoices@index");
    Route::post("/delete", "Invoices@destroy")->middleware(['demoModeCheck']);
    Route::get("/change-category", "Invoices@changeCategory");
    Route::post("/change-category", "Invoices@changeCategoryUpdate");
    Route::get("/add-payment", "Invoices@addPayment");
    Route::post("/add-payment", "Invoices@addPayment");
    Route::get("/{invoice}/clone", "Invoices@createClone")->where('invoice', '[0-9]+');
    Route::post("/{invoice}/clone", "Invoices@storeClone")->where('invoice', '[0-9]+');
    Route::get("/{invoice}/stop-recurring", "Invoices@stopRecurring")->where('invoice', '[0-9]+');
    Route::get("/{invoice}/attach-project", "Invoices@attachProject")->where('invoice', '[0-9]+');
    Route::post("/{invoice}/attach-project", "Invoices@attachProjectUpdate")->where('invoice', '[0-9]+');
    Route::get("/{invoice}/detach-project", "Invoices@dettachProject")->where('invoice', '[0-9]+');
    Route::get("/{invoice}/email-client", "Invoices@emailClient")->where('invoice', '[0-9]+');
    Route::get("/{invoice}/download-pdf", "Invoices@downloadPDF")->where('invoice', '[0-9]+');
    Route::get("/{invoice}/recurring-settings", "Invoices@recurringSettings")->where('invoice', '[0-9]+');
    Route::post("/{invoice}/recurring-settings", "Invoices@recurringSettingsUpdate")->where('invoice', '[0-9]+');
    Route::get("/{invoice}/edit-invoice", "Invoices@show")->where('invoice', '[0-9]+')->middleware(['invoicesMiddlewareEdit', 'invoicesMiddlewareShow']);
    Route::post("/{invoice}/edit-invoice", "Invoices@saveInvoice")->where('invoice', '[0-9]+');
    Route::get("/{invoice}/pdf", "Invoices@show")->where('invoice', '[0-9]+')->middleware(['invoicesMiddlewareShow']);
    Route::get("/{invoice}/publish", "Invoices@publishInvoice")->where('invoice', '[0-9]+')->middleware(['invoicesMiddlewareEdit', 'invoicesMiddlewareShow']);
    Route::post("/{invoice}/publish/scheduled", "Invoices@publishScheduledInvoice")->where('invoice', '[0-9]+')->middleware(['invoicesMiddlewareEdit', 'invoicesMiddlewareShow']);
    Route::get("/{invoice}/resend", "Invoices@resendInvoice")->where('invoice', '[0-9]+')->middleware(['invoicesMiddlewareEdit', 'invoicesMiddlewareShow']);
    Route::get("/{invoice}/overdue-reminder", "Invoices@overdueReminder")->where('invoice', '[0-9]+');
    Route::get("/{invoice}/stripe-payment", "Invoices@paymentStripe")->where('invoice', '[0-9]+');
    Route::get("/{invoice}/paypal-payment", "Invoices@paymentPaypal")->where('invoice', '[0-9]+');
    Route::get("/timebilling/{project}/", "Timebilling@index")->where('project', '[0-9]+');
    Route::get("/{invoice}/razorpay-payment", "Invoices@paymentRazorpay")->where('invoice', '[0-9]+');
    Route::get("/{invoice}/mollie-payment", "Invoices@paymentMollie")->where('invoice', '[0-9]+');
    Route::get("/{invoice}/tap-payment", "Invoices@paymentTap")->where('invoice', '[0-9]+');
    Route::get("/{invoice}/paystack-payment", "Invoices@paymentPaystack")->where('invoice', '[0-9]+');
    Route::post("/{invoice}/attach-files", "Invoices@attachFiles")->where('estimate', '[0-9]+');
    Route::get("/delete-attachment", "Invoices@deleteFile");
    Route::post("/{invoice}/change-tax-type", "Invoices@updateTaxType")->where('invoice', '[0-9]+');
    Route::get("/{invoice}/pinning", "Invoices@togglePinning")->where('invoice', '[0-9]+');
    Route::post("/bulk-dettach-project", "Invoices@bulkDettachFromProject");
    Route::post("/bulk-email-client", "Invoices@bulkEmailClient");
    Route::get('/{invoice}/taskbilling', 'Invoices@taskBilling')->where('id', '[0-9]+');

    //view from email link
    Route::get("/redirect/{invoice}", "Invoices@redirectURL")->where('invoice', '[0-9]+');
});
Route::resource('invoices', 'Invoices');

//SUBSCRIPTIONS
Route::group(['prefix' => 'subscriptions'], function () {
    Route::any("/search", "Subscriptions@index");
    Route::post("/delete", "Subscriptions@destroy")->middleware(['demoModeCheck']);
    Route::get("/change-category", "Subscriptions@changeCategory");
    Route::post("/change-category", "Subscriptions@changeCategoryUpdate");
    Route::get("/getprices", "Subscriptions@getProductPrices");
    Route::get("/{subscription}/invoices", "Subscriptions@subscriptionInvoices")->where('subscription', '[0-9]+');
    Route::get("/{subscription}/pay", "Subscriptions@setupStripePayment")->where('subscription', '[0-9]+');
    Route::get("/{subscription}/cancel", "Subscriptions@cancelSubscription")->where('subscription', '[0-9]+')->middleware(['demoModeCheck']);
    Route::get("/{subscription}/pinning", "Subscriptions@togglePinning")->where('subscription', '[0-9]+');

});
Route::resource('subscriptions', 'Subscriptions');

//ESTIMATES
Route::group(['prefix' => 'estimates'], function () {
    Route::any("/search", "Estimates@index");
    Route::post("/delete", "Estimates@destroy")->middleware(['demoModeCheck']);
    Route::get("/change-category", "Estimates@changeCategory");
    Route::post("/change-category", "Estimates@changeCategoryUpdate");
    Route::get("/{estimate}/attach-project", "Estimates@attachProject")->where('estimate', '[0-9]+');
    Route::post("/{estimate}/attach-project", "Estimates@attachProjectUpdate")->where('invoice', '[0-9]+');
    Route::get("/{estimate}/detach-project", "Estimates@dettachProject")->where('estimate', '[0-9]+');
    Route::get("/{estimate}/email-client", "Estimates@emailClient")->where('estimate', '[0-9]+');
    Route::get("/{estimate}/convert-to-invoice", "Estimates@convertToInvoice")->where('estimate', '[0-9]+');
    Route::get("/{estimate}/change-status", "Estimates@changeStatus")->where('estimate', '[0-9]+');
    Route::post("/{estimate}/change-status", "Estimates@changeStatusUpdate")->where('estimate', '[0-9]+');
    Route::get("/{estimate}/edit-estimate", "Estimates@show")->middleware(['estimatesMiddlewareEdit', 'estimatesMiddlewareShow']);
    Route::post("/{estimate}/edit-estimate", "Estimates@saveEstimate");
    Route::get("/view/{estimate}/pdf", "Estimates@showPublic");
    Route::get("/{estimate}/publish", "Estimates@publishEstimate")->where('estimate', '[0-9]+')->middleware(['estimatesMiddlewareEdit', 'estimatesMiddlewareShow']);
    Route::post("/{estimate}/publish/scheduled", "Estimates@publishScheduledEstimate")->where('estimate', '[0-9]+')->middleware(['estimatesMiddlewareEdit', 'estimatesMiddlewareShow']);
    Route::get("/{estimate}/publish-revised", "Estimates@publishRevisedEstimate")->where('estimate', '[0-9]+')->middleware(['estimatesMiddlewareEdit', 'estimatesMiddlewareShow']);
    Route::get("/{estimate}/resend", "Estimates@resendEstimate")->where('estimate', '[0-9]+')->middleware(['estimatesMiddlewareEdit', 'estimatesMiddlewareShow']);
    Route::get("/{estimate}/accept", "Estimates@acceptEstimate");
    Route::get("/{estimate}/decline", "Estimates@declineEstimate");
    Route::get("/{estimate}/convert-to-invoice", "Estimates@convertToInvoice")->where('invoice', '[0-9]+');
    Route::post("/{estimate}/convert-to-invoice", "Estimates@convertToInvoiceAction")->where('invoice', '[0-9]+');
    Route::get("/{estimate}/clone", "Estimates@createClone")->where('estimate', '[0-9]+');
    Route::post("/{estimate}/clone", "Estimates@storeClone")->where('estimate', '[0-9]+');
    Route::get("/{estimate}/edit-automation", "Estimates@editAutomation")->where('estimate', '[0-9]+');
    Route::post("/{estimate}/edit-automation", "Estimates@updateAutomation")->where('estimate', '[0-9]+');
    Route::post("/{estimate}/attach-files", "Estimates@attachFiles")->where('estimate', '[0-9]+');
    Route::get("/delete-attachment", "Estimates@deleteFile");
    Route::post("/{estimate}/change-tax-type", "Estimates@updateTaxType")->where('estimate', '[0-9]+');
    Route::get("/view/{estimate}", "Estimates@showPublic");
    Route::get("/{estimate}/pinning", "Estimates@togglePinning")->where('estimate', '[0-9]+');
    Route::post("/bulk-email-client", "Estimates@bulkEmailClient");
    Route::get("/bulk-change-status", "Estimates@bulkChangeStatus");
    Route::post("/bulk-change-status-update", "Estimates@bulkChangeStatusUpdate");
    Route::get("/bulk-convert-to-invoice", "Estimates@bulkConvertToInvoice");
    Route::post("/bulk-convert-to-invoice-action", "Estimates@bulkConvertToInvoiceAction");
});
Route::resource('estimates', 'Estimates');

//PAYMENTS
Route::group(['prefix' => 'payments'], function () {
    Route::any("/search", "Payments@index");
    Route::post("/delete", "Payments@destroy")->middleware(['demoModeCheck']);
    Route::get("/change-category", "Payments@changeCategory");
    Route::post("/change-category", "Payments@changeCategoryUpdate");
    Route::any("/v/{payment}", "Payments@index")->where('payment', '[0-9]+');
    Route::any("/thankyou", "Payments@thankYou");
    Route::post("/thankyou/razorpay", "Payments@thankYouRazorpay");
    Route::get("/thankyou/tap", "Payments@thankYouTap");
    Route::get("/{payment}/pinning", "Payments@togglePinning")->where('payment', '[0-9]+');

});
Route::resource('payments', 'Payments');

//ITEMS
Route::group(['prefix' => 'items'], function () {
    Route::any("/search", "Items@index");
    Route::any("/category", "Items@categoryItems");
    Route::post("/delete", "Items@destroy")->middleware(['demoModeCheck']);
    Route::get("/change-category", "Items@changeCategory");
    Route::post("/change-category", "Items@changeCategoryUpdate");
    Route::get("/{item}/tasks", "Items@indexTasks")->where('item', '[0-9]+');
    Route::delete("/tasks/{task}", "Items@destroyTask")->where('task', '[0-9]+');
    Route::get("/tasks/create", "Items@createTask");
    Route::post("/tasks", "Items@storeTask");
    Route::get("/tasks/{task}/edit", "Items@editTask")->where('task', '[0-9]+');
    Route::put("/tasks/{task}", "Items@updateTask")->where('task', '[0-9]+');
    Route::get("/{item}/pinning", "Items@togglePinning")->where('item', '[0-9]+');

});
Route::resource('items', 'Items');

//PRODUCTS (same as items above)
Route::group(['prefix' => 'products'], function () {
    Route::any("/search", "Items@index");
    Route::post("/delete", "Items@destroy")->middleware(['demoModeCheck']);
    Route::get("/change-category", "Items@changeCategory");
    Route::post("/change-category", "Items@changeCategoryUpdate");
});
Route::resource('products', 'Items');

//EXPENSES
Route::group(['prefix' => 'expenses'], function () {
    Route::any("/search", "Expenses@index");
    Route::get("/attachments/download/{uniqueid}", "Expenses@downloadAttachment");
    Route::delete("/attachments/{uniqueid}", "Expenses@deleteAttachment")->middleware(['demoModeCheck']);
    Route::post("/delete", "Expenses@destroy")->middleware(['demoModeCheck']);
    Route::get("/{expense}/attach-dettach", "Expenses@attachDettach")->where('invoice', '[0-9]+');
    Route::post("/{expense}/attach-dettach", "Expenses@attachDettachUpdate")->where('invoice', '[0-9]+');
    Route::get("/change-category", "Expenses@changeCategory");
    Route::post("/change-category", "Expenses@changeCategoryUpdate");
    Route::get("/{expense}/create-new-invoice", "Expenses@createNewInvoice")->where('expense', '[0-9]+');
    Route::post("/{expense}/create-new-invoice", "Expenses@createNewInvoice")->where('expense', '[0-9]+');
    Route::get("/{expense}/add-to-invoice", "Expenses@addToInvoice")->where('expense', '[0-9]+');
    Route::post("/{expense}/add-to-invoice", "Expenses@addToInvoice")->where('expense', '[0-9]+');
    Route::any("/v/{expense}", "Expenses@index")->where('expense', '[0-9]+');
    Route::get("/{expense}/pinning", "Expenses@togglePinning")->where('expense', '[0-9]+');
    Route::get("/{expense}/recurring-settings", "Expenses@recurringSettings")->where('expense', '[0-9]+');
    Route::post("/{expense}/recurring-settings", "Expenses@recurringSettingsUpdate")->where('expense', '[0-9]+');
    Route::get("/{expense}/stop-recurring", "Expenses@stopRecurring")->where('expense', '[0-9]+');
    Route::get("/{expense}/clone", "Expenses@createClone")->where('project', '[0-9]+');
    Route::post("/{expense}/clone", "Expenses@storeClone")->where('project', '[0-9]+');
});
Route::resource('expenses', 'Expenses');

//PROJECTS & PROJECT
Route::group(['prefix' => 'projects'], function () {
    Route::any("/search", "Projects@index");
    Route::post("/delete", "Projects@destroy")->middleware(['demoModeCheck']);
    Route::get("/change-category", "Projects@changeCategory");
    Route::post("/change-category", "Projects@changeCategoryUpdate");
    Route::get("/{project}/change-status", "Projects@changeStatus")->where('project', '[0-9]+');
    Route::post("/{project}/change-status", "Projects@changeStatusUpdate")->where('project', '[0-9]+');
    Route::get("/{project}/project-details", "Projects@details")->where('project', '[0-9]+');
    Route::post("/{project}/project-details", "Projects@updateDescription")->where('project', '[0-9]+');
    Route::put("/{project}/stop-all-timers", "Projects@stopAllTimers")->where('project', '[0-9]+');
    Route::put("/{project}/archive", "Projects@archive")->where('project', '[0-9]+');
    Route::put("/{project}/activate", "Projects@activate")->where('project', '[0-9]+');
    Route::get("/{project}/clone", "Projects@createClone")->where('project', '[0-9]+');
    Route::post("/{project}/clone", "Projects@storeClone")->where('project', '[0-9]+');
    Route::get("/prefill-project", "Projects@prefillProject");
    Route::get("/{project}/progress", "Projects@changeProgress")->where('project', '[0-9]+');
    Route::post("/{project}/progress", "Projects@changeProgressUpdate")->where('project', '[0-9]+');
    Route::get("/{project}/change-cover-image", "Projects@changeCoverImage")->where('project', '[0-9]+');
    Route::post("/{project}/change-cover-image", "Projects@changeCoverImageUpdate")->where('project', '[0-9]+');
    Route::get("/{project}/assigned", "Projects@assignedUsers")->where('project', '[0-9]+');
    Route::put("/{project}/assigned", "Projects@assignedUsersUpdate")->where('project', '[0-9]+');
    Route::get("/{project}/edit-automation", "Projects@editAutomation")->where('project', '[0-9]+');
    Route::post("/{project}/edit-automation", "Projects@updateAutomation")->where('project', '[0-9]+');
    Route::get("/{project}/set-cover-image", "Projects@setFileBasedCoverImage")->where('project', '[0-9]+');
    Route::post("/{project}/remove-cover-image", "Projects@removeCoverImage")->where('project', '[0-9]+');
    Route::get("/change-assigned", "Projects@BulkchangeAssigned");
    Route::post("/change-assigned", "Projects@BulkchangeAssignedUpdate");
    Route::get("/bulk-change-status", "Projects@BulkChangeStatus");
    Route::post("/bulk-change-status", "Projects@BulkChangeStatusUpdate");
    Route::get("/{project}/pinning", "Projects@togglePinning")->where('project', '[0-9]+');
    Route::post("/bulk/archive", "Projects@bulkArchive");
    Route::post("/bulk/restore", "Projects@bulkRestore");
    Route::get("/bulk-change-progress", "Projects@bulkChangeProgress");
    Route::post("/bulk-change-progress", "Projects@bulkChangeProgressUpdate");
    Route::post("/bulk/stop-timers", "Projects@bulkStopTimers");

    //dynamic load
    Route::any("/{project}/{section}", "Projects@showDynamic")
        ->where(['project' => '[0-9]+', 'section' => 'details|comments|files|tasks|invoices|payments|timesheets|expenses|estimates|milestones|tickets|notes|checklists']);
});
Route::resource('projects', 'Projects');

//TASKS
Route::group(['prefix' => 'tasks'], function () {
    Route::any("/search", "Tasks@index");
    Route::any("/timer/{id}/start", "Tasks@timerStart")->where('id', '[0-9]+');
    Route::any("/timer/{id}/stop", "Tasks@timerStop")->where('id', '[0-9]+');
    Route::any("/timer/stop", "Tasks@timerStopUser");
    Route::any("/timer/{id}/stopall", "Tasks@timerStopAll")->where('id', '[0-9]+');
    Route::post("/delete", "Tasks@destroy")->middleware(['demoModeCheck']);
    Route::post("/{task}/toggle-status", "Tasks@toggleStatus")->where('task', '[0-9]+');
    Route::post("/{task}/update-description", "Tasks@updateDescription")->where('task', '[0-9]+');
    Route::post("/{task}/attach-files", "Tasks@attachFiles")->where('task', '[0-9]+');
    Route::delete("/delete-attachment/{uniqueid}", "Tasks@deleteAttachment")->middleware(['demoModeCheck']);
    Route::get("/download-attachment/{uniqueid}", "Tasks@downloadAttachment");
    Route::post("/{task}/post-comment", "Tasks@storeComment")->where('task', '[0-9]+');
    Route::delete("/delete-comment/{commentid}", "Tasks@deleteComment")->where('commentid', '[0-9]+');
    Route::post("/{task}/update-title", "Tasks@updateTitle")->where('task', '[0-9]+');
    Route::post("/{task}/add-checklist", "Tasks@storeChecklist")->where('task', '[0-9]+');
    Route::post("/{task}/import-checklist", "Tasks@importChecklists")->where('task', '[0-9]+');
    Route::post("/update-checklist/{checklistid}", "Tasks@updateChecklist")->where('checklistid', '[0-9]+');
    Route::delete("/delete-checklist/{checklistid}", "Tasks@deleteChecklist")->where('checklistid', '[0-9]+');
    Route::post("/toggle-checklist-status/{checklistid}", "Tasks@toggleChecklistStatus")->where('checklistid', '[0-9]+');
    Route::post("/{task}/update-start-date", "Tasks@updateStartDate")->where('task', '[0-9]+');
    Route::post("/{task}/update-due-date", "Tasks@updateDueDate")->where('task', '[0-9]+');
    Route::post("/{task}/update-status", "Tasks@updateStatus")->where('task', '[0-9]+');
    Route::post("/{task}/update-priority", "Tasks@updatePriority")->where('task', '[0-9]+');
    Route::post("/{task}/update-visibility", "Tasks@updateVisibility")->where('task', '[0-9]+');
    Route::post("/{task}/update-milestone", "Tasks@updateMilestone")->where('task', '[0-9]+');
    Route::post("/{task}/update-assigned", "Tasks@updateAssigned")->where('task', '[0-9]+');
    Route::post("/{task}/update-tags", "Tasks@updateTags")->where('task', '[0-9]+');
    Route::post("/update-position", "Tasks@updatePosition");
    Route::any("/v/{task}/{slug}", "Tasks@index")->where('task', '[0-9]+');
    Route::any("/v/{task}", "Tasks@index")->where('task', '[0-9]+');
    Route::post("/{task}/update-custom", "Tasks@updateCustomFields")->where('task', '[0-9]+');
    Route::put("/{task}/archive", "Tasks@archive")->where('task', '[0-9]+');
    Route::put("/{task}/activate", "Tasks@activate")->where('task', '[0-9]+');
    Route::get("/{task}/clone", "Tasks@cloneTask")->where('task', '[0-9]+');
    Route::post("/{task}/clone", "Tasks@cloneStore")->where('task', '[0-9]+');
    Route::get("/{task}/recurring-settings", "Tasks@recurringSettings")->where('task', '[0-9]+');
    Route::post("/{task}/recurring-settings", "Tasks@recurringSettingsUpdate")->where('task', '[0-9]+');
    Route::get("/{task}/stop-recurring", "Tasks@stopRecurring")->where('task', '[0-9]+');
    Route::post("/{task}/add-dependency", "Tasks@storeDependency")->where('task', '[0-9]+');
    Route::delete("/{task}/delete-dependency", "Tasks@deleteDependency")->where('task', '[0-9]+');
    Route::get("/{task}/add-cover-image", "Tasks@addCoverImage")->where('task', '[0-9]+');
    Route::get("/{task}/remove-cover-image", "Tasks@removeCoverImage")->where('task', '[0-9]+');
    Route::get("/{task}/pinning", "Tasks@togglePinning")->where('task', '[0-9]+');

    //card tabs
    Route::get("/content/{task}/show-main", "Tasks@show")->where('lead', '[0-9]+');
    Route::get("/content/{task}/show-customfields", "Tasks@showCustomFields")->where('task', '[0-9]+');
    Route::get("/content/{task}/edit-customfields", "Tasks@editCustomFields")->where('task', '[0-9]+');
    Route::post("/content/{task}/edit-customfields", "Tasks@updateCustomFields")->where('task', '[0-9]+');
    Route::get("/content/{task}/show-mynotes", "Tasks@showMyNotes")->where('task', '[0-9]+');
    Route::get("/content/{task}/create-mynotes", "Tasks@createMyNotes")->where('task', '[0-9]+');
    Route::get("/content/{task}/edit-mynotes", "Tasks@editMyNotes")->where('task', '[0-9]+');
    Route::delete("/content/{task}/delete-mynotes", "Tasks@deleteMyNotes")->where('task', '[0-9]+');
    Route::post("/content/{task}/edit-mynotes", "Tasks@updateMyNotes")->where('task', '[0-9]+');

    Route::post('/update-checklist-positions', 'Tasks@updateChecklistPositions');
    Route::post('/{task}/import-checklists', 'Tasks@importChecklists');
    Route::post('/{task}/post-checklist-comment', 'Tasks@storeChecklistComment');
    Route::delete('/{task}/delete-checklist-comment', 'Tasks@destroyChecklistComment');
});
Route::resource('tasks', 'Tasks');

//LEADS & LEAD
Route::group(['prefix' => 'leads'], function () {
    Route::any("/search", "Leads@index");
    Route::any("/{lead}/details", "Leads@details")->where('lead', '[0-9]+');
    Route::post("/delete", "Leads@destroy")->middleware(['demoModeCheck']);
    Route::get("/change-category", "Leads@changeCategory");
    Route::post("/change-category", "Leads@changeCategoryUpdate");
    Route::get("/{lead}/change-status", "Leads@changeStatus")->where('lead', '[0-9]+');
    Route::post("/{lead}/change-status", "Leads@changeStatusUpdate")->where('lead', '[0-9]+');
    Route::post("/{lead}/update-description", "Leads@updateDescription")->where('lead', '[0-9]+');
    Route::post("/{lead}/attach-files", "Leads@attachFiles")->where('lead', '[0-9]+');
    Route::delete("/delete-attachment/{uniqueid}", "Leads@deleteAttachment");
    Route::get("/download-attachment/{uniqueid}", "Leads@downloadAttachment");
    Route::post("/{lead}/update-title", "Leads@updateTitle")->where('lead', '[0-9]+');
    Route::post("/{lead}/post-comment", "Leads@storeComment")->where('lead', '[0-9]+');
    Route::delete("/delete-comment/{commentid}", "Leads@deleteComment")->where('commentid', '[0-9]+');
    Route::post("/{lead}/add-checklist", "Leads@storeChecklist")->where('lead', '[0-9]+');
    Route::post("/update-checklist/{checklistid}", "Leads@updateChecklist")->where('checklistid', '[0-9]+');
    Route::delete("/delete-checklist/{checklistid}", "Leads@deleteChecklist")->where('checklistid', '[0-9]+');
    Route::post("/toggle-checklist-status/{checklistid}", "Leads@toggleChecklistStatus")->where('checklistid', '[0-9]+');
    Route::post("/{lead}/update-date-added", "Leads@updateDateAdded")->where('lead', '[0-9]+');
    Route::post("/{lead}/update-name", "Leads@updateName")->where('lead', '[0-9]+');
    Route::post("/{lead}/update-value", "Leads@updateValue")->where('lead', '[0-9]+');
    Route::post("/{lead}/update-status", "Leads@updateStatus")->where('lead', '[0-9]+');
    Route::post("/{lead}/update-category", "Leads@updateCategory")->where('lead', '[0-9]+');
    Route::post("/{lead}/update-contacted", "Leads@updateContacted")->where('lead', '[0-9]+');
    Route::post("/{lead}/update-phone", "Leads@updatePhone")->where('lead', '[0-9]+');
    Route::post("/{lead}/update-email", "Leads@updateEmail")->where('lead', '[0-9]+');
    Route::post("/{lead}/update-source", "Leads@updateSource")->where('lead', '[0-9]+');
    Route::post("/{lead}/update-organisation", "Leads@updateOrganisation")->where('lead', '[0-9]+');
    Route::post("/{lead}/update-assigned", "Leads@updateAssigned")->where('lead', '[0-9]+');
    Route::post("/{lead}/update-tags", "Leads@updateTags")->where('lead', '[0-9]+');
    Route::post("/update-position", "Leads@updatePosition");
    Route::post("/{lead}/convert-lead", "Leads@convertLead")->where('lead', '[0-9]+');
    Route::get("/{lead}/convert-details", "Leads@convertDetails")->where('lead', '[0-9]+');
    Route::any("/v/{lead}/{slug}", "Leads@index")->where('lead', '[0-9]+');
    Route::post("/{lead}/update-custom", "Leads@updateCustomFields")->where('lead', '[0-9]+');
    Route::put("/{lead}/archive", "Leads@archive")->where('lead', '[0-9]+');
    Route::put("/{lead}/activate", "Leads@activate")->where('lead', '[0-9]+');
    Route::get("/{lead}/clone", "Leads@cloneLead")->where('lead', '[0-9]+');
    Route::post("/{lead}/clone", "Leads@cloneStore")->where('lead', '[0-9]+');
    Route::get("/{lead}/assigned", "Leads@assignedUsers")->where('lead', '[0-9]+');
    Route::put("/{lead}/assigned", "Leads@assignedUsersUpdate")->where('lead', '[0-9]+');
    Route::get("/change-assigned", "Leads@BulkchangeAssigned");
    Route::post("/change-assigned", "Leads@BulkchangeAssignedUpdate");
    Route::get("/bulk-change-status", "Leads@BulkChangeStatus");
    Route::post("/bulk-change-status", "Leads@BulkChangeStatusUpdate");
    Route::get("/{lead}/add-cover-image", "Leads@addCoverImage")->where('lead', '[0-9]+');
    Route::get("/{lead}/remove-cover-image", "Leads@removeCoverImage")->where('lead', '[0-9]+');
    Route::get("/{lead}/pinning", "Leads@togglePinning")->where('lead', '[0-9]+');

    //card tabs
    Route::get("/content/{lead}/show-main", "Leads@show")->where('lead', '[0-9]+');
    Route::get("/content/{lead}/show-organisation", "Leads@showOrganisation")->where('lead', '[0-9]+');
    Route::get("/content/{lead}/edit-organisation", "Leads@editOrganisation")->where('lead', '[0-9]+');
    Route::post("/content/{lead}/edit-organisation", "Leads@updateOrganisation")->where('lead', '[0-9]+');
    Route::get("/content/{lead}/show-customfields", "Leads@showCustomFields")->where('lead', '[0-9]+');
    Route::get("/content/{lead}/edit-customfields", "Leads@editCustomFields")->where('lead', '[0-9]+');
    Route::post("/content/{lead}/edit-customfields", "Leads@updateCustomFields")->where('lead', '[0-9]+');
    Route::get("/content/{lead}/show-mynotes", "Leads@showMyNotes")->where('lead', '[0-9]+');
    Route::get("/content/{lead}/create-mynotes", "Leads@createMyNotes")->where('lead', '[0-9]+');
    Route::get("/content/{lead}/edit-mynotes", "Leads@editMyNotes")->where('lead', '[0-9]+');
    Route::delete("/content/{lead}/delete-mynotes", "Leads@deleteMyNotes")->where('lead', '[0-9]+');
    Route::post("/content/{lead}/edit-mynotes", "Leads@updateMyNotes")->where('lead', '[0-9]+');
    Route::get("/content/{lead}/show-logs", "Leads@showLogs")->where('lead', '[0-9]+');
    Route::get("/content/{lead}/edit-logs", "Leads@editLogs")->where('lead', '[0-9]+');
    Route::post("/content/{lead}/edit-logs", "Leads@updateLogs")->where('lead', '[0-9]+');

    Route::post('/update-checklist-positions', 'Leads@updateChecklistPositions');
    Route::post('/{lead}/import-checklists', 'Leads@importChecklists');
    Route::post('/{lead}/post-checklist-comment', 'Leads@storeChecklistComment')->where('lead', '[0-9]+');
    Route::delete('/{comment}/delete-checklist-comment', 'Leads@destroyChecklistComment')->where('comment', '[0-9]+');
    
    //bulk archive and restore
    Route::post("/bulk/archive", "Leads@bulkArchive");
    Route::post("/bulk/restore", "Leads@bulkRestore");

    //lead logs
    Route::get("/{lead}/show-logs", "Leads@showLogs")->where('lead', '[0-9]+');
    Route::post("/{lead}/store-log", "Leads@storeLog")->where('lead', '[0-9]+');
    Route::get("/{lead}/edit-log/{uniqueid}", "Leads@editLog")->where('lead', '[0-9]+');
    Route::put("/{lead}/update-log/{uniqueid}", "Leads@updateLog")->where('lead', '[0-9]+');
    Route::delete("/{lead}/delete-log/{uniqueid}", "Leads@deleteLog")->where('lead', '[0-9]+');    
});
Route::resource('leads', 'Leads');

//TICKETS
Route::group(['prefix' => 'tickets'], function () {
    Route::any("/search", "Tickets@index");
    Route::get("/{x}/editdetails", "Tickets@editDetails")->where('x', '[0-9]+');
    Route::get("/{ticket}/reply", "Tickets@reply")->where('x', '[0-9]+');
    Route::post("/{ticket}/postreply", "Tickets@storeReply")->where('x', '[0-9]+');
    Route::post("/delete", "Tickets@destroy")->middleware(['demoModeCheck']);
    Route::get("/change-category", "Tickets@changeCategory");
    Route::post("/change-category", "Tickets@changeCategoryUpdate");
    Route::get("/attachments/download/{uniqueid}", "Tickets@downloadAttachment");
    Route::get("/{ticket}/edit-reply", "Tickets@editReply")->where('ticket', '[0-9]+');
    Route::post("/{ticket}/edit-reply", "Tickets@updateReply")->where('ticket', '[0-9]+');
    Route::delete("/{ticket}/delete-reply", "Tickets@deleteReply")->where('ticket', '[0-9]+');
    Route::any("/archive", "Tickets@archive");
    Route::any("/restore", "Tickets@restore");
    Route::get("/change-status", "Tickets@changeStatus");
    Route::post("/change-status", "Tickets@changeStatusUpdate");
    Route::get("/{ticket}/pinning", "Tickets@togglePinning")->where('ticket', '[0-9]+');
    Route::get("/{ticket}/edit-tags", "Tickets@editTags");
    Route::post("/{ticket}/edit-tags", "Tickets@updateTags");

});
Route::resource('tickets', 'Tickets');

//TICKETS CANNED RESPONSES
Route::group(['prefix' => 'canned'], function () {
    Route::post("/search", "Canned@search");
    Route::get("/update-recently-used/{id}", "Canned@updateRecentlyUsed");
});
Route::resource('canned', 'Canned');

//TIMELINE
Route::group(['prefix' => 'timeline'], function () {
    Route::any("/client", "Timeline@clientTimeline");
    Route::any("/project", "Timeline@projectTimeline");
    Route::any("/user/{id}", "Timeline@user");
});

//TIMESHEETS
Route::group(['prefix' => 'timesheets'], function () {
    Route::any("/my", "Timesheets@index");
    Route::any("/", "Timesheets@index");
    Route::any("/search", "Timesheets@index");
    Route::post("/delete", "Timesheets@destroy")->middleware(['demoModeCheck']);
    Route::get("/{timesheet}/pinning", "Timesheets@togglePinning")->where('timesheet', '[0-9]+');

});
Route::resource('timesheets', 'Timesheets');

//FILES
Route::group(['prefix' => 'files'], function () {
    Route::any("/search", "Files@index");
    Route::get("/getimage", "Files@showImage");
    Route::get("/download", "Files@download");
    Route::get("/download-attachment", "Files@downloadAttachment");
    Route::post("/delete", "Files@destroy")->middleware(['demoModeCheck']);
    Route::post("/{file}/rename", "Files@renameFile")->middleware(['demoModeCheck']);
    Route::get("/folders/show", "Files@showFolders");
    Route::get("/folders/create", "Files@createFolder");
    Route::post("/folders/create", "Files@storeFolder");
    Route::get("/folders/edit", "Files@editFolders");
    Route::post("/folders/update", "Files@updateFolders");
    Route::delete("/folders/{folder}/delete", "Files@deleteFolder")->where('folder', '[0-9]+')->middleware(['demoModeCheck']);
    Route::post("/move", "Files@ShowMoveFiles");
    Route::put("/move", "Files@moveFiles")->middleware(['demoModeCheck']);
    Route::post("/bulkdownload", "Files@bulkDownload");
    Route::get("/copy", "Files@copy");
    Route::post("/copy", "Files@copyAction");
    Route::get("/{file}/edit-tags", "Files@editTags");
    Route::post("/{file}/edit-tags", "Files@updateTags");
});
Route::resource('files', 'Files');

//NOTES
Route::group(['prefix' => 'notes'], function () {
    Route::any("/search", "Notes@index");
    Route::post("/delete", "Notes@destroy")->middleware(['demoModeCheck']);
    Route::delete("/attachments/{uniqueid}", "Notes@deleteAttachment")->middleware(['demoModeCheck']);
    Route::get("/attachments/download/{uniqueid}", "Notes@downloadAttachment");
});
Route::resource('notes', 'Notes');

//COMMENTS
Route::group(['prefix' => 'comments'], function () {
    Route::any("/search", "Comments@index");
    Route::post("/delete", "Comments@destroy")->middleware(['demoModeCheck']);
});
Route::resource('comments', 'Comments');

//DOCUMENTS (proposals & contracts)
Route::group(['prefix' => 'documents'], function () {
    Route::post("/{document}/update/hero", "Documents@updateHero")->where('document', '[0-9]+');
    Route::post("/{document}/update/details", "Documents@updateDetails")->where('document', '[0-9]+');
    Route::post("/{document}/update/body", "Documents@updateBody")->where('document', '[0-9]+');
});

//PROPOSALS
Route::resource('proposals', 'Proposals');
Route::group(['prefix' => 'proposals'], function () {
    Route::any("/search", "Proposals@index");
    Route::post("/delete", "Proposals@destroy")->middleware(['demoModeCheck']);
    Route::get("/change-category", "Proposals@changeCategory");
    Route::post("/change-category", "Proposals@changeCategoryUpdate");
    Route::get("/{proposal}", "Proposals@show")->where('proposal', '[0-9]+');
    Route::get("/{proposal}/edit", "Proposals@editingProposal")->where('proposal', '[0-9]+');
    Route::get("/{proposal}/publish", "Proposals@publish")->where('proposal', '[0-9]+');
    Route::post("/{proposal}/publish/scheduled", "Proposals@publishScheduled")->where('proposal', '[0-9]+')->middleware(['proposalsMiddlewareEdit', 'proposalsMiddlewareShow']);
    Route::get("/{proposal}/resend", "Proposals@resendEmail")->where('proposal', '[0-9]+');
    Route::get("/view/{proposal}", "Proposals@showPublic");
    Route::get("/{proposal}/change-status", "Proposals@changeStatus")->where('proposal', '[0-9]+');
    Route::get("/{proposal}/sign", "Proposals@sign");
    Route::post("/{proposal}/accept", "Proposals@accepted");
    Route::get("/{proposal}/decline", "Proposals@declined");
    Route::get("/{proposal}/clone", "Proposals@createClone")->where('project', '[0-9]+');
    Route::post("/{proposal}/clone", "Proposals@storeClone")->where('project', '[0-9]+');
    Route::get("/{proposal}/edit-automation", "Proposals@editAutomation")->where('estimate', '[0-9]+');
    Route::post("/{proposal}/edit-automation", "Proposals@updateAutomation")->where('estimate', '[0-9]+');
    Route::get("/{proposal}/pinning", "Proposals@togglePinning")->where('proposal', '[0-9]+');
});

//CONTRACTS
Route::resource('contracts', 'Contracts');
Route::group(['prefix' => 'contracts'], function () {
    Route::any("/search", "Contracts@index");
    Route::post("/delete", "Contracts@destroy")->middleware(['demoModeCheck']);
    Route::get("/change-category", "Contracts@changeCategory");
    Route::post("/change-category", "Contracts@changeCategoryUpdate");
    Route::get("/{contract}", "Contracts@show")->where('contract', '[0-9]+');
    Route::get("/{contract}/edit", "Contracts@editingContract")->where('contract', '[0-9]+');
    Route::get("/{contract}/publish", "Contracts@publish")->where('contract', '[0-9]+');
    Route::post("/{contract}/publish/scheduled", "Contracts@publishScheduled")->where('contract', '[0-9]+')->middleware(['contractsMiddlewareEdit', 'contractsMiddlewareShow']);
    Route::get("/{contract}/resend", "Contracts@resendEmail")->where('contract', '[0-9]+');
    Route::get("/view/{contract}", "Contracts@showPublic");
    Route::get("/{contract}/change-status", "Contracts@changeStatus")->where('contract', '[0-9]+');
    Route::get("/{contract}/sign/team", "Contracts@signTeam");
    Route::post("/{contract}/sign/team", "Contracts@signTeamAction");
    Route::get("/{contract}/sign/client", "Contracts@signClient");
    Route::post("/{contract}/sign/client", "Contracts@signClientAction");
    Route::delete("/{contract}/sign/delete-signature", "Contracts@signDeleteSignature");
    Route::get("/{contract}/attach-project", "Contracts@attachProject")->where('invoice', '[0-9]+');
    Route::post("/{contract}/attach-project", "Contracts@attachProjectUpdate")->where('invoice', '[0-9]+');
    Route::get("/{contract}/detach-project", "Contracts@dettachProject")->where('invoice', '[0-9]+');
    Route::get("/{contract}/clone", "Contracts@createClone")->where('project', '[0-9]+');
    Route::post("/{contract}/clone", "Contracts@storeClone")->where('project', '[0-9]+');
    Route::get("/{contract}/pinning", "Contracts@togglePinning")->where('contract', '[0-9]+');
});

//CONTRACT TEMPLATES
Route::resource('templates/contracts', 'Templates\Contracts');

//PROPOSAL TEMPLATES
Route::resource('templates/proposals', 'Templates\Proposals');

//AUTOCOMPLETE AJAX FEED
Route::group(['prefix' => 'feed'], function () {
    Route::get("/", "Feed@index");
    Route::get("/company_names", "Feed@companyNames");
    Route::get("/contacts", "Feed@contactNames");
    Route::get("/email", "Feed@emailAddress");
    Route::get("/tags", "Feed@tags");
    Route::get("/leads", "Feed@leads");
    Route::get("/leadnames", "Feed@leadNames");
    Route::get("/projects", "Feed@projects");
    Route::get("/projectassigned", "Feed@projectAssignedUsers");
    Route::get("/projects-my-assigned-task", "Feed@projectAssignedTasks");
    Route::get("/clone-task-projects", "Feed@cloneTaskProjects");
    Route::get("/project-milestones", "Feed@projectsMilestones");
    Route::get("/project-client-users", "Feed@projectClientUsers");
    Route::get("/users-projects", "Feed@usersProjects");

});

//PROJECTS & PROJECT
Route::group(['prefix' => 'feed'], function () {
    Route::any("/team", "Team@index"); //[TODO]  auth middleware
});

//MILESTONES
Route::group(['prefix' => 'milestones'], function () {
    Route::any("/search", "Milestones@index");
    Route::post("/update-positions", "Milestones@updatePositions");
});
Route::resource('milestones', 'Milestones');

//CATEGORIES
Route::group(['prefix' => 'categories'], function () {
    Route::any("/", "Categories@index");
    Route::get("/{category}/team", "Categories@showTeam")->where('category', '[0-9]+');
    Route::put("/{category}/team", "Categories@updateTeam")->where('category', '[0-9]+');
});
Route::resource('categories', 'Categories');

//FILEUPLOAD
Route::post("/fileupload", "Fileupload@save");
Route::post("/webform/fileupload", "Fileupload@saveWebForm");

//AVATAR FILEUPLOAD
Route::post("/avatarupload", "Fileupload@saveAvatar");

//CLIENT LOGO FILEUPLOAD
Route::post("/uploadlogo", "Fileupload@saveLogo");

//APP LOGO FILEUPLOAD
Route::post("/upload-app-logo", "Fileupload@saveAppLogo");

//TINYMCE IMAGE FILEUPLOAD
Route::post("/upload-tinymce-image", "Fileupload@saveTinyMCEImage");

//COVER IMAGE UPLAOD
Route::post("/upload-cover-image", "Fileupload@uploadCoverImage");

//GENERAL IMAGE UPLAOD
Route::post("/upload-general-image", "Fileupload@saveGeneralImage");

//TAGS - GENERAL
Route::group(['prefix' => 'tags'], function () {
    Route::any("/search", "Tags@index");
});
Route::resource('tags', 'Tags');

//KNOWLEDGEBASE - CATEGORIES
Route::group(['prefix' => 'knowledgebase'], function () {
    //category
    Route::get("/", "KBCategories@index");
});
Route::resource('knowledgebase', 'KBCategories');

//KNOWLEDGEBASE - ARTICLES
Route::group(['prefix' => 'kb'], function () {
    //category
    Route::any("/search", "Knowledgebase@index");
    //pretty url domain.com/kb/12/some-category-title
    Route::any("/articles/{slug}", "Knowledgebase@index");
    Route::any("/article/{slug}", "Knowledgebase@show");
    Route::any("/search/{slug}", "Knowledgebase@index");

});
Route::resource('kb', 'Knowledgebase');

//CALENDAR
Route::group(['prefix' => 'calendar'], function () {
    Route::post("/", "Calendar@index");
});
Route::resource('calendar', 'Calendar');
Route::delete("/calendar/files/{id}", "Calendar@deleteFiles");

//SETTINGS - HOME
Route::group(['prefix' => 'settings'], function () {
    Route::get("/", "Settings\Home@index");
});

//SETTINGS - SYSTEM
Route::group(['prefix' => 'settings/system'], function () {
    Route::get("/clearcache", "Settings\System@clearLaravelCache");
    Route::get("/info", "Settings\System@systemInfo");
    Route::get("/disc-usage", "Settings\System@discUsage");
    Route::post("/cleanup", "Settings\System@cleanUpSpace");
});

//SETTINGS - GENERAL
Route::group(['prefix' => 'settings/general'], function () {
    Route::get("/", "Settings\General@index");
    Route::put("/", "Settings\General@update")->middleware(['demoModeCheck']);
});

//SETTINGS - MODULES
Route::group(['prefix' => 'settings/modules'], function () {
    Route::get("/", "Settings\Modules@index");
    Route::put("/", "Settings\Modules@update")->middleware(['demoModeCheck']);
});

//SETTINGS - COMPANY
Route::group(['prefix' => 'settings/company'], function () {
    Route::get("/", "Settings\Company@index");
    Route::put("/", "Settings\Company@update")->middleware(['demoModeCheck']);
});

//SETTINGS - CURRENCY
Route::group(['prefix' => 'settings/currency'], function () {
    Route::get("/", "Settings\Currency@index");
    Route::put("/", "Settings\Currency@update")->middleware(['demoModeCheck']);
});

//SETTINGS - THEME
Route::group(['prefix' => 'settings/theme'], function () {
    Route::get("/", "Settings\Theme@index");
    Route::put("/", "Settings\Theme@update")->middleware(['demoModeCheck']);
});

//SETTINGS - CLIENT
Route::group(['prefix' => 'settings/clients'], function () {
    Route::get("/", "Settings\Clients@index");
    Route::put("/", "Settings\Clients@update")->middleware(['demoModeCheck']);
});

//SETTINGS - TAGS
Route::group(['prefix' => 'settings/tags'], function () {
    Route::get("/", "Settings\Tags@index");
    Route::put("/", "Settings\Tags@update")->middleware(['demoModeCheck']);
});

//SETTINGS - PROJECT
Route::group(['prefix' => 'settings/projects'], function () {
    Route::get("/general", "Settings\Projects@general");
    Route::put("/general", "Settings\Projects@updateGeneral")->middleware(['demoModeCheck']);
    Route::get("/client", "Settings\Projects@clientPermissions");
    Route::put("/client", "Settings\Projects@updateClientPermissions")->middleware(['demoModeCheck']);
    Route::get("/staff", "Settings\Projects@staffPermissions");
    Route::put("/staff", "Settings\Projects@updateStaffPermissions")->middleware(['demoModeCheck']);
    Route::get("/automation", "Settings\Projects@automation");
    Route::put("/automation", "Settings\Projects@automationUpdate");
});

//SETTINGS - INVOICES
Route::group(['prefix' => 'settings/invoices'], function () {
    Route::get("/", "Settings\Invoices@index");
    Route::put("/", "Settings\Invoices@update")->middleware(['demoModeCheck']);
});

//SETTINGS - TIMESHEETS
Route::group(['prefix' => 'settings/timesheets'], function () {
    Route::get("/", "Settings\Timesheets@index");
    Route::put("/", "Settings\Timesheets@update")->middleware(['demoModeCheck']);
});

//SETTINGS - SUBSCRIPTIONS
Route::group(['prefix' => 'settings/subscriptions'], function () {
    Route::get("/", "Settings\Subscriptions@index");
    Route::put("/", "Settings\Subscriptions@update")->middleware(['demoModeCheck']);
});

//SETTINGS - UNITS
Route::group(['prefix' => 'settings/units'], function () {
    Route::get("/", "Settings\Units@index");
    Route::put("/", "Settings\Units@update")->middleware(['demoModeCheck']);
});
Route::resource('settings/units', 'Settings\Units');

//SETTINGS - TAX RATES
Route::group(['prefix' => 'settings/taxrates'], function () {
    Route::get("/", "Settings\Taxrates@index");
    Route::put("/", "Settings\Taxrates@update")->middleware(['demoModeCheck']);
});
Route::resource('settings/taxrates', 'Settings\Taxrates');

//SETTINGS - ESTIMATES
Route::group(['prefix' => 'settings/estimates'], function () {
    Route::get("/", "Settings\Estimates@index");
    Route::put("/", "Settings\Estimates@update")->middleware(['demoModeCheck']);
    Route::get("/automation", "Settings\Estimates@automation");
    Route::put("/automation", "Settings\Estimates@automationUpdate");
});

//SETTINGS - CONTRACTS
Route::group(['prefix' => 'settings/contracts'], function () {
    Route::get("/", "Settings\Contracts@index");
    Route::put("/", "Settings\Contracts@update")->middleware(['demoModeCheck']);
});

//SETTINGS - PROPOSALS
Route::group(['prefix' => 'settings/proposals'], function () {
    Route::get("/", "Settings\Proposals@index");
    Route::put("/", "Settings\Proposals@update")->middleware(['demoModeCheck']);
    Route::get("/automation", "Settings\Proposals@automation");
    Route::put("/automation", "Settings\Proposals@automationUpdate");
});

//SETTINGS - EXPENSES
Route::group(['prefix' => 'settings/expenses'], function () {
    Route::get("/", "Settings\Expenses@index");
    Route::put("/", "Settings\Expenses@update")->middleware(['demoModeCheck']);
});

//SETTINGS - STRIPE
Route::group(['prefix' => 'settings/stripe'], function () {
    Route::get("/", "Settings\Stripe@index")->middleware(['demoModeCheck']);
    Route::put("/", "Settings\Stripe@update")->middleware(['demoModeCheck']);
});

//SETTINGS - RAZORPAY
Route::group(['prefix' => 'settings/razorpay'], function () {
    Route::get("/", "Settings\Razorpay@index")->middleware(['demoModeCheck']);
    Route::put("/", "Settings\Razorpay@update")->middleware(['demoModeCheck']);
});

//SETTINGS - MOLLIE
Route::group(['prefix' => 'settings/mollie'], function () {
    Route::get("/", "Settings\Mollie@index")->middleware(['demoModeCheck']);
    Route::put("/", "Settings\Mollie@update")->middleware(['demoModeCheck']);
});

//SETTINGS - PAYPAL
Route::group(['prefix' => 'settings/paypal'], function () {
    Route::get("/", "Settings\Paypal@index")->middleware(['demoModeCheck']);
    Route::put("/", "Settings\Paypal@update")->middleware(['demoModeCheck']);
});

//SETTINGS - TAP
Route::group(['prefix' => 'settings/tap'], function () {
    Route::get("/", "Settings\Tap@index")->middleware(['demoModeCheck']);
    Route::put("/", "Settings\Tap@update")->middleware(['demoModeCheck']);
});

//SETTINGS - PAYSTACK
Route::group(['prefix' => 'settings/paystack'], function () {
    Route::get("/", "Settings\Paystack@index")->middleware(['demoModeCheck']);
    Route::put("/", "Settings\Paystack@update")->middleware(['demoModeCheck']);
});

//SETTINGS - BANK
Route::group(['prefix' => 'settings/bank'], function () {
    Route::get("/", "Settings\Bank@index");
    Route::put("/", "Settings\Bank@update")->middleware(['demoModeCheck']);
});

//SETTINGS - LEADS
Route::group(['prefix' => 'settings/leads'], function () {
    Route::get("/general", "Settings\Leads@general");
    Route::put("/general", "Settings\Leads@updateGeneral");
    Route::get("/statuses", "Settings\Leads@statuses");
    Route::put("/statuses", "Settings\Leads@updateStatuses")->middleware(['demoModeCheck']);
    Route::get("/statuses/{id}/edit", "Settings\Leads@editStatus")->where('lead', '[0-9]+');
    Route::put("/statuses/{id}", "Settings\Leads@updateStatus")->where('lead', '[0-9]+')->middleware(['demoModeCheck']);
    Route::get("/statuses/create", "Settings\Leads@createStatus");
    Route::post("/statuses/create", "Settings\Leads@storeStatus");
    Route::get("/move/{id}", "Settings\Leads@move")->where('id', '[0-9]+');
    Route::put("/move/{id}", "Settings\Leads@updateMove")->where('id', '[0-9]+');
    Route::delete("/statuses/{id}", "Settings\Leads@destroyStatus")->where('id', '[0-9]+')->middleware(['demoModeCheck']);
    Route::post("/update-stage-positions", "Settings\Leads@updateStagePositions");
});

//SETTINGS - MILESTONES
Route::group(['prefix' => 'settings/milestones'], function () {
    Route::get("/settings", "Settings\Milestones@index");
    Route::put("/settings", "Settings\Milestones@update")->middleware(['demoModeCheck']);
    Route::get("/default", "Settings\Milestones@categories");
    Route::get("/create", "Settings\Milestones@create");
    Route::post("/create", "Settings\Milestones@storeCategory")->middleware(['demoModeCheck']);
    Route::get("/{id}/edit", "Settings\Milestones@editCategory")->where('id', '[0-9]+');
    Route::put("/{id}", "Settings\Milestones@updateCategory")->where('id', '[0-9]+')->middleware(['demoModeCheck']);
    Route::post("/update-positions", "Settings\Milestones@updateCategoryPositions");
    Route::delete("/{id}", "Settings\Milestones@destroy")->where('id', '[0-9]+')->middleware(['demoModeCheck']);
});

//SETTINGS - knowledgebase
Route::group(['prefix' => 'settings/knowledgebase'], function () {
    Route::get("/settings", "Settings\Knowledgebase@index");
    Route::put("/settings", "Settings\Knowledgebase@update")->middleware(['demoModeCheck']);
    Route::get("/default", "Settings\Knowledgebase@categories");
    Route::get("/create", "Settings\Knowledgebase@create");
    Route::post("/create", "Settings\Knowledgebase@storeCategory")->middleware(['demoModeCheck']);
    Route::get("/{id}/edit", "Settings\Knowledgebase@editCategory")->where('id', '[0-9]+');
    Route::put("/{id}", "Settings\Knowledgebase@updateCategory")->where('id', '[0-9]+')->middleware(['demoModeCheck']);
    Route::post("/update-positions", "Settings\Knowledgebase@updateCategoryPositions");
    Route::delete("/{id}", "Settings\Knowledgebase@destroy")->where('id', '[0-9]+')->middleware(['demoModeCheck']);
});

//SETTINGS - LEAD SOURCES
Route::group(['prefix' => 'settings/sources'], function () {
    Route::get("/", "Settings\Sources@index");
    Route::put("/", "Settings\Sources@update")->middleware(['demoModeCheck']);
});
Route::resource('settings/sources', 'Settings\Sources');

//SETTINGS - LEAD WEBFORMS
Route::group(['prefix' => 'settings/webforms'], function () {
    Route::get("/", "Settings\Webforms@index");
    Route::put("/", "Settings\Webforms@update")->middleware(['demoModeCheck'])->name('webform.save');
    Route::get("/{id}/embedcode", "Settings\Webforms@embedCode");
    Route::get("/{id}/assigned", "Settings\Webforms@assignedUsers");
    Route::post("/{id}/assigned", "Settings\Webforms@updateAssignedUsers");
});
Route::resource('settings/webforms', 'Settings\Webforms');

//WEBFORM - VIEW
Route::get("/webform/view/{id}", "Webform@showWeb");
Route::get("/webform/embed/{id}", "Webform@showWeb");
Route::post("/webform/submit/{id}", "Webform@saveForm")->name('webform.submit');

//SETTINGS - LEAD FORM BUILDER
Route::group(['prefix' => 'settings/formbuilder'], function () {
    Route::get("/{id}/build", "Settings\Formbuilder@buildForm");
    Route::post("/{id}/build", "Settings\Formbuilder@saveForm");
    Route::get("/{id}/settings", "Settings\Formbuilder@formSettings");
    Route::post("/{id}/settings", "Settings\Formbuilder@saveSettings");
    Route::get("/{id}/integrate", "Settings\Formbuilder@embedCode");
    Route::get("/{id}/style", "Settings\Formbuilder@formStyle");
    Route::post("/{id}/style", "Settings\Formbuilder@saveStyle");
});

//SETTINGS - TASKS
Route::group(['prefix' => 'settings/tasks'], function () {
    Route::get("/", "Settings\Tasks@index");
    Route::put("/", "Settings\Tasks@update")->middleware(['demoModeCheck']);

    Route::get("/statuses", "Settings\Tasks@statuses");
    Route::put("/statuses", "Settings\Tasks@updateStatuses")->middleware(['demoModeCheck']);
    Route::get("/statuses/{id}/edit", "Settings\Tasks@editStatus")->where('task', '[0-9]+');
    Route::put("/statuses/{id}", "Settings\Tasks@updateStatus")->where('task', '[0-9]+')->middleware(['demoModeCheck']);
    Route::get("/statuses/create", "Settings\Tasks@createStatus");
    Route::post("/statuses/create", "Settings\Tasks@storeStatus");
    Route::get("/move/{id}", "Settings\Tasks@move")->where('id', '[0-9]+');
    Route::put("/move/{id}", "Settings\Tasks@updateMove")->where('id', '[0-9]+');
    Route::delete("/statuses/{id}", "Settings\Tasks@destroyStatus")->where('id', '[0-9]+')->middleware(['demoModeCheck']);
    Route::post("/update-stage-positions", "Settings\Tasks@updateStagePositions");

    Route::get("/priorities", "Settings\Tasks@priorities");
    Route::put("/priorities", "Settings\Tasks@updatePriorities")->middleware(['demoModeCheck']);
    Route::get("/priorities/{id}/edit", "Settings\Tasks@editPriority")->where('task', '[0-9]+');
    Route::put("/priorities/{id}", "Settings\Tasks@updatePriority")->where('task', '[0-9]+')->middleware(['demoModeCheck']);
    Route::get("/priorities/create", "Settings\Tasks@createPriority");
    Route::post("/priorities/create", "Settings\Tasks@storePriority");
    Route::delete("/priorities/{id}", "Settings\Tasks@destroyPriority")->where('id', '[0-9]+')->middleware(['demoModeCheck']);
    Route::get("/move/priority/{id}", "Settings\Tasks@movePriority")->where('id', '[0-9]+');
    Route::put("/move/priority/{id}", "Settings\Tasks@updatePriorityMove")->where('id', '[0-9]+');
    Route::delete("/priorities/{id}", "Settings\Tasks@destroyPriority")->where('id', '[0-9]+')->middleware(['demoModeCheck']);
    Route::post("/update-priority-positions", "Settings\Tasks@updatePriorityPositions");

});

//SETTINGS - EMAIL
Route::group(['prefix' => 'settings/email'], function () {
    Route::get("/general", "Settings\Email@general");
    Route::put("/general", "Settings\Email@updateGeneral")->middleware(['demoModeCheck']);
    Route::get("/smtp", "Settings\Email@smtp")->middleware(['demoModeCheck']);
    Route::put("/smtp", "Settings\Email@updateSMTP")->middleware(['demoModeCheck']);
    Route::get("/templates", "Settings\Emailtemplates@index");
    Route::get("/templates/{id}", "Settings\Emailtemplates@show")->where('id', '[0-9]+');
    Route::post("/templates/{id}", "Settings\Emailtemplates@update")->where('id', '[0-9]+')->middleware(['demoModeCheck']);
    Route::get("/testemail", "Settings\Email@testEmail")->middleware(['demoModeCheck']);
    Route::post("/testemail", "Settings\Email@testEmailAction")->middleware(['demoModeCheck']);
    Route::get("/testsmtp", "Settings\Email@testSMTP")->middleware(['demoModeCheck']);
    Route::get("/queue", "Settings\Email@queueShow")->where('id', '[0-9]+');
    Route::get("/queue/{id}", "Settings\Email@queueRead")->where('id', '[0-9]+');
    Route::delete("/queue/{id}", "Settings\Email@queueDelete")->where('id', '[0-9]+');
    Route::delete("/queue/purge", "Settings\Email@queuePurge");
    Route::delete("/queue/requeue", "Settings\Email@queueReschedule");
    Route::get("/log", "Settings\Email@logShow")->where('id', '[0-9]+');
    Route::get("/log/{id}", "Settings\Email@logRead")->where('id', '[0-9]+');
    Route::delete("/log/{id}", "Settings\Email@logDelete")->where('id', '[0-9]+');
    Route::delete("/log/purge", "Settings\Email@logPurge");
});

//SETTINGS - UPDATES
Route::group(['prefix' => 'settings/updates'], function () {
    Route::get("/", "Settings\Updates@index");
    Route::post("/check", "Settings\Updates@checkUpdates");
});

//SETTINGS - RECAPCHA
Route::group(['prefix' => 'settings/recaptcha'], function () {
    Route::get("/", "Settings\Captcha@index");
    Route::put("/", "Settings\Captcha@update");
});

//SETTINGS - TWEAK
Route::group(['prefix' => 'settings/tweak'], function () {
    Route::get("/", "Settings\Tweak@index");
    Route::put("/", "Settings\Tweak@update")->middleware(['demoModeCheck']);
});

//SETTINGS - ROLES
Route::group(['prefix' => 'settings/roles'], function () {
    Route::get("/", "Settings\Roles@index");
    Route::put("/", "Settings\Roles@update")->middleware(['demoModeCheck']);
    Route::get("/{id}/homepage", "Settings\Roles@editHomePage")->where('id', '[0-9]+');
    Route::put("/{id}/homepage", "Settings\Roles@updateHomePage")->middleware(['demoModeCheck']);
});
Route::resource('settings/roles', 'Settings\Roles');
Route::post("/settings/roles", "Settings\Roles@Store")->middleware(['demoModeCheck']);

//SETTINGS - CLIENTS
Route::group(['prefix' => 'settings/clients'], function () {
    Route::get("/", "Settings\Clients@index");
    Route::put("/", "Settings\Clients@update")->middleware(['demoModeCheck']);
});

//SETTINGS - TICKETS
Route::group(['prefix' => 'settings/tickets'], function () {
    Route::get("/", "Settings\Tickets@index");
    Route::put("/", "Settings\Tickets@update")->middleware(['demoModeCheck']);
    Route::get("/statuses", "Settings\Tickets@statuses");
    Route::put("/statuses", "Settings\Tickets@updateStatuses")->middleware(['demoModeCheck']);
    Route::get("/statuses/{id}/edit", "Settings\Tickets@editStatus")->where('task', '[0-9]+');
    Route::put("/statuses/{id}", "Settings\Tickets@updateStatus")->where('task', '[0-9]+')->middleware(['demoModeCheck']);
    Route::get("/statuses/create", "Settings\Tickets@createStatus");
    Route::post("/statuses/create", "Settings\Tickets@storeStatus");
    Route::get("/move/{id}", "Settings\Tickets@move")->where('id', '[0-9]+');
    Route::put("/move/{id}", "Settings\Tickets@updateMove")->where('id', '[0-9]+');
    Route::delete("/statuses/{id}", "Settings\Tickets@destroyStatus")->where('id', '[0-9]+')->middleware(['demoModeCheck']);
    Route::post("/update-stage-positions", "Settings\Tickets@updateStagePositions");
    Route::get("/statuses/{id}/settings", "Settings\Tickets@statusSettings");
    Route::put("/statuses/{id}/settings", "Settings\Tickets@statusSettingsUpdate");
    Route::post("/emailintegration/test", "Settings\Tickets@testImapConnection");
    Route::get("/emailintegration/category/{id}", "Settings\Tickets@categoryIMAPSettings");
    Route::put("/emailintegration/category/{id}", "Settings\Tickets@updateCategoryIMAPSettings");
});

//SETTINGS - LOGO
Route::group(['prefix' => 'settings/logos'], function () {
    Route::get("/", "Settings\Logos@index");
    Route::get("/uploadlogo", "Settings\Logos@logo");
    Route::put("/uploadlogo", "Settings\Logos@updateLogo")->middleware(['demoModeCheck']);
});

//SETTINGS - DYNAMIC URLS's
Route::group(['prefix' => 'app/settings'], function () {
    Route::get("/{any}", "Settings\Dynamic@showDynamic")->where(['any' => '.*']);
});
Route::get("app/categories", "Settings\Dynamic@showDynamic");
Route::get("app/tags", "Settings\Dynamic@showDynamic");

//SETTINGS - CRONJOBS
Route::get("/settings/cronjobs", "Settings\Cronjobs@index");

//SETTINGS - TASKS
Route::group(['prefix' => 'settings/subscriptions'], function () {
    Route::get("/plans", "Settings\Subscriptions@plans");
    Route::get("/plans/create", "Settings\Subscriptions@createPlan");
    Route::post("/plans", "Settings\Subscriptions@storePlan")->middleware(['demoModeCheck']);
    Route::put("/plans", "Settings\Subscriptions@updatePlan")->middleware(['demoModeCheck']);
});

//SETTINGS - CUSTOMFIELDS
Route::group(['prefix' => 'settings/customfields'], function () {
    Route::get("/clients", "Settings\Customfields@showClient");
    Route::put("/clients", "Settings\Customfields@updateClient");
    Route::get("/projects", "Settings\Customfields@showProject");
    Route::put("/projects", "Settings\Customfields@updateProject");
    Route::get("/leads", "Settings\Customfields@showLead");
    Route::put("/leads", "Settings\Customfields@updateLead");
    Route::get("/tasks", "Settings\Customfields@showTask");
    Route::put("/tasks", "Settings\Customfields@updateTask");
    Route::get("/tickets", "Settings\Customfields@showTicket");
    Route::put("/tickets", "Settings\Customfields@updateTicket");
    Route::delete("/{id}", "Settings\Customfields@destroy")->where('id', '[0-9]+');
    Route::get("/standard-form", "Settings\Customfields@showStandardForm");
    Route::put("/standard-form-required", "Settings\Customfields@updateStandardFormRequired");
    Route::post("/update-standard-form-positions", "Settings\Customfields@updateFieldPositions");
    Route::put("/standard-form-display-settings", "Settings\Customfields@updateDisplaySettings");

});

//SETTINGS - ERROR LOGS
Route::group(['prefix' => 'settings/errorlogs'], function () {
    Route::get("/", "Settings\Errorlogs@index");
    Route::delete("delete", "Settings\Errorlogs@delete")->where('id', '[0-9]+');
    Route::get("/download", "Settings\Errorlogs@download");
});

//SETTINGS - FILES
Route::group(['prefix' => 'settings/files'], function () {
    Route::get("/general", "Settings\Files@showGeneral");
    Route::put("/general", "Settings\Files@updateGeneral");
    Route::get("/folders", "Settings\Files@folders");
    Route::put("/folders", "Settings\Files@updatefolders")->middleware(['demoModeCheck']);
    Route::get("/defaultfolders", "Settings\Files@defaultFolders");
    Route::post("/defaultfolders", "Settings\Files@defaultFoldersStore");
    Route::get("/defaultfolders/create", "Settings\Files@createFolder");
    Route::post("/defaultfolders/create", "Settings\Files@storeFolder");
    Route::get("/defaultfolders/{folder}/edit", "Settings\Files@editFolder")->where('folder', '[0-9]+');
    Route::put("/defaultfolders/{folder}", "Settings\Files@updateFolder")->where('folder', '[0-9]+');
    Route::delete("/defaultfolders/{folder}", "Settings\Files@deleteFolder")->where('folder', '[0-9]+');;

});

//EVENTS - TIMELINE
Route::group(['prefix' => 'events'], function () {
    Route::get("/topnav", "Events@topNavEvents");
    Route::get("/{id}/mark-read-my-event", "Events@markMyEventRead")->where('id', '[0-9]+');
    Route::get("/mark-allread-my-events", "Events@markAllMyEventRead");
});

//WEBHOOKS & IPN API
Route::group(['prefix' => 'api'], function () {
    Route::any("/stripe/webhooks", "API\Stripe\Webhooks@index");
    Route::any("/paypal/ipn", "API\Paypal\Ipn@index");
    Route::any("/mollie/webhooks", "API\Mollie\Webhooks@index");
    Route::any("/paystack/webhooks", "API\Paystack\Webhooks@index");
});

//POLLING
Route::group(['prefix' => 'polling'], function () {
    Route::get("/general", "Polling@generalPoll");
    Route::post("/timers", "Polling@timersPoll");
    Route::get("/timer", "Polling@activeTimerPoll");
});

//SETUP GROUP (with group route name 'setup'
Route::group(['prefix' => 'setup', 'as' => 'setup'], function () {
    //requirements
    Route::post("/requirements", "Setup\Setup@checkRequirements")->middleware('memo');;
    //server phpinfo()
    Route::get("/serverinfo", "Setup\Setup@serverInfo");
    //database
    Route::get("/database", "Setup\Setup@showDatabase");
    Route::post("/database", "Setup\Setup@updateDatabase");
    //settings
    Route::get("/settings", "Setup\Setup@showSettings");
    Route::post("/settings", "Setup\Setup@updateSettings");
    //admin user
    Route::get("/adminuser", "Setup\Setup@showUser");
    Route::post("/adminuser", "Setup\Setup@updateUser");
    //load first page -put this as last item
    Route::any("/", "Setup\Setup@index");
});

//UPDATING MODALS - Removed due to controller conflicts

//IMPORTING - COMMON
Route::post("/import/uploadfiles", "Fileupload@uploadImportFiles");
Route::get("/import/errorlog", "Import\Common@showErrorLog");

//IMPORT LEADS
Route::resource('import/leads', 'Import\Leads');

//IMPORT CLIENTS
Route::resource('import/clients', 'Import\Clients');

//EXPORT TICKETS
Route::post('export/tickets', 'Export\Tickets@index');

//EXPORT CLIENTS
Route::post('export/clients', 'Export\Clients@index');

//EXPORT PROJECTS
Route::post('export/projects', 'Export\Projects@index');

//EXPORT INVOICES
Route::post('export/invoices', 'Export\Invoices@index');

//EXPORT ESTIMATES
Route::post('export/estimates', 'Export\Estimates@index');

//EXPORT PAYMENTS
Route::post('export/payments', 'Export\Payments@index');

//EXPORT EXPENSES
Route::post('export/expenses', 'Export\Expenses@index');

//EXPORT TIMESHEETS
Route::post('export/timesheets', 'Export\Timesheets@index');

//EXPORT EXPENSES
Route::post('export/items', 'Export\Items@index');

//EXPORT LEADS
Route::post('export/leads', 'Export\Leads@index');

//EXPORT TASKS
Route::post('export/tasks', 'Export\Tasks@index');

//PROJECTS & PROJECT
Route::group(['prefix' => 'templates/projects'], function () {
    Route::any("/search", "Templates\Projects@index")->middleware(['projectTemplatesGeneral']);
    Route::post("/delete", "Templates\Projects@destroy")->middleware(['demoModeCheck']);
    Route::get("/{project}/project-details", "Templates\Projects@details")->middleware(['projectTemplatesGeneral']);
    Route::post("/{project}/project-details", "Templates\Projects@updateDescription");
    //dynamic load
    Route::any("/{project}/{section}", "Templates\Projects@showDynamic")
        ->where(['project' => '-[0-9]+', 'section' => 'details|files|tasks|milestones'])->middleware(['projectTemplatesGeneral']);
});
Route::resource('templates/projects', 'Templates\Projects')->middleware(['projectTemplatesGeneral']);

//REMINDERS
Route::group(['prefix' => 'reminders'], function () {
    Route::get("/start", "Reminders@show");
    Route::get("/show", "Reminders@show");
    Route::get("/edit", "Reminders@edit");
    Route::get("/new", "Reminders@create");
    Route::post("/new", "Reminders@store");
    Route::get("/close", "Reminders@close");
    Route::get("/delete", "Reminders@delete");
    Route::get("/topnav-feed", "Reminders@topNavFeed");
    Route::get("/{id}/delete-reminder", "Reminders@deleteReminder");
    Route::get("/delete-all-my-due-reminders", "Reminders@deleteAllReminders");

});

//WEBMAIL
Route::get("/appwebmail/compose", "Webmail\Compose@compose");
Route::post("/appwebmail/send", "Webmail\Compose@send")->middleware(['demoModeCheck']);
Route::get("/appwebmail/prefill", "Webmail\Compose@prefillTemplate");

//SETTINGS - CLIENT EMAIL TEMPLATES
Route::resource('settings/webmail/templates', 'Settings\WebmailTemplates');

//REPORTING
Route::group(['prefix' => 'reports'], function () {
    Route::get("/", "Reports\Dynamic@showDynamic");
    //dynamic load
    Route::any("/{section}/{optional}", "Reports\Dynamic@showDynamic")
        ->where(['section' => 'start|invoices|estimates|projects|clients|expenses|proposals|timesheets|financial'])
        ->where('optional', '.*');
});
Route::group(['prefix' => 'report'], function () {
    //start page
    Route::get("/start", "Reports\Start@showStart");

    //invoices
    Route::any("/invoices/overview", "Reports\Invoices@overview");
    Route::any("/invoices/month", "Reports\Invoices@month");
    Route::any("/invoices/client", "Reports\Invoices@client");
    Route::any("/invoices/project", "Reports\Invoices@project");
    Route::any("/invoices/category", "Reports\Invoices@category");

    //estimates
    Route::any("/estimates/overview", "Reports\Estimates@overview");
    Route::any("/estimates/month", "Reports\Estimates@month");
    Route::any("/estimates/client", "Reports\Estimates@client");
    Route::any("/estimates/project", "Reports\Estimates@project");
    Route::any("/estimates/category", "Reports\Estimates@category");
    Route::any("/estimates/projectcategory", "Reports\Estimates@projectcategory");

    //projects
    Route::any("/projects/overview", "Reports\Projects@overview");
    Route::any("/projects/client", "Reports\Projects@client");
    Route::any("/projects/project", "Reports\Projects@project");
    Route::any("/projects/category", "Reports\Projects@category");
    Route::any("/projects/projectcategory", "Reports\Projects@projectcategory");

    //clients
    Route::any("/clients/overview", "Reports\Clients@overview");

    //timesheets
    Route::any("/timesheets/team", "Reports\Timesheets@team");
    Route::any("/timesheets/client", "Reports\Timesheets@client");
    Route::any("/timesheets/project", "Reports\Timesheets@project");
    Route::any("/financial/income-expenses", "Reports\IncomeStatement@report");

    //expenses
    Route::any("/expenses/client", "Reports\Expenses@client");
    Route::any("/expenses/project", "Reports\Expenses@project");

    //proposals
    Route::any("/proposals/client", "Reports\Proposals@client");

    // sales - custom reports
    Route::get("/sales/comparison", "Reports\\SalesReports@comparison");
    Route::post("/sales/comparison/data", "Reports\\SalesReports@comparisonData");
    Route::get("/sales/comparison/datatables", "Reports\\SalesReports@comparisonDataTables");
    Route::get("/sales/aggregates", "Reports\\SalesReports@aggregates");
    Route::post("/sales/aggregates/data", "Reports\\SalesReports@aggregatesData");
    
    // sales - analytics reports
    Route::get("/sales/analytics", "Reports\\SalesReports@analytics");
    Route::post("/sales/analytics/monthly-trend", "Reports\\SalesReports@getMonthlyTrend");
    Route::post("/sales/analytics/top-products", "Reports\\SalesReports@getTopProducts");
    Route::post("/sales/analytics/top-customers", "Reports\\SalesReports@getTopCustomers");
    Route::post("/sales/analytics/customer-percentage", "Reports\\SalesReports@getCustomerPercentageAnalysis");
    Route::post("/sales/analytics/profit-analysis", "Reports\\SalesReports@getProfitAnalysis");
    Route::post("/sales/analytics/seasonal-analysis", "Reports\\SalesReports@getSeasonalAnalysis");
    Route::post("/sales/analytics/delivery-status", "Reports\\SalesReports@getDeliveryStatus");
    Route::post("/sales/analytics/focus-summary", "Reports\\SalesReports@getFocusSummary");
    Route::post("/sales/analytics/focus-distributions", "Reports\\SalesReports@getFocusDistributions");
    Route::post("/sales/analytics/unique-values", "Reports\\SalesReports@getUniqueValues");
    Route::get("/sales/analytics/customer-categories", "Reports\\SalesReports@getCustomerCategories");
    Route::get("/sales/analytics/test-data", "Reports\\SalesReports@testAnalyticsData");
    
    Route::get("/sales/test-date-conversion", "Reports\\SalesReports@testDateConversion");
    Route::get("/sales/simple-date-test", "Reports\\SalesReports@simpleDateTest");
    Route::get("/sales/check-database-data", "Reports\\SalesReports@checkDatabaseData");
});

//SPACES
Route::group(['prefix' => 'spaces'], function () {
    //dynamic load
    Route::any("/{space}/{section}", "Spaces@showDynamic")->where(['section' => 'comments|files|notes']);
    Route::any("/{space}", "Spaces@showDynamic");

});

//MESSAGES
Route::group(['prefix' => 'messages'], function () {
    Route::any("/", "Messages@index");
    Route::post("/feed", "Messages@getFeed");
    Route::post("/post/text", "Messages@storeText");
    Route::delete("/{message}", "Messages@destroy");
    Route::post("/fileupload", "Messages@storeFiles");
});

//USER PREFERENCES
Route::group(['prefix' => 'preferences'], function () {

    //table display config
    Route::post("/tables", "Preferences@updateTableConfig");

});

//CLIENT LOGO FILEUPLOAD
Route::post("/search", "Search@index");

//CHECKLISTS
Route::group(['prefix' => 'checklists'], function () {
    Route::any("/search", "Checklists@index");
    Route::post("/import-checklists", "Checklists@importChecklists");
    Route::post("/post-checklist-comment", "Checklists@storeChecklistComment");
    Route::delete("/delete-checklist-comment/{id}", "Checklists@destroyChecklistComment");
    Route::post("/toggle-checklist-status/{checklistid}", "Checklists@toggleChecklistStatus")->where('checklistid', '[0-9]+');
    Route::delete("/delete-checklist/{checklistid}", "Checklists@deleteChecklist")->where('checklistid', '[0-9]+');
    Route::post("/update-checklist-positions", "Checklists@updateChecklistPositions");
    Route::post("/update-checklist/{checklistid}", "Checklists@UpdateChecklist")->where('checklistid', '[0-9]+');
    Route::post("/add-checklist", "Checklists@StoreChecklist");
});
Route::resource('checklists', 'Checklists');


//STARRED
Route::group(['prefix' => 'starred'], function () {
    Route::get("/view/{type}", "Starred@index");
    Route::post("/togglestatus", "Starred@toggleStatus");
    Route::delete("/remove/{id}", "Starred@removeFromFeed");
});

/**----------------------------------------------------------------------------------------------------------------
 * [GROWCRM - CUSTOM ROUTES]
 * ---------------------------------------------------------------------------------------------------------------*/

//AFFILIATES - USERS
Route::group(['prefix' => 'cs/affiliates/users'], function () {
    Route::get("/{id}/changepassword", "CS_Affiliates\Users@editPassword")->where('id', '[0-9]+');
    Route::put("/{id}/changepassword", "CS_Affiliates\Users@updatePassword")->where('id', '[0-9]+');
});
Route::resource('cs/affiliates/users', 'CS_Affiliates\Users');

//AFFILIATES - PROJECTS
Route::group(['prefix' => 'cs/affiliates/projects'], function () {

});
Route::resource('cs/affiliates/projects', 'CS_Affiliates\Projects');

//AFFILIATES - EARNINGS
Route::group(['prefix' => 'cs/affiliates/earnings'], function () {

});
Route::resource('cs/affiliates/earnings', 'CS_Affiliates\Earnings');

//AFFILATE PROFIT
Route::get("/cs/affiliate/my/earnings", "CS_Affiliates\Profit@index");

//ACCOUNTING
Route::group(['prefix' => 'accounting'], function () {
    Route::any("/search", "Accounting@index");
    Route::post("/delete", "Accounting@destroy")->middleware(['demoModeCheck']);
    Route::get("/change-category", "Accounting@changeCategory");
    Route::post("/change-category", "Accounting@changeCategoryUpdate");
    Route::get("/{accounting}/accounting-details", "Accounting@details")->where('accounting', '[0-9]+');
    Route::post("/{accounting}/accounting-details", "Accounting@updateDescription")->where('accounting', '[0-9]+');
    Route::get("/{accounting}/pinning", "Accounting@togglePinning")->where('accounting', '[0-9]+');
});
Route::resource('accounting', 'Accounting');

//INVENTORY
Route::group(['prefix' => 'inventory'], function () {
    Route::any("/search", "InventoryController@index");
    Route::post("/delete", "InventoryController@destroy")->middleware(['demoModeCheck']);
    Route::get("/change-category", "InventoryController@changeCategory");
    Route::post("/change-category", "InventoryController@changeCategoryUpdate");
    Route::get("/{inventory}/inventory-details", "InventoryController@details")->where('inventory', '[0-9]+');
    Route::post("/{inventory}/inventory-details", "InventoryController@updateDescription")->where('inventory', '[0-9]+');
    Route::get("/{inventory}/pinning", "InventoryController@togglePinning")->where('inventory', '[0-9]+');
});
Route::resource('inventory', 'InventoryController');

//INVENTORY IMPORT
Route::group(['prefix' => 'import/inventory', 'middleware' => ['auth']], function () {
    Route::get("/", "Import\Inventory@index");
    Route::post("/", "Import\Inventory@store");
});

//INVOICE SETTLEMENTS
Route::group(['prefix' => 'invoice-settlements'], function () {
    Route::any("/search", "InvoiceSettlementController@index");
});
Route::resource('invoice-settlements', 'InvoiceSettlementController')->only(['index']);

//INVOICE SETTLEMENT IMPORT
Route::group(['prefix' => 'import/invoice-settlements', 'middleware' => ['auth']], function () {
    Route::get("/", "Import\InvoiceSettlements@index");
    Route::post("/", "Import\InvoiceSettlements@store");
});

//SALES
Route::group(['prefix' => 'sales'], function () {
    Route::any("/search", "SalesController@index");
    Route::post("/delete", "SalesController@destroy")->middleware(['demoModeCheck']);
    Route::get("/change-category", "SalesController@changeCategory");
    Route::post("/change-category", "SalesController@changeCategoryUpdate");
    Route::get("/{sales}/sales-details", "SalesController@details")->where('sales', '[0-9]+');
    Route::post("/{sales}/sales-details", "SalesController@updateDescription")->where('sales', '[0-9]+');
    Route::get("/{sales}/pinning", "SalesController@togglePinning")->where('sales', '[0-9]+');
});
Route::resource('sales', 'SalesController');

//SALES IMPORT
Route::group(['prefix' => 'import/sales', 'middleware' => ['auth']], function () {
    Route::get("/", "Import\Sales@index");
    Route::post("/", "Import\Sales@store");
});
```

```blade
<!-- ============================================================== -->
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<aside class="left-sidebar {{ app()->getLocale() == 'ar' || app()->getLocale() == 'fa' || app()->getLocale() == 'ur' || app()->getLocale() == 'he' ? 'rtl-sidebar' : '' }}" id="js-trigger-nav-team">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar" id="main-scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav" id="main-sidenav">
            <ul id="sidebarnav" data-modular-id="main_menu_team">

                
                <!--[MODULES] - dynamic menu-->
                {!! config('modules.menus.main.parent1') !!}

                <!--home-->
                @if(auth()->user()->role->role_homepage == 'dashboard')
                <li data-modular-id="main_menu_team_home"
                    class="sidenav-menu-item {{ $page['mainmenu_home'] ?? '' }} menu-tooltip menu-with-tooltip"
                    title="{{ cleanLang(__('lang.home')) }}">
                    <a class="waves-effect waves-dark" href="/home" aria-expanded="false" target="_self">
                        <i class="ti-home"></i>
                        <span class="hide-menu">{{ cleanLang(__('lang.dashboard')) }}
                        </span>
                    </a>
                </li>
                <!--home-->
                @endif
                
                <!--[MODULES] - dynamic menu-->
                {!! config('modules.menus.main.parent2') !!}

                <!--users[done]-->
                @if(runtimeGroupMenuVibility([config('visibility.modules.clients'),
                config('visibility.modules.users')]))
                <li data-modular-id="main_menu_team_clients"
                    class="sidenav-menu-item {{ $page['mainmenu_customers'] ?? '' }}">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                        <i class="sl-icon-people"></i>
                        <span class="hide-menu">{{ cleanLang(__('lang.customers')) }}
                        </span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        @if(config('visibility.modules.clients'))
                        <li class="sidenav-submenu {{ $page['submenu_customers'] ?? '' }}" id="submenu_clients">
                            <a href="/clients"
                                class="{{ $page['submenu_customers'] ?? '' }}">{{ cleanLang(__('lang.clients')) }}</a>
                        </li>
                        @endif
                        @if(config('visibility.modules.users'))
                        <li class="sidenav-submenu {{ $page['submenu_contacts'] ?? '' }}" id="submenu_contacts">
                            <a href="/users"
                                class="{{ $page['submenu_contacts'] ?? '' }}">{{ cleanLang(__('lang.client_users')) }}</a>
                        </li>
                        @endif
                        <!--[MODULES] - dynamic menu-->
                        {!! config('modules.menus.main.clients') !!}
                    </ul>
                </li>
                @endif
                <!--customers-->
                                
                <!--[MODULES] - dynamic menu-->
                {!! config('modules.menus.main.parent3') !!}

                <!--projects[done]-->
                @if(config('visibility.modules.projects'))
                <li data-modular-id="main_menu_team_projects"
                    class="sidenav-menu-item {{ $page['mainmenu_projects'] ?? '' }}">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                        <i class="ti-folder"></i>
                        <span class="hide-menu">{{ cleanLang(__('lang.projects')) }}
                        </span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        @if(config('system.settings_projects_categories_main_menu') == 'yes')
                        @foreach(config('projects_categories') as $category)
                        <li class="sidenav-submenu" id="submenu_projects">
                            <a href="{{ _url('/projects?filter_category='.$category->category_id) }}"
                                class="{{ $page['submenu_projects_category_'.$category->category_id] ?? '' }}">{{ $category->category_name }}</a>
                        </li>
                        @endforeach
                        @else
                        <li class="sidenav-submenu {{ $page['submenu_projects'] ?? '' }}" id="submenu_projects">
                            <a href="{{ _url('/projects') }}"
                                class="{{ $page['submenu_projects'] ?? '' }}">{{ cleanLang(__('lang.projects')) }}</a>
                        </li>
                        @endif
                        <li class="sidenav-submenu {{ $page['submenu_templates'] ?? '' }}"
                            id="submenu_project_templates">
                            <a href="{{ _url('/templates/projects') }}"
                                class="{{ $page['submenu_templates'] ?? '' }}">{{ cleanLang(__('lang.templates')) }}</a>
                        </li>
                        <!--[MODULES] - dynamic menu-->
                        {!! config('modules.menus.main.projects') !!}
                    </ul>
                </li>
                @endif
               <!--projects-->
                
                <!--[MODULES] - dynamic menu-->
                {!! config('modules.menus.main.parent4') !!}

                <!--tasks[done]-->
                @if(config('visibility.modules.tasks'))
                <li data-modular-id="main_menu_team_tasks"
                    class="sidenav-menu-item {{ $page['mainmenu_tasks'] ?? '' }} menu-tooltip menu-with-tooltip"
                    title="{{ cleanLang(__('lang.tasks')) }}">
                    <a class="waves-effect waves-dark" href="/tasks" aria-expanded="false" target="_self">
                        <i class="ti-menu-alt"></i>
                        <span class="hide-menu">{{ cleanLang(__('lang.tasks')) }}
                        </span>
                    </a>
                </li>
                @endif
                <!--tasks-->
                                
                <!--[MODULES] - dynamic menu-->
                {!! config('modules.menus.main.parent5') !!}

                <!--leads[done]-->
                @if(config('visibility.modules.leads'))
                <li data-modular-id="main_menu_team_leads"
                    class="sidenav-menu-item {{ $page['mainmenu_leads'] ?? '' }} menu-tooltip menu-with-tooltip"
                    title="{{ cleanLang(__('lang.leads')) }}">
                    <a class="waves-effect waves-dark" href="/leads" aria-expanded="false" target="_self">
                        <i class="sl-icon-call-in"></i>
                        <span class="hide-menu">{{ cleanLang(__('lang.leads')) }}
                        </span>
                    </a>
                </li>
                @endif
                <!--leads-->
                                
                <!--[MODULES] - dynamic menu-->
                {!! config('modules.menus.main.parent6') !!}

                <!--sales-->
                @if(runtimeGroupMenuVibility([config('visibility.modules.invoices'),
                config('visibility.modules.payments'), config('visibility.modules.estimates'),
                config('visibility.modules.products'), config('visibility.modules.expenses'),
                config('visibility.modules.proposals')]))
                <li data-modular-id="main_menu_team_billing"
                    class="sidenav-menu-item {{ $page['mainmenu_sales'] ?? '' }}">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                        <i class="ti-wallet"></i>
                        <span class="hide-menu">{{ cleanLang(__('lang.sales')) }}
                        </span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        @if(config('visibility.modules.invoices'))
                        <li class="sidenav-submenu {{ $page['submenu_invoices'] ?? '' }}" id="submenu_invoices">
                            <a href="/invoices"
                                class=" {{ $page['submenu_invoices'] ?? '' }}">{{ cleanLang(__('lang.invoices')) }}</a>
                        </li>
                        @endif
                        @if(config('visibility.modules.payments'))
                        <li class="sidenav-submenu {{ $page['submenu_payments'] ?? '' }}" id="submenu_payments">
                            <a href="/payments"
                                class=" {{ $page['submenu_payments'] ?? '' }}">{{ cleanLang(__('lang.payments')) }}</a>
                        </li>
                        @endif
                        @if(config('visibility.modules.estimates'))
                        <li class="sidenav-submenu {{ $page['submenu_estimates'] ?? '' }}" id="submenu_estimates">
                            <a href="/estimates"
                                class=" {{ $page['submenu_estimates'] ?? '' }}">{{ cleanLang(__('lang.estimates')) }}</a>
                        </li>
                        @endif
                        @if(config('visibility.modules.subscriptions'))
                        <li class="sidenav-submenu {{ $page['submenu_subscriptions'] ?? '' }}"
                            id="submenu_subscriptions">
                            <a href="/subscriptions"
                                class=" {{ $page['submenu_subscriptions'] ?? '' }}">{{ cleanLang(__('lang.subscriptions')) }}</a>
                        </li>
                        @endif
                        @if(config('visibility.modules.products'))
                        <li class="sidenav-submenu {{ $page['submenu_products'] ?? '' }}" id="submenu_products">
                            <a href="/products"
                                class=" {{ $page['submenu_products'] ?? '' }}">{{ cleanLang(__('lang.products')) }}</a>
                        </li>
                        @endif
                        @if(config('visibility.modules.expenses'))
                        <li class="sidenav-submenu {{ $page['submenu_expenses'] ?? '' }}" id="submenu_expenses">
                            <a href="/expenses"
                                class=" {{ $page['submenu_expenses'] ?? '' }}">{{ cleanLang(__('lang.expenses')) }}</a>
                        </li>
                        @endif
                        <!--[MODULES] - dynamic menu-->
                        {!! config('modules.menus.main.sales') !!}
                    </ul>
                </li>
                @endif
                <!--billing-->

                <!--[MODULES] - dynamic menu-->
                {!! config('modules.menus.main.parent7') !!}

                <!--proposals [multiple]-->
                @if(config('visibility.modules.proposals') && auth()->user()->role->role_templates_proposals > 0)
                <!--multipl menu-->
                <li data-modular-id="main_menu_team_proposals"
                    class="sidenav-menu-item {{ $page['mainmenu_proposals'] ?? '' }}">
                    <!--multiple menu-->
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                        <i class="ti-bookmark-alt"></i>
                        <span class="hide-menu">{{ cleanLang(__('lang.proposals')) }}
                        </span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li class="sidenav-submenu {{ $page['submenu_proposals'] ?? '' }}" id="submenu_proposals">
                            <a href="{{ _url('/proposals') }}"
                                class="{{ $page['submenu_proposals'] ?? '' }}">{{ cleanLang(__('lang.proposals')) }}</a>
                        </li>
                        <li class="sidenav-submenu {{ $page['submenu_proposal_templates'] ?? '' }}"
                            id="submenu_proposal_templates">
                            <a href="{{ _url('/templates/proposals') }}"
                                class="{{ $page['submenu_templates'] ?? '' }}">{{ cleanLang(__('lang.templates')) }}</a>
                        </li>
                        <!--[MODULES] - dynamic menu-->
                        {!! config('modules.menus.main.proposals') !!}
                    </ul>
                </li>
                @endif
                <!--proposals-->
                
                <!--[MODULES] - dynamic menu-->
                {!! config('modules.menus.main.parent8') !!}

                <!--proposals [single]-->
                @if(config('visibility.modules.proposals') && auth()->user()->role->role_templates_proposals == 0)
                <li data-modular-id="main_menu_team_proposals"
                    class="sidenav-menu-item {{ $page['mainmenu_proposals'] ?? '' }} menu-tooltip menu-with-tooltip"
                    title="{{ cleanLang(__('lang.proposals')) }}">
                    <a class="waves-effect waves-dark p-r-20" href="/proposals" aria-expanded="false" target="_self">
                        <i class="ti-bookmark-alt"></i>
                        <span class="hide-menu">{{ cleanLang(__('lang.proposals')) }}
                        </span>
                    </a>
                </li>
                @endif
                
                <!--[MODULES] - dynamic menu-->
                {!! config('modules.menus.main.parent9') !!}

                <!--contracts [multiple]-->
                @if(config('visibility.modules.contracts') && auth()->user()->role->role_templates_contracts > 0)
                <!--multipl menu-->
                <li data-modular-id="main_menu_team_contracts"
                    class="sidenav-menu-item {{ $page['mainmenu_contracts'] ?? '' }}">
                    <!--multiple menu-->
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                        <i class="ti-write"></i>
                        <span class="hide-menu">{{ cleanLang(__('lang.contracts')) }}
                        </span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li class="sidenav-submenu {{ $page['submenu_contracts'] ?? '' }}" id="submenu_contracts">
                            <a href="{{ _url('/contracts') }}"
                                class="{{ $page['submenu_contracts'] ?? '' }}">{{ cleanLang(__('lang.contracts')) }}</a>
                        </li>
                        <li class="sidenav-submenu {{ $page['submenu_contract_templates'] ?? '' }}"
                            id="submenu_contract_templates">
                            <a href="{{ _url('/templates/contracts') }}"
                                class="{{ $page['submenu_contract_templates'] ?? '' }}">{{ cleanLang(__('lang.templates')) }}</a>
                        </li>
                        <!--[MODULES] - dynamic menu-->
                        {!! config('modules.menus.main.contracts') !!}
                    </ul>
                </li>
                @endif
                <!--contracts-->
                
                <!--[MODULES] - dynamic menu-->
                {!! config('modules.menus.main.parent10') !!}

                <!--contracts [single]-->
                @if(config('visibility.modules.contracts') && auth()->user()->role->role_templates_contracts == 0)
                <li data-modular-id="main_menu_team_contracts"
                    class="sidenav-menu-item {{ $page['mainmenu_contracts'] ?? '' }} menu-tooltip menu-with-tooltip"
                    title="{{ cleanLang(__('lang.contracts')) }}">
                    <a class="waves-effect waves-dark p-r-20" href="/contracts" aria-expanded="false" target="_self">
                        <i class="ti-write"></i>
                        <span class="hide-menu">{{ cleanLang(__('lang.contracts')) }}
                        </span>
                    </a>
                </li>
                @endif


                <!--[MODULES] - dynamic menu-->
                {!! config('modules.menus.main.parent11') !!}

                <!--spaces-->
                @if(config('visibility.modules.spaces'))
                <li data-modular-id="main_menu_team_spaces hidden"
                    class="sidenav-menu-item {{ $page['mainmenu_spaces'] ?? '' }}">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                        <i class="ti-layers"></i>
                        <span class="hide-menu">@lang('lang.spaces')
                        </span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        @if(config('system.settings2_spaces_user_space_status') == 'enabled')
                        <li class="sidenav-submenu {{ $page['submenu_spaces_my'] ?? '' }}" id="submenu_spaces_my">
                            <a href="{{ _url('/spaces/'.auth()->user()->space_uniqueid) }}"
                                class="{{ $page['submenu_spaces_my'] ?? '' }}">
                                {{ config('system.settings2_spaces_user_space_menu_name') }}
                            </a>
                        </li>
                        @endif
                        @if(config('system.settings2_spaces_team_space_status') == 'enabled')
                        <li class="sidenav-submenu {{ $page['submenu_spaces_team'] ?? '' }}" id="submenu_spaces_team">
                            <a href="{{ _url('/spaces/'.config('system.settings2_spaces_team_space_id')) }}"
                                class="{{ $page['submenu_spaces_team'] ?? '' }}">
                                {{ config('system.settings2_spaces_team_space_menu_name') }}
                            </a>
                        </li>
                        @endif
                        <!--[MODULES] - dynamic menu-->
                        {!! config('modules.menus.main.spaces') !!}
                    </ul>
                </li>
                @endif
                <!--spaces-->


                <!--support-->
                <li data-modular-id="main_menu_team_support"
                    class="sidenav-menu-item {{ $page['mainmenu_support'] ?? '' }}">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                        <i class="ti-comments"></i>
                        <span class="hide-menu">{{ cleanLang(__('lang.support')) }}
                        </span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <!--tickets-->
                        @if(config('visibility.modules.tickets'))
                        <li class="sidenav-submenu {{ $page['submenu_tickets'] ?? '' }}" id="submenu_tickets">
                            <a href="{{ _url('/tickets') }}"
                                class="{{ $page['submenu_tickets'] ?? '' }}">{{ cleanLang(__('lang.tickets')) }}</a>
                        </li>
                        @endif
                        <!--canned-->
                        @if(auth()->user()->is_team)
                        <li class="sidenav-submenu {{ $page['submenu_canned'] ?? '' }}" id="submenu_canned">
                            <a href="{{ _url('/canned') }}"
                                class="{{ $page['submenu_canned'] ?? '' }}">{{ cleanLang(__('lang.canned')) }}</a>
                        </li>
                        @endif
                        <!--knowledgebase-->
                        @if(config('visibility.modules.knowledgebase'))
                        <li class="sidenav-submenu {{ $page['submenu_knowledgebase'] ?? '' }}"
                            id="submenu_knowledgebase">
                            <a href="{{ _url('/knowledgebase') }}"
                                class="{{ $page['submenu_knowledgebase'] ?? '' }}">{{ cleanLang(__('lang.knowledgebase')) }}</a>
                        </li>
                        @endif
                        <!--messaging-->
                        @if(config('visibility.modules.messages'))
                        <li class="sidenav-submenu {{ $page['submenu_messages'] ?? '' }}" id="submenu_messages">
                            <a href="{{ _url('/messages') }}"
                                class="{{ $page['submenu_messages'] ?? '' }}">{{ cleanLang(__('lang.messages')) }}</a>
                        </li>
                        @endif
                        <!--[MODULES] - dynamic menu-->
                        {!! config('modules.menus.main.support') !!}
                    </ul>
                </li>
                
                <!--[MODULES] - dynamic menu-->
                {!! config('modules.menus.main.parent12') !!}

                <!--team-->
                @if(auth()->user()->is_team)
                <li data-modular-id="main_menu_team_team"
                    class="sidenav-menu-item {{ $page['mainmenu_settings'] ?? '' }}">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                        <i class="sl-icon-user"></i>
                        <span class="hide-menu">{{ cleanLang(__('lang.team')) }}
                        </span>
                    </a>
                    <ul aria-expanded="false" class="position-top collapse">
                        @if(config('visibility.modules.team'))
                        <li class="sidenav-submenu mainmenu_team {{ $page['submenu_team'] ?? '' }}" id="submenu_team">
                            <a href="/team"
                                class="{{ $page['submenu_team'] ?? '' }}">{{ cleanLang(__('lang.team_members')) }}</a>
                        </li>
                        @endif
                        @if(config('visibility.modules.timesheets'))
                        <li class="sidenav-submenu mainmenu_timesheets {{ $page['submenu_timesheets'] ?? '' }}"
                            id="submenu_timesheets">
                            <a href="/timesheets"
                                class="{{ $page['submenu_timesheets'] ?? '' }}">{{ cleanLang(__('lang.time_sheets')) }}</a>
                        </li>
                        @endif
                        <!--[MODULES] - dynamic menu-->
                        {!! config('modules.menus.main.team') !!}
                    </ul>
                </li>
                @endif
                
                <!--[MODULES] - dynamic menu-->
                {!! config('modules.menus.main.parent13') !!}

                <!--accounting-->
                <li data-modular-id="main_menu_team_accounting"
                    class="sidenav-menu-item {{ $page['mainmenu_accounting'] ?? '' }}">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false">
                        <i class="ti-bar-chart"></i>
                        <span class="hide-menu">{{ cleanLang(__('lang.accounting')) }}
                        </span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li class="sidenav-submenu {{ $page['submenu_inventory'] ?? '' }}" id="submenu_inventory">
                            <a href="{{ _url('/inventory') }}"
                                class="{{ $page['submenu_inventory'] ?? '' }}">
                                <i class="ti-package"></i>
                                {{ cleanLang(__('lang.inventory')) }}
                            </a>
                        </li>
                        <li class="sidenav-submenu {{ $page['submenu_sales'] ?? '' }}" id="submenu_sales">
                            <a href="{{ _url('/sales') }}"
                                class="{{ $page['submenu_sales'] ?? '' }}">
                                <i class="ti-shopping-cart"></i>
                                {{ cleanLang(__('lang.sales')) }}
                            </a>
                        </li>
                        <li class="sidenav-submenu {{ $page['submenu_invoice_settlements'] ?? '' }}" id="submenu_invoice_settlements">
                            <a href="{{ _url('/invoice-settlements') }}"
                                class="{{ $page['submenu_invoice_settlements'] ?? '' }}">
                                <i class="ti-clipboard"></i>
                                {{ cleanLang(__('lang.invoice_settlements')) }}
                            </a>
                        </li>
                    </ul>
                </li>
                <!--accounting-->

                <!--reports-->
                @if(config('visibility.modules.reports'))
                <li data-modular-id="main_menu_reports"
                    class="sidenav-menu-item {{ $page['mainmenu_reports'] ?? '' }} menu-tooltip menu-with-tooltip"
                    title="{{ cleanLang(__('lang.reports')) }}">
                    <a class="waves-effect waves-dark p-r-20" href="/reports" aria-expanded="false" target="_self">
                        <i class="sl-icon-chart"></i>
                        <span class="hide-menu">@lang('lang.reports')
                        </span>
                    </a>
                </li>
                @endif
                
                <!--[MODULES] - dynamic menu-->
                {!! config('modules.menus.main.parent14') !!}
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
```

```php
<?php

/**
 * [TRANSLATION INSTRUCTIONS]
 * ==========================================
 * For instructions on how to translate this file, please see https://growcrm.io/documentation/language-translation/
 *
 **/

return [

    'a_project_is_required_for_billable_expenses' => "A Project is required (for billable expenses)",
    'accept_amendments' => "Accept Changes",
    'accept_estimate_confirm' => "Please confirm that you want to accept this estimate",
    'accept_estimate' => "Accept Estimate",
    'accept' => "Accept",
    'accepted' => "Accepted",
    'account_has_been_suspended' => "Your account has been suspended",
    'account_not_found' => "Account does not exist",
    'account_number' => "Account Number",
    'account_owner' => "Account Owner",
    'account_status' => "Account Status",
    'account' => "Account",
    'action_items' => "Action Items",
    'action_not_available_in_demo_mode' => "This request is not avaiable in demo mode",
    'action_not_completed_errors_found' => "The request could not be completed",
    'action_only_available_on_declined_estimates' => "This request is only available on declined estimates",
    'action' => "Action",
    'actions_not_available' => "This action is not available on this resource",
    'actions' => "Actions",
    'activate' => "Activate",
    'active_leads' => "Active Sales Activity",
    'active_projects' => "Active Projects",
    'active_tasks' => "Active Tasks",
    'active_users' => "Active Users",
    'active' => "Active",
    'activity' => "Activity",
    'add_a_new_category' => "Add A New Category",
    'add_a_new_item' => "Add a new product",
    'add_a_payment' => "Add A New Payment",
    'add_article' => "Create A Knowledgebase Article",
    'add_attachment' => "Add a file attachment",
    'add_category' => "Create A New Category",
    'add_client_manager' => "Add Client Manager",
    'add_client' => "Add Client",
    'add_comment' => "Add Comment",
    'add_contact' => "Add Contact",
    'add_credit_note' => "Add Credit Note",
    'add_department' => "Add Department",
    'add_due_date' => "Add Due Date",
    'add_estimate' => "Create A New Estimate",
    'add_event' => "Add Event",
    'add_expense' => "Add A New Expense",
    'add_file' => "Add File",
    'add_invoice' => "Create A New Invoice",
    'add_item' => "Add Comment",
    'add_knowledge_base' => "Create A New Article",
    'add_knowledgebase_category' => "Add Knowledgebase Category",
    'add_lead_source' => "Add lead Source",
    'add_lead' => "Add Lead",
    'add_lineitem_items_first' => "First add invoice products",
    'add_milestone_category' => "Add A Milestone Category",
    'add_milestone' => "Add A New Milestone",
    'add_new_item' => "Create A New Item",
    'add_new_lead_status' => "Add New Lead Status",
    'add_new_lead' => "Add A New Lead",
    'add_new_payment' => "Add A New Payment",
    'add_note' => "Create A New Note",
    'add_payment' => "Add Payment",
    'add_product' => "Create A New Product",
    'add_project' => "Create A New Project",
    'add_role' => "Create A New Role",
    'add_selected_items' => "Add Selected Items",
    'add_subscription' => "Create A New Subscription",
    'add_tag' => "Add Tag",
    'add_task' => "Add A New Task",
    'add_tax_rate' => "Add Tax Rate",
    'add_taxrate' => "New Tax Rate",
    'add_this_inside_your_dashboard' => "Add this url inside your dashboard",
    'add_ticket' => "Open A New Support Ticket",
    'add_to_invoice' => "Add To Invoice",
    'add_url_inside_stripe' => "Add this url inside your Stripe account",
    'add_user' => "Create A New User",
    'add' => "Add",
    'added_by' => "Added By",
    'added' => "Added",
    'additional_information' => "Additional Information",
    'additional_settings' => "Additional Settings",
    'address_and_organisation_details' => "Address & Organisation Details",
    'address' => "Address",
    'agree_to_terms' => "Agree to terms and conditions",
    'all_estimates' => "All Estimates",
    'all_expenses' => "All Expenses",
    'all_invoices' => "All Invoices",
    'all_leads' => "All leads",
    'all_projects' => "All Projects",
    'all_setting_permissions_when_adding_project' => "Allow user to set client and staff permissions when adding a new project",
    'all_tasks' => "All Tasks",
    'all_tickets' => "All Tickets",
    'all_time' => "All Time",
    'all' => "All",
    'allow_customers_to_signup' => "Allow customer to signup",
    'allow_editing_of_ticket_message' => "Allow editing of support ticket message",
    'allow_editing_of_ticket_subject' => "Allow editing of support ticket subject",
    'allow_project_managers_to_edit_milestone' => "Allows project managers to change and edit project milestones",
    'allow_user_tags' => "Allow users to create new tags",
    'allow_users_to_change_backgrounds' => "Allow users to change backgrounds",
    'allow_users_to_change_language' => "Allow Users To Change Language",
    'allow_users_to_change_theme' => "Allow users to change themes",
    'allow_users_to_view_kb_when_not_logged_in' => "Allow users to view knowledgebase when they are not logged in",
    'allowed_avatar_file_type' => "Allowed avatar file types are (jpeg or png)",
    'allowed_file_types' => "Allowed File Types",
    'already_exists' => "already exists",
    'already_have_an_account' => "Already have an account?",
    'amount_before_tax' => "Amount Before Tax",
    'amount' => "Amount",
    'answered' => "Answered",
    'api_key' => "API Key",
    'apostrophe' => "Apostrophe",
    'app_settings' => "App Settings",
    'app_version_could_not_be_veried' => "Your app version could not be verified",
    'app' => "App",
    'application_backup_instructions' => "You can backup your application via your web hosting control panel (e.g. Cpanel)",
    'application_error' => "A system error occurred whilst trying to process this request",
    'apply_filter' => "Apply Filter",
    'apply' => "Apply",
    'april_short' => "Apr",
    'april' => "April",
    'archive_lead' => "Archive Lead",
    'archive_project' => "Archive Project",
    'archive_task' => "Archive Task",
    'archive' => "Archive",
    'archived' => "Archived",
    'archives_leads' => "Archived Sales Activity",
    'archives_projects' => "Archived Projects",
    'archives_tasks' => "Archived Tasks",
    'archives' => "Archives",
    'are_you_sure_delete' => "Are you sure you want to delete this item?",
    'are_you_sure' => "Are you sure?",
    'Article Ordering' => "Article Ordering",
    'article_not_found' => "Article could not be found",
    'article_title' => "Article Title",
    'article' => "Article",
    'articles' => "Articles",
    'ascending_order' => "Ascending Order",
    'assiged_manager_not_found' => "Assigned manager could not be found",
    'assiged_user_not_found' => "Assigned user could not be found",
    'assign_leads' => "Assign Sales Activity",
    'assign_projects' => "Assign Projects",
    'assign_tasks' => "Assign Tasks",
    'assign_users' => "Assign Users",
    'assign' => "Assign",
    'assigned_to_me' => "Assigned to me",
    'assigned_to_you' => "Assigned to you",
    'assigned_to' => "Assigned To",
    'assigned_user_permissions' => "Assigned User's Permissions",
    'assigned_users' => "Assigned Users",
    'assigned' => "Assigned",
    'assigning_users_to_a_task_info' => "Assign task to team members assigned to the project",
    'at' => "At",
    'attach_dettach' => "Attach or Dettach",
    'attach_receipt' => "Attach A Receipt",
    'attach_to_project' => "Attach To A Project",
    'attach' => "Attach",
    'attachement' => "File Attachment",
    'attachments' => "File Attachments",
    'august_short' => "Aug",
    'august' => "August",
    'available_to_all_clients' => "Available to all clients",
    'awaiting_feedback' => "Awaiting Feedback X",
    'back_to_login' => "Back to login page",
    'background' => "Background images",
    'balance_due' => "Balance Due",
    'balance' => "Balance",
    'bank' => "Bank",
    'banking_details' => "Banking Details",
    'before_tax' => "Before Tax",
    'best_image_dimensions' => "Best image dimensions",
    'bi Monthly' => "Bi-Monthly",
    'bi_weekly' => "Bi-Weekly",
    'bill_an_expense' => "Invoice An Expenses",
    'bill_estimateid' => "Estimate ID",
    'bill_recurring_cycle_start_date' => "Billing Cycle Start Date",
    'bill_recurring_cycles_explanation_1' => "When you first create a recurring invoice, you must set the date when the recurring cycle will start. This rest of the dates will then be set automatically once the cycle has started.",
    'bill_recurring_cycles_explanation_2' => "Invoices that fall on days that are not present in a particular month (e.g. 29th, 30th, 31st), these invoices will be generated on nearest date (e.g. 28th)",
    'bill_recurring_cycles_explanation_3' => "The first invoice to be issued, is the parent/main invoice. This invoice will be sent as soon as you have published it",
    'bill_recurring_grace_period_info' => "Number of days before the newly created invoice becomes due for payment",
    'bill_recurring_grace_period' => "Recurring Invoice - Due Date Grace Period",
    'bill_recurring_period_info' => "Invoice will stop recurring after these number of cycles. For infinity, set the value to 0",
    'bill_to' => "Invoice To",
    'billable_expenses' => "Billable Expenses",
    'billable_hours_info' => "These are all the hours that have not yet been invoiced for this project",
    'billable_hours' => "Billable Hours",
    'billable_time' => "Billable Time",
    'billable' => "Billable",
    'billing_address' => "Billing Address",
    'billing_type' => "Billing Type",
    'billing' => "Billing",
    'bin' => "Delete",
    'body' => "HTML Body Section",
    'bulk_invoice_payments' => "Bulk Invoice Payments",
    'calendar' => "Calendar",
    'call_log' => "Call Log",
    'can_access_dashboard' => "Can Access Dashboard",
    'can_only_be_set_as_global' => "Can only be set as Global",
    'cancel_subscription' => "Cancel Subscription",
    'cancel_upload' => "Cancel upload",
    'cancel' => "Cancel",
    'cancelled' => "Cancelled",
    'categories' => "Categories",
    'Category Ordering' => "Category Ordering",
    'category_already_exists' => "A category with that name already exists",
    'category_icon' => "Category Icon",
    'category_name' => "Category Name",
    'category_not_empty' => "This category is not empty. First move its items to another category",
    'category_not_found' => "Category could not be found",
    'category' => "Category",
    'change_background' => "Change Background",
    'change_category' => "Change Category",
    'change_logo' => "Change Logo",
    'change_milestone' => "Change Milestone",
    'change_status' => "Change Status",
    'change_theme' => "Change Theme",
    'change_your_password' => "Change your password. Minimum of 6 characters.",
    'changes_will_apply_on_next_date' => "Changes will apply on the next invoice renewal date",
    'check_for_updates' => "Check for updates",
    'check_logs_for_details' => "Check logs for details",
    'checkbox' => "Checkbox",
    'checking_for_updates' => "Checking for updates",
    'checklist' => "Checklist",
    'city' => "City",
    'clear_cache_confirm' => "This will clear your applications system cache",
    'clear_cache' => "Clear Cache",
    'click_here' => "Click Here",
    'client_already_exists' => "This client already exists",
    'client_email_required_for_dashboard_access' => "Client name & email address are required for a contact with dashboard access",
    'client_emails' => "Client Emails",
    'client_files' => "Client Files",
    'client_has_not_paid_for_subsccrition' => "Client has not yet paid for this subscription",
    'client_has_not_yet_accepted_the_contract' => "Client has not yet accepted the contract",
    'client_id' => "Client ID",
    'client_is_required' => "A client is required",
    'client_manager_information' => "A client manager will have full access to all the clients projects, billing etc",
    'client_manager' => "Client Manager",
    'client_name_is_required' => "Client name is required",
    'client_name' => "Client Name",
    'client_not_found' => "Client could not be found",
    'client_permissions' => "Client Permissions",
    'client_project_permissions' => "Client's Project Permissions",
    'client_project' => "Client & Project",
    'client_rejected_the_contract' => "Client rejected the contract",
    'client_signature' => "Client Signature",
    'client_users' => "Client Users",
    'client_visibility_invalid' => "Client visibility option is invalid",
    'client_visibility' => "Client Visibility",
    'client' => "Client",
    'clients' => "Clients",
    'clone_contract' => "Clone Contract",
    'clone_estimate' => "Clone Estimate",
    'clone_invoice' => "Clone Invoice",
    'clone_lead' => "Clone Lead",
    'clone_project' => "Clone Project",
    'clone_proposal' => "Clone Proposal",
    'clone_task' => "Clone Task",
    'cloning_failed' => "Cloning has failed. See log for details",
    'close_modal_windows_on_page_click' => "Close Modal Window On Page Click",
    'close' => "Close",
    'closed_tasks' => "Closed Tasks",
    'closed' => "Closed",
    'closing_balance' => "Closing Balance",
    'collapsed' => "Collapsed",
    'color' => "Color",
    'comma' => "Comma",
    'comment_not_found' => "Comment could not be found",
    'comment' => "Comment",
    'comments' => "Comments",
    'company_details' => "Company Details",
    'company_logo' => "Company Logo",
    'company_name' => "Company Name",
    'company' => "Company",
    'complete_your_payment' => "Complete Your Payment",
    'completed_projects' => "Completed Projects",
    'completed_tasks' => "Completed Tasks",
    'completed' => "Completed",
    'confirm_action' => "Confirm Action",
    'confirm_password' => "Confirm Password",
    'confirm' => "Confirm",
    'confirmation_text_does_not_match' => 'confirmation text does not match',
    'contact' => "Contact",
    'contacted' => "Contacted",
    'contacts' => "Contacts",
    'continue' => "Continue",
    'contract_amendments' => "Contract Amendments",
    'contract_has_been_amended' => "Contract has been amended",
    'contract_has_been_amended' => "This contract has been amended",
    'contract_has_not_been_signed' => "This contract has not yet been signed",
    'contract_id' => "Contract ID",
    'contract_prefix' => "Contract Prefix",
    'contract_template' => "Contract Template",
    'contract_terms' => "Contract Terms",
    'contract_type' => "Contract Type",
    'contract_value' => "Contract Value",
    'contract' => "Contract",
    'contracts' => "Contracts",
    'conversation_record_notes' => "Conversation Notes",
    'convert_expense_to_invoice' => "Convert Expense To Invoice",
    'convert_to_a_customer' => "Convert To A Customer",
    'convert_to_customer' => "Convert To Customer",
    'convert_to_invoice' => "Convert To Invoice",
    'convert' => "Convert",
    'converted_by' => "Converted By",
    'converted' => "Converted",
    'costs_estimate' => "Estimated Costs",
    'count' => "Count",
    'country' => "Country",
    'create_a_new_account' => "Create a new account",
    'create_a_reminder' => "Create A Reminder",
    'create_contract_template' => "Create Contract Template",
    'create_estimate_template' => "Create Estimate Template",
    'create_from_template' => "Create From Template",
    'create_invoice_template' => "Create Invoice Template",
    'create_new_account' => "Create New Account",
    'create_new_contract' => "Create A New Contract",
    'create_new' => "Create New",
    'create_product' => "Create Product",
    'create_project_template' => "Create Project Template",
    'create_subscription' => "Create A Subscription",
    'create_tasks' => "Create Tasks",
    'create_template' => "Create template",
    'created_by' => "Created By",
    'created' => "Created",
    'credit_note' => "Credit Note",
    'credit' => "Credit",
    'cron_job_command' => "Cron Job Command",
    'cronjob_and_emails' => "Your Cron Job does not seem to be running. Email delivery will not work until this has been fixed.",
    'cronjob_inactive' => "The system Cron Job does not appear to be active. This status message will update once the Cronjob has run",
    'cronjob_instructions' => "Configure this cronjob, inside your webhosting  control panel. You must set it to run every minute.",
    'cronjob_last_executed' => "The Cron Job was last executed",
    'cronjob_settings' => "Cron Job Settings",
    'cronjob_status' => "Cron Job Status",
    'currency_code' => "Currency code",
    'currency_code' => "Currency Code",
    'currency_symbol_position' => "Currency Symbol Position",
    'currency_symbol' => "Currency Symbol",
    'currency' => "Currency",
    'current_version' => "Current Version",
    'custom_fields' => "Custom Fields",
    'custom_form_fields' => "Custom Fields",
    'customer' => "Customer",
    'customers' => "Customers",
    'customize' => "Customize",
    'cycle' => "Cycle",
    'cycles' => "Cycles",
    'daily' => "Daily",
    'dark_mode' => "Dark Mode",
    'dashboard' => "Dashboard",
    'data_transfer_token' => "Data Transfer Token",
    'date_added' => "Date Added",
    'date_cannot_be_in_future' => "Date cannot be in the future",
    'date_cannot_be_in_past' => "Date cannot be in the past",
    'date_created' => "Date Created",
    'date_format' => "Date Format",
    'date_is_not_valid' => 'date is invalid',
    'date_last_contacted' => "Date Last Contacted",
    'date_picker_format' => "Date Selector Format",
    'date' => "Date",
    'dates_information' => "Dates Information",
    'day' => "Day",
    'days_before' => "days before",
    'days' => "Days",
    'deadline' => "Deadline",
    'debit' => "Debit",
    'december_short' => "Dec",
    'december' => "December",
    'decimal_separator' => "Decimal Separator",
    'decline_estimate_confirm' => "Please confirm that you want to decline this estimate",
    'decline_estimate' => "Decline Estimate",
    'decline' => "Decline",
    'declined' => "Declined",
    'default_category' => "Default Catagory",
    'default_hourly_rate' => "Default Hourly Rate",
    'default_language' => "Default Language",
    'default_milestone' => "Default Milestones",
    'default_milestones' => "Default Milestones",
    'default_theme_not_found' => "The selected theme could not be found",
    'default_theme' => "Default Theme",
    'default_units' => "Default Units",
    'default' => "Default",
    'delete_all_tasks' => "Delete all tasks",
    'delete_article' => "Delete Article",
    'delete_canned_template' => "Delete Template",
    'delete_category' => "Delete Category",
    'delete_client_manager' => "Delete Client Manager",
    'delete_client' => "Delete Client",
    'delete_comment' => "Delete Comment",
    'delete_confirmation' => "Delete Confirmation",
    'delete_contract' => "Delete Contract",
    'delete_credit_note' => "Delete Credit Note",
    'delete_estimate' => "Delete Estimate",
    'delete_file' => "Delete File",
    'delete_invoice' => "Delete Invoice",
    'delete_item' => "Delete Item",
    'delete_lead_source' => "Delete Lead Source ",
    'delete_lead_status' => "Delete Lead Status ",
    'delete_lead' => "Delete Lead",
    'delete_milestone' => "Delete Milestone",
    'delete_note' => "Delete Note",
    'delete_payment' => "Delete Payment",
    'delete_product' => "Delete Product",
    'delete_project' => "Delete Project",
    'delete_proposal' => "Delete Proposal",
    'delete_selected_items' => "Delete Selected Items",
    'delete_subscription' => "Delete Subscription",
    'delete_tag' => "Delete Tag",
    'delete_task' => "Delete Task",
    'delete_ticket' => "Delete Support Ticket",
    'delete_user_role' => "Delete User Role",
    'delete_user' => "Delete User",
    'delete' => "Delete",
    'department_name' => "Department Name",
    'department' => "Department",
    'departments' => "Departments",
    'descending_order' => "Descending Order",
    'description_no_html' => "Description should not contain any HTML",
    'description' => "Description",
    'design' => "Design",
    'detach_from_project' => "Detach From Project",
    'details' => "Details",
    'dettach_item' => "Detach Item",
    'digital_signature' => "Digital Signature",
    'disable' => "Disable",
    'disabled' => "Disabled",
    'discount_is_invalid' => "Discount is invalid",
    'discount' => "Discount",
    'discounts' => "Discounts",
    'dismiss_notifications' => "Dismiss All Notifications",
    'display_name_info' => "Text displayed to the customer (e.g. Credit Card, Bank Transfer)",
    'display_name' => "Display Name",
    'disqualified' => "Disqualified",
    'dm' => "DM",
    'do_not_email_me_notifications' => "Do not send an email of these notifications",
    'does_not_exist' => "does not exists",
    'done' => "Done",
    'dont_have_an_account' => "Don't have an account?",
    'download_database_backup' => "Download Database Backup",
    'download_pdf' => "Download PDF",
    'download_updates' => "Download updates",
    'download_updates' => "Download Updates",
    'download' => "Download",
    'draft' => "Draft",
    'drafts' => "Drafts",
    'drag_drop_file' => "Drop files here or click to upload",
    'drag_drop_not_supported' => "Your browser does not support drag and drop",
    'drop_down_list' => "Drop Down List",
    'due_date_must_be_after_start_date' => "Due Date must be after the Start Date",
    'due_date' => "Due Date",
    'due_invoices' => "Due Invoice",
    'due' => "Due",
    'duration' => "Duration",
    'each' => "Each",
    'edit_article' => 'Edit Article',
    'edit_call_log' => "Edit Call Log",
    'edit_canned_template' => "Edit Template",
    'edit_category' => "Edit Category",
    'edit_client' => "Edit Client",
    'edit_comment' => "Edit Comment",
    'edit_contact' => "Edit Contact",
    'edit_contract_template' => "Edit Contract Template",
    'edit_contract' => "Edit Contract",
    'edit_credit_note' => "Edit Credit Note",
    'edit_delete_permission_only_for_user' => "[edit] & [delete] permissions only apply to content created by the user",
    'edit_department' => "Edit Department",
    'edit_description' => "Edit Description",
    'edit_details' => "Edit Details",
    'edit_estimate' => "Edit Estimate",
    'edit_expense' => "Edit Expense",
    'edit_file' => "Edit File",
    'edit_invoice_template' => "Edit Invoice Template",
    'edit_invoice' => "Edit Invoice",
    'edit_item' => "Edit Item",
    'edit_knowledgebase' => "Edit Knowledgebase",
    'edit_lead_source' => "Edit Lead Source",
    'edit_lead_status' => "Edit Lead Status",
    'edit_lead' => "Edit Lead",
    'edit_milestone' => "Edit Milestone",
    'edit_note' => "Edit Note",
    'edit_payment_method' => "Edit Payment Method",
    'edit_payment' => "Edit Payment",
    'edit_product' => "Edit Product",
    'edit_project_template' => "Edit Project Template",
    'edit_project' => "Edit Project",
    'edit_proposal' => "Edit Proposal",
    'edit_settings' => "Edit Settings",
    'edit_tag' => "Edit Tag",
    'edit_task' => "Edit Task",
    'edit_tax_rate' => "Edit Tax rate",
    'edit_ticket' => "Edit Support Ticket",
    'edit_unit' => "Edit Unit",
    'edit_user_role' => "Edit User Role",
    'edit_user' => "Edit User",
    'edit' => "Edit",
    'email_delivery' => "Email Delivery",
    'email_address' => "Email Address",
    'email_already_exists' => "Email address is already in use",
    'email_body' => "Email Body",
    'email_delivery_test' => "Email Delivery Test",
    'email_delivery_this_is_a_test' => "This is a test email delivery",
    'email_frequency' => "Email Sending Frequency",
    'email_is_required' => "Email is required",
    'email_me_notifications' => "Send me an email of these notifications",
    'email_notifications' => "Email Notifications",
    'email_subject' => "Email Subject",
    'email_templates' => "Email Templates",
    'email_to_client' => "Email To Client",
    'email' => "Email",
    'enable_and_disable_features' => "Enable & Disable Features",
    'enable_payment_method' => "Enable Payment Method",
    'enable_shipping_address' => "Enable shipping address",
    'enable_smtp_email' => "Enable SMTP Email",
    'enable' => "Enable",
    'enabled' => "Enabled",
    'encryption' => "Encryption",
    'end_date' => "End Date",
    'end' => "End",
    'enter_new_password' => "Please enter your new password. Minimum 6 characters.",
    'enter_product_code' => "Enter your product purchase code",
    'error_404' => "404 Error",
    'error_check_logs_for_details' => "Error processing your request. Please check error logs for details",
    'error_communicating_updates_server' => "Error communicating with the update server",
    'error_loading_item' => "The request item could loaded at this time",
    'error_no_permission_for_resource' => "You do not have permission for this action",
    'error_not_found' => "The request item could not be found",
    'error_request_could_not_be_completed' => "An error was encountered processing your request",
    'error_session_timeout' => "Your session has timed out. Please login again",
    'estimate_aleady_published' => "This estimate has already been published",
    'estimate_date' => "Estimate Date",
    'estimate_has_expired_update_date' => "This estimate has expired. Update the expiry date and try again",
    'estimate_not_found' => "Estimate could not be found",
    'estimate_prefix' => "Estimate Prefix",
    'estimate_still_draft' => "Estimate is still in draft status",
    'estimate_template' => "Estimate Template",
    'estimate_terms' => "Estimate Terms & Conditions",
    'estimate' => "Estimate",
    'estimated_hours' => "Estimated Hours",
    'estimates' => "Estimates",
    'event_accepted_estimate' => "Accepted an estimate",
    'event_assigned_user_to_a_lead_alt' => 'Assigned a lead to',
    'event_assigned_user_to_a_lead' => 'Assigned you to a lead',
    'event_assigned_user_to_a_project_alt' => 'Assigned a project to',
    'event_assigned_user_to_a_project' => 'Assigned you to a project',
    'event_assigned_user_to_a_task_alt' => 'Assigned a task to',
    'event_assigned_user_to_a_task' => 'Assigned you to a task',
    'event_attached_a_file' => "Attached a file",
    'event_cancelled_subscription' => "Cancelled a scubscription",
    'event_cancelled_the_subscription' => "Cancelled the scubscription",
    'event_changed_bill_status' => "Changed invoice status",
    'event_changed_lead_status' => "Changed lead status",
    'event_changed_project_status' => "Changed project status",
    'event_changed_status' => "Changed status",
    'event_changed_task_status_completed' => "Completed a task",
    'event_changed_task_status' => "Changed task status",
    'event_changed_ticket_status' => "Changed support ticket status",
    'event_closed_ticket' => "Closed a support ticket",
    'event_created_estimate' => "Created an new estimate",
    'event_created_invoice' => "Created a new invoice",
    'event_created_lead' => "Created a new task",
    'event_created_project' => "Created a new project",
    'event_created_subscription' => "Created a new subscription",
    'event_created_task' => "Created a new task",
    'event_declined_estimate' => "Declined an estimate",
    'event_opened_ticket' => "Opened a new support ticket",
    'event_paid_invoice' => "Paid an invoice",
    'event_paid_subscription' => "Paid a scubscription",
    'event_paid_the_subscription' => "Paid the scubscription",
    'event_posted_a_comment' => "Posted a new comment",
    'event_replied_ticket' => "Replied a support ticket",
    'event_revised_estimate' => "Revised an estimate",
    'event_type' => "Event Type",
    'event_uploaded_a_file' => "Uploaded a file",
    'event_user_assigned' => "Assigned you to",
    'events_such_as' => "Events include: comments, file uploads, payments, invoices, etc",
    'everyone' => "Everyone",
    'everything' => "Everything",
    'example_csv_file' => "Example CSV File",
    'example' => "Example",
    'exit_settings' => "Exit",
    'expense_has_already_been_invoiced_cannot_be_attached' => "This expense has already been invoiced. It can no longer be attached or detached.",
    'expense_has_already_been_invoiced' => "This expense has already been invoiced",
    'expense_has_already_been_invoiced' => "This expense has been invoiced. Client and project can longer be changed",
    'expense_not_billable' => "This expense is not marked as billable",
    'expense_not_found' => "Expense could not be found",
    'expense_records' => "Expense Record",
    'expense' => "Expense",
    'expenses' => "Expenses",
    'expired' => "Expired",
    'expires' => "Expires",
    'expiry_date_must_be_after_estimate_date' => "Expiry date must be after estimate date",
    'expiry_date' => "Expiry Date",
    'expiry' => "Expiry",
    'export_all_records' => "Export all records",
    'export_failed' => "Exporting records failed, please see error logs for details",
    'export_file_fomart' => "Export File Format",
    'export_format' => "Export Format",
    'export_records' => "Export Records",
    'export_visible_records' => "Export only the displayed records",
    'export' => "Export",
    'failed' => "Failed",
    'features' => "Features",
    'february_short' => "Feb",
    'february' => "February",
    'female' => "Female",
    'file_name' => "File Name",
    'file_not_found' => "File could not be found",
    'file_too_big' => "File is too big",
    'file_type_not_allowed' => "File type is not allowed",
    'file_upload_error' => "File could not be uploaded",
    'files' => "Files",
    'fill_in_all_fields' => "Fill in all fields",
    'fill_in_all_required_fields' => "Fill in all required fields",
    'filter_all_projects' => "Filter All Projects",
    'filter_clients' => "Filter Clients",
    'filter_contacts' => "Filter Contacts",
    'filter_contracts' => "Filter Contracts",
    'filter_estimates' => "Filter Estimates",
    'filter_expenses' => "Filter Expenses",
    'filter_invoices' => "Filter Invoices",
    'filter_leads' => "Filter Sales Activity",
    'filter_my_projects' => "Filter My Projects",
    'filter_payments' => "Filter Payments",
    'filter_products' => "Filter Products",
    'filter_projects' => "Filter Projects",
    'filter_proposals' => "Filter Proposals",
    'filter_subscriptions' => "Filter Subsriptions",
    'filter_tags' => "Filter Tags",
    'filter_tasks' => "Filter Tasks",
    'filter_tickets' => "Filter Support Tickets",
    'filter_timesheets' => 'Filter Timesheets',
    'filter' => "Filter",
    'final_amount' => "Final Amount",
    'final_reminder' => "Final Reminder",
    'final_stage' => "Final Stage",
    'financial' => "Financial",
    'finish' => "Finish",
    'first_billing_date' => "First Billing Date",
    'first_invoice' => "First Invoice",
    'first_name' => "First Name",
    'first_reminder' => "First Reminder",
    'fixed_amount' => "Fixed Amount",
    'fixed_fee' => "Fixed Fee",
    'fixed' => "Fixed",
    'flter_timesheets' => "Filter Timesheets",
    'for' => "For",
    'forgot_password' => "Forgot Password",
    'form_field' => "Form Field",
    'frequency' => "Frequency",
    'from' => "From",
    'full_stop' => "Full Stop",
    'gender' => "Gender",
    'general_settings' => "General Settings",
    'general_variables' => "General Variables",
    'get_from_paylpal_dashboard' => "Get this value form inside your Paypal dashboard",
    'get_help_from_knowledgebase' => "Get answers and help from our knowledgebase",
    'global_level' => "Global Level",
    'global' => "Global",
    'gross' => "Gross",
    'group_by_task' => "Group By Task",
    'group_by_user' => "Group By User",
    'grouped' => "Grouped",
    'grouping' => "Grouping",
    'head_body_information' => "HTML or Javascript code pasted here will be place inside the [HEAD] & [BODY] tags (e.g. Google Analytics)",
    'head' => "HTML Head Section",
    'help_documentation' => "Help Documentation",
    'hidden' => "Hidden",
    'hide' => "Hide",
    'high' => "High",
    'home' => "Home",
    'hour' => "Hour",
    'hourly' => "Hourly",
    'hours_before' => "hours before",
    'hours_worked' => "Hours Worked",
    'hours' => "Hours",
    'how_to_install_updates' => "How to install updates",
    'hr' => "Hr",
    'hrs' => "Hrs",
    'id' => "ID",
    'if_items_selected_then_viewing_perm' => "If selected, then (viewing permission) will be enabled automatically & vise-versa",
    'if_you_do_not_delete_tasks' => "If you do not delete tasks, they will be moved into (uncategoried) milestones",
    'image_dimensions_not_allowed' => "Images dimensions are not allowed",
    'image_file_type_not_supported' => "Image file type is not supported",
    'import_clients' => "Import Clients",
    'import_error_format' => "Your file is not in the expected format. You can download the sample files to use as a template",
    'import_leads' => "Import Leads",
    'import_send_welcome_email' => "Send welcome emails",
    'import_users' => "Import Users",
    'import_wizard_download_template' => "Download Data Template",
    'import_wizard_error' => "Importing data failed, please see error logs for details",
    'import_wizard_Invalid_file_type' => "Invalid file type. Only Excel and CVS files allowed",
    'import_wizard_passed' => "Importing data was successful",
    'import_wizard_upload_file' => "Upload Data File",
    'importing_wizard_info' => "To import data, it must be in Excel or CSV file format. You can download the sample files and use them as a template for your data",
    'importing_wizard' => "Importing Wizard",
    'in_progress' => "In Progress",
    'inbox' => "Inbox",
    'income_vs_expense' => "Income vs Expenses",
    'income' => "Income",
    'incomplete' => "Incomplete",
    'information' => "Information",
    'initial_stage' => "Initial Stage",
    'inline_tax' => "In-line Tax",
    'installation_directoy_not_deleted' => "Waring! - Install directory has not been deleted",
    'invalid_category' => "Invalid Category",
    'invalid_department' => "Invalid department",
    'invalid_discount' => "The discount is not valid",
    'invalid_email_address' => "Invalid email address",
    'invalid_login_details' => "Invalid login details",
    'invalid_or_missing_data' => "Invalid or missing data",
    'invalid_priority' => "Invalid Priority",
    'invalid_repeat_every' => "Invalid (repeat every...) value",
    'invalid_status' => "Invalid Status",
    'invalid_ticket_priority' => "Invalid support ticket priority",
    'invalid_ticket_status' => "Invalid support ticket status",
    'invoice_already_piblished' => "This invoice has already been published",
    'invoice_amount' => "Invoice Amount",
    'invoice_automatically_created_from_recurring' => "This invoice was automatically created from a recurring invoice",
    'invoice_date' => "Invoice Date",
    'invoice_id' => "Invoice ID",
    'invoice_is_already_fully_paid' => "This invoice is already fully paid",
    'invoice_not_found' => "Invoice could not be found",
    'invoice_payment' => "Invoice Payment",
    'invoice_prefix' => "Invoice Prefix",
    'invoice_products' => "Invoice Products",
    'invoice_reminder' => "Invoice Reminder",
    'invoice_still_draft' => "Invoice is still a draft",
    'invoice_template' => "Invoice Template",
    'invoice_terms' => "Invoice Terms",
    'invoice_total' => "Invoice Total",
    'invoice_vs_expenses' => "Invoice vs Expenses",
    'invoice' => "Invoice",
    'invoiced' => "Invoiced",
    'invoices' => "Invoices",
    'is_already_taken' => 'is already taken',
    'is_invalid' => "is invalid",
    'is_not_a_valid_email_address' => 'is not a valid email address',
    'is_not_a_valid_file' => 'is not a valid file',
    'is_not_a_valid_image' => 'is not a valid image',
    'is_not_a_valid_ip_address' => 'is not a valid IP address',
    'is_not_a_valid_number' => 'is not a valid number',
    'is_not_a_valid_url' => 'is not a valid URL',
    'is_required' => 'is required',
    'item_is_attached_to_invoice_cannot_be_edited' => "This time has been invoiced and connot be edited. First remove it from the invoice.",
    'item_nolonger_exists_or_removed' => "This item no longer exists, or it has been updated by another user",
    'item_not_found' => "Item could not be found",
    'item_summary' => "Item Summary",
    'item' => "Item",
    'items' => "Items",
    'january_short' => "Jan",
    'january' => "January",
    'job_title' => "Job Title",
    'july_short' => "Jul",
    'july' => "July",
    'june_short' => "Jun",
    'june' => "June",
    'kanban_board_settings' => "Kanban Board Settings",
    'kanban_card_front_settings_info' => "Select the items that are shown on the front of each card, when viewing in Kanban mode",
    'kanban_pagination_limits' => "Kanban Boards - Pagination Limits",
    'kanban_pagination_limits' => "Kanban Pagination Limits",
    'kanban_view' => "Kanban View",
    'key_id' => "Key ID",
    'knowledgebase_article' => "Knowledgebase Article",
    'knowledgebase_not_found' => "Knowledgebase record could not be found",
    'knowledgebase' => "Knowledgebase",
    'large_logo' => "Large Logo",
    'last_contact' => "Last Contact",
    'last_contacted' => "Last Contacted",
    'last_month' => "Last Month",
    'last_name' => "Last Name",
    'last_payment' => "Last Payment",
    'last_renewed' => "Last Renewed",
    'last_seen' => "Last Seen",
    'last_update' => "Latest Update",
    'last_week' => "Last Week",
    'last_year' => "Last Year",
    'latest_activity' => "Latest Activity",
    'latest_comments' => "Latest Comments",
    'latest_payment' => "Latest Payment",
    'lead_id' => "Lead ID",
    'lead_not_found' => "Lead could not be found",
    'lead_settings' => "Lead Settings",
    'lead_source' => "Lead Source",
    'lead_sources' => "Lead Sources",
    'lead_stages' => "Lead Stages",
    'lead_status_is_invalid' => "Lead status is invalid",
    'lead_title' => "Lead Title",
    'lead_value' => "Lead Value",
    'lead' => "Lead",
    'leads' => "Leads",
    'left_menu_position' => "Left Menu Position - Default Position",
    'left' => "Left",
    'light_mode' => "Light Mode",
    'linked_to' => "Linked To",
    'list_view' => "List View",
    'live_mode' => "Live Mode",
    'live' => "Live",
    'log_in' => "Sign In",
    'login_to_you_account' => "Account Login",
    'logo_used_on_pdf_documents' => "Also used on invoices, estimates, etc.",
    'logo_used_when_menu_is_collapsed' => "Used when the main menu is collapsed",
    'logo_used_when_menu_is_expanded' => "Used when the main menu is expanded",
    'logout' => "Logout",
    'loss' => "Loss",
    'low' => "Low",
    'main_administrator' => "Main Administrator",
    'main_menu_default_state' => "Main Menu Default State",
    'main_menu' => "Main Menu",
    'main_settings' => "Main Settings",
    'main_theme' => "Main Theme",
    'make_a_payment' => "Make A Payment",
    'male' => "Male",
    'manage_knowledgebase' => "Manage Knowledgebase",
    'manager' => "Manager",
    'manually_set_progress' => "Manually Set Progress",
    'march_short' => "Mar",
    'march' => "March",
    'mark_as_signed' => "Mark As Signed",
    'mark_estimates_as_billable_by_default' => "Mark expense as billable by default",
    'max' => "Max",
    'maximum_size' => "Maximum Size",
    'maximum_upload_files_reached' => "Maximum allowed files has been reached",
    'maximum' => "Maximum",
    'may_short' => "May",
    'may' => "May",
    'message' => "Message",
    'method' => "Method",
    'milestone_already_exists' => "Milestone already exists ",
    'milestone_is_not_empty' => "Milestone has linked tasks. You must first move them",
    'milestone_name' => "Milestone Name",
    'milestone_not_found' => "Milestone could not be found",
    'milestone_settings' => "Milestone Settings",
    'milestone' => "Milestone",
    'milestones' => "Milestones",
    'min' => "Min",
    'minimum_six_characters' => "min 6 characters",
    'minimum_size' => "Minimum Size",
    'minimum' => "Minimum",
    'mins' => "Mins",
    'minute' => "Minute",
    'minutes_before' => "minutes before",
    'minutes' => "Minutes",
    'modal_window_close_on_body_click' => "Close Modal Window On Page Click",
    'modal_window' => "Modal Window",
    'modify_subscription' => "Modify Subscription",
    'month_months' => "Month(s)",
    'month' => "Month",
    'monthly' => "Monthly",
    'months' => "Months",
    'more_information' => "More Information",
    'more_options' => "More Options",
    'more' => "More",
    'move_artiles_to_another_category' => "Move articles to another category",
    'move_down' => "Move Up",
    'move_tasks_to_another_milestone' => "Move tasks to another milestone",
    'move_tickets_to_another_department' => "Move support tickets to another department",
    'move_to_another_category' => "Move items to another category",
    'move_to_this_stage' => "Move To This Stage",
    'move_up' => "Move Down",
    'move' => "Move",
    'multiple' => "Multiple",
    'must_be_a_nuber' => 'must be a number',
    'must_be_a_number_greater_than' => "must be a number greater than",
    'must_be_a_number_not_greater_than' => "must be a number not greater than",
    'must_be_a_whole_nuber' => 'must be a whole number',
    'must_be_accepted' => 'must be accepted',
    'must_be_greater_than_or_equal_to' => "must be greater than or equal to",
    'must_be_greater_than' => "must be greater than",
    'must_not_be_blank' => 'must not be blank',
    'must_not_contain_any_html' => "must not contain any HTML",
    'must_only_contain_letters_numbers_dashes' => 'must only contain letters, numbers, and dashes',
    'must_only_contain_letters_numbers' => 'must only contain letters and numbers',
    'must_only_contain_letters' => 'must only contain letters',
    'must_only_contain_numbers' => 'must only contain numbers',
    'my_estimates' => "My Estimates",
    'my_leads' => "My Sales Activity",
    'my_messages' => "My Messages",
    'my_projects' => "My Projects",
    'my_reminders' => "My Reminders",
    'my_reminder' => "My Reminder",
    'my_tasks' => "My Tasks",
    'my_time_sheets' => "My Time Sheets",
    'my_time' => "My Time",
    'my_timer' => "My Timer",
    'my_todo_list' => "My TO DO List",
    'name' => "Name",
    'need_more_help' => "Need More Help",
    'net_profit' => "Net Profit",
    'net' => "Net",
    'new_assignment' => "New Assignment",
    'new_attachment' => "New FIle Attachment",
    'new_blank_line' => "New Line",
    'new_call_log' => "New Call Log",
    'new_comment' => "New Comment",
    'new_estimate' => "New Estimate",
    'new_expense' => "New Expense",
    'new_file' => "New File",
    'new_invoice' => "New Invoice",
    'new_lead' => "New Lead",
    'new_payment' => "New Payment",
    'new_project' => "New Project",
    'new_status' => "New status",
    'new_task' => "New Task",
    'new_ticket' => "New Support Ticket",
    'new_time_line' => "Time Line",
    'new_updates_available' => "A new update is available",
    'new_updates_available' => "New updates available",
    'new_version' => "New Version",
    'new' => "New",
    'newest_first' => "Newest First",
    'next_bill_date' => "Next Invoice Date",
    'next_billing_date_cannot_be_in_past' => "Next Invoice Date cannot be in the past",
    'next_invoice_date_cannot_be_past' => "Next invoice date cannot be in the past",
    'next_month' => "Next Month",
    'next_payment' => "Next Payment",
    'next_renewal' => "Next Renewal",
    'next_week' => "Next Week",
    'next_year' => "Next Year",
    'next' => "Next",
    'no_actions_available' => "No actions are available",
    'no_details_found' => "No details found",
    'no_discount' => "No Discount",
    'no_grouping' => "No Grouping",
    'no_leads' => "No Sales Activity",
    'no_new_updates_available' => "No new updates where found",
    'no_notifications_found' => "No notifications found!",
    'no_payments_found' => "No Payments Found",
    'no_results_found' => "No records were found",
    'no_subsccription_plans_found' => "No subscription plans were found",
    'no_tax_rates_available' => "No tax rates available. Tax rates are added via the Dashboard settings panel",
    'no_tax' => "No Tax",
    'no_updates_available' => "No updates are currently available",
    'no' => "No",
    'none' => "None",
    'normal' => "Normal",
    'not_attached_to_project' => "Not attached to a project",
    'not_billable' => "Not Billable",
    'not_invoiced' => "Not Invoiced",
    'not_started' => "Not Started",
    'not_visible_to_the_client' => "not visible to the client",
    'not_visisble_to_client' => "Not visible to the client",
    'note_not_found' => "Note could not be found",
    'note' => "Note",
    'notes' => "Notes",
    'nothing' => "Nothing",
    'notification_and_email' => "Notification & Email",
    'notification_only' => "Notification",
    'notification_settings' => "Notification Settings",
    'notifications' => "Notifications",
    'notify_me_about_these_events' => "Notify & email me about these events",
    'notify_me_via_email' => "Notify me via email",
    'notify_me_via_sms' => "Notify me via SMS",
    'notify_me' => "Notify My",
    'november_short' => "Nov",
    'november' => "November",
    'now' => "Now",
    'ny_notes' => "My Notes",
    'october_short' => "Oct",
    'october' => "October",
    'ok' => "Ok",
    'oldest_first' => "Oldest First",
    'on_hold' => "On Hold",
    'on' => "On",
    'one_of_the_selected_items_nolonger_exists' => "One of the selected items no longer exists. Refresh and try again",
    'only_available_to_specified_customers' => "Only available to specified clients",
    'open_a_support_ticket' => "Open A Support Ticket",
    'open_projects' => "Open Projects",
    'open_support_ticket' => "Open a support ticket",
    'open' => "Open",
    'opening_balance' => "Opening Balance",
    'optional' => "Optional",
    'options' => "Options",
    'order_articles_by' => "Order articles by",
    'Ordering' => "Ordering",
    'organisation' => "Organisation",
    'other_details' => "Other Details",
    'other' => "Other",
    'overdue_invoices' => "Overdue Invoices",
    'overdue' => "Overdue",
    'overview' => "Overview",
    'pagination_limits' => "Pagination Limits",
    'pagination' => "Pagination",
    'paid_invoices' => "Paid Invoices",
    'paid' => "Paid",
    'part_paid' => "Part Paid",
    'password_confirmation' => "Password Confirmation",
    'password_reset_email_sent' => "We have sent you an email with instructions",
    'password_reset_success' => "Success! You can now login with your new password",
    'password' => "Password",
    'pay_invoices' => "Pay Invoices",
    'pay_now' => "Pay Now",
    'pay' => "Pay",
    'payment_date' => "Payment Date",
    'payment_gateway_currency_code_example' => "Payment gateway supported currency Code (example: USD, GBP)",
    'payment_gateway' => "Payment Gateway",
    'payment_history' => "Payment History",
    'payment_id' => "Payment ID",
    'payment_method' => "Payment Method",
    'payment_methods' => "Payment Methods",
    'payment_not_found' => "Payment could not be found",
    'payment' => "Payment",
    'payments_amount' => "Payments Amount",
    'payments' => "Payments",
    'paypal_api_instructions' => "Add this url inside your Paypal dashboard",
    'paypal_ipn_url' => "Paypal IPN url",
    'payu_money_key' => "PayU Money Key",
    'payu_salt_key' => "PayU Salt Key",
    'pending_tasks' => "Pending Tasks",
    'pending' => "Pending",
    'percentage' => "Percentage",
    'performance' => "Performance",
    'period' => "Period",
    'permission_denied_for_this_item' => "Permission denied for this item - ID",
    'permissions' => "Permissions",
    'phone' => "Phone",
    'plan' => "Plan",
    'planning' => "Planning",
    'please_backup_first' => "Please make sure you have backed up your application before doing an update",
    'please_contact_support' => "Please contact support for assistance",
    'please_enter_account_email_address' => "Please enter your account email address below.",
    'please_wait' => "Please wait...",
    'position' => "Position",
    'post_a_comment' => "Post a comment",
    'post' => "Post",
    'preferences' => "Preferences",
    'preview' => "Preview",
    'previous' => "Previous",
    'print' => "Print",
    'priority' => "Priority",
    'private_api_key' => "Private API Key",
    'private_auth_key' => "Private Auth Key",
    'private' => "Private",
    'product_item' => "Product Item",
    'product_not_found' => "Product could not be found",
    'product_purchase_code' => "This code can be found inside your Codecanyon Dashboard",
    'product_units' => "Product Units",
    'product' => "Product",
    'products' => "Products",
    'profile' => "Profile",
    'profit' => "Profit",
    'progress' => "Progress",
    'project_billing_info' => "Project Billing Info",
    'project_billing' => "Project Billing",
    'project_cost_estimate_info' => "Your projected expenses on this project. This information is not visible to the client. You can also leave it blank.",
    'project_estimated_hours_info' => "The amount of time you expect to send on this project. Adding this information,will help you keep track of your time budget.",
    'project_files' => "Project Files",
    'project_id' => "Project ID",
    'project_is_required' => "A project is required",
    'project_manager_info' => "A project manager will have admin level permissions to manage all aspects of this project",
    'project_manager' => "Project Manager",
    'project_not_found' => "Project could not be found",
    'project_notes_not_visible_to_client' => "Project notes are not visible to the client",
    'project_progress' => "Project Progress",
    'project_proposal' => "Project Proposal",
    'project_settings' => "Project Settings",
    'project_template' => "Project Template",
    'project_title' => "Project Title",
    'project' => "Project",
    'projects' => "Projects",
    'proposal_amount' => "Proposal Amount",
    'proposal_prefix' => "Proposal Prefix",
    'publish_contract' => "Publish Contract",
    'publish_estimate' => "Publish Estimate",
    'publish_invoice' => "Publish Invoice",
    'publish_proposal' => "Publish Proposal",
    'publish_revised_estimate' => "Publish Revised Estimate",
    'publish' => "Publish",
    'publishable_key' => "Publishable Key",
    'purchase_code_could_not_be_confirmed' => "Your product purchase code could not be verified. You can try and enter the code again",
    'purchase_code' => "Product Purchase Code",
    'qty' => "Qty",
    'qualified' => "Qualified",
    'quantity_is_invalid' => "Quantity is invalid",
    'quick_edit' => "Quick Edit",
    'quick_filters' => "Quick Filters",
    'quick_stats' => "Quick Stats",
    'rate_is_invalid' => "Rate is invalid",
    'rate' => "Rate",
    'razorpay_general_info' => "Get this value from inside your Razorpay dashboard",
    'razorpay_key' => "RazoprPay Key",
    'read_documentation_for_instructions' => "IMPORTANT: First read the application documentation, for clear, step by step instructions.",
    'recaptcha' => "Captcha",
    'recorded_by' => "Recorded By",
    'recurring_child_invoices' => "Recurring Child Invoices",
    'recurring_invoice_options_info' => "Recurring invoice options are available after an invoice has been created",
    'recurring_invoice_options' => "Recurring Invoice Options",
    'recurring_invoice' => "Recurring Invoice",
    'recurring_invoices' => "Recurring Invoices",
    'recurring_options' => "Recurring Options",
    'recurring_settings' => "Recurring Settings",
    'recurring' => "bar",
    'recurring' => "Recurring",
    'reject_amendments' => "Contract Amendments",
    'reject' => "Reject",
    'related' => "Related",
    'remember_me' => "Remember me",
    'remove_event' => "Remove Event",
    'remove_file' => "Remove file",
    'remove_reminder' => "Remove Reminder",
    'remove_star' => "remove Star",
    'renewal_history' => "Renewal History",
    'renewed' => "Renewed",
    'repeat_every' => "Repeat every",
    'repeat_value_greater_than_zero' => "The (repeat every...) value must be bigger than zero",
    'reply_ticket' => "Reply Support Ticket",
    'reply' => "Reply",
    'report' => "Report",
    'reports' => "Reports",
    'request_could_not_be_completed' => "Request could not be completed",
    'request_has_been_completed' => "Request has been completed",
    'request_is_invalid' => "Request is invalid",
    'required_data_is_missing' => "Required data is missing",
    'required_leads_stage' => "Required - System Lead Stage",
    'required' => "Required",
    'resend' => "Resend",
    'reset_instructions_sent_to_email' => "We have sent password reset instructions to your email address",
    'reset_password' => "Reset Password",
    'reset' => "Reset",
    'resource_id' => "Resource ID",
    'resource_type' => "Resource Type",
    'resource' => "Resource",
    'restore_lead' => "Restore Lead",
    'restore_project' => "Restore Project",
    'restore_task' => "Restore Task",
    'restore' => "Restore",
    'revised' => "Revised",
    'right' => "Right",
    'role_already_exists' => "Role already exists",
    'role_name' => "Role Name",
    'role_not_empty' => "You must first move this roles existing users",
    'role' => "Role",
    'roles_scope_info' => "Global permissions give a user admin level permissions on the resource",
    'sales' => "Sales",
    'same_as_billing' => "Same As Billing",
    'sandbox_mode_info' => "Sandbox is test mode",
    'sandbox_mode' => "Sandbox Mode",
    'sandbox' => "Sandbox",
    'save_canned_reply_as_a_template' => "Save as a template",
    'save_changes' => "Save Changes",
    'save_product' => "Save Product",
    'save' => "Save",
    'search_term' => "Search Term",
    'search' => "Search",
    'second_reminder' => "Second Reminder",
    'second' => "Second",
    'seconds' => "Seconds",
    'secret_key' => "Secret Key",
    'secret_word' => "Secret Word",
    'section_not_available_in_demo_mode' => "This section is not available in demo mode",
    'see_articles' => "see articles",
    'see_documentation_for_details' => "See documentation for details",
    'see_information_below' => "See Information Below",
    'select_a_subscrition_pan' => "Select a subscription plan",
    'select_atleast_one_item' => "You must select at least one item",
    'select_background' => "Select Background",
    'select_email_template_from_dropdown' => "Select an email template from the dropdown menu",
    'select_export_format' => "Select Export File Format",
    'select_features_you_want' => "You can select the features that you want to use in your app. Some features are core and cannot be disabled",
    'select_invoice' => "Select Invoice",
    'select_invoices' => "Select Invoices",
    'select_payment_method' => "Select Payment Method",
    'select_price' => "Select A Price",
    'select_product' => "Select A Product",
    'select_theme' => "Select Theme",
    'select' => "Select",
    'selected_expense_is_already_on_invoice' => "One of the selected expenses is already on the invoice",
    'selected_theme_is_invalid' => "The selected theme is invalid",
    'send_a_message' => "Send A Message",
    'send_a_welcome_email' => "Send a welcome email",
    'send_email_reminder' => "Send Email Reminder",
    'send_email_to_client' => "Send an email to the client",
    'send_email_using' => "Send Email Using",
    'send_email' => "Send email",
    'send_message' => "Send Message",
    'send_test_email' => "Send A Test Email",
    'send' => "Send",
    'sent' => "Sent",
    'september_short' => "Sep",
    'september' => "September",
    'server_info' => "Server Info",
    'session_timed_out_login' => "Your session has timed out. Please login.",
    'set_default_table_columns' => "Set default table columns",
    'set_progress_manually' => "Set progress manually",
    'set_progress_manually' => "Set Progress Manually",
    'set_project_permissions' => "Set Project Permissions",
    'setting_welcome_message_sub' => "Select any item from the left menu",
    'setting_welcome_message' => "Manage Application Settings",
    'settings' => "Settings",
    'shipping_address' => "Shipping Address",
    'show_archive_leads' => "Show Archived Sales Activity",
    'show_archive_projects' => "Show Archived Projects",
    'show_archive_tasks' => "(Show/Hide) Archived Tasks",
    'show_invoice' => "Show on invoices",
    'show_lead_after_adding' => "Show lead after adding",
    'show_lead' => "Show on lead summary",
    'show_more' => "show more",
    'show_on_invoices' => "Show On Invoices",
    'show_permission_project_creation' => "Show permissions settings during project creation",
    'show_profile' => "Show on client profile",
    'show_project_after_its_created' => "Show project after its been created",
    'show_project' => "Show on project profile",
    'show_task_after_adding' => "Show task after adding",
    'show_task' => "Show on task summary",
    'show' => "Show",
    'sign_contract' => "Sign Contract",
    'sign_in_to_your_account' => "Sign in to your account",
    'sign_in' => "Sign In",
    'sign_up_for_your_account' => "Sign up for you new account today!",
    'sign_up' => "Sign Up",
    'signature' => "Signature",
    'signed' => "Signed",
    'size' => "Size",
    'small_logo' => "Small Logo",
    'sms_gateway' => "SMS Gateway",
    'smtp_host' => "SMTP Host",
    'smtp_port' => "SMTP Port",
    'smtp_settings' => "SMTP Settings",
    'social_profile_username' => "profile username",
    'social_profile' => "Social Profile",
    'sort_by' => "Sort By",
    'sort' => "Sort",
    'source_already_exists' => "Source already exists",
    'source_name' => "Source Name",
    'source' => "Source",
    'space' => "Space",
    'stage_not_empty' => "This stage is not empty. First move its leads to another category",
    'star_item' => "Star Item",
    'starred_clients' => "Starred Client",
    'starred_invoices' => "Starred Invoices",
    'starred_items' => "Starred Items",
    'starred_leads' => "Starred Sales Activity",
    'starred_projects' => "Starred projects",
    'starred_tasks' => "Starred Tasks",
    'starred_users' => "Starred Users",
    'start_date_must_be_before_due_date' => "Start Date must be before the Due Date",
    'start_date' => "Start Date",
    'start_time' => "Start Time",
    'start_timer' => "Start Timer",
    'start' => "Start",
    'state' => "State",
    'stats_panel_default_state' => "Stats Panel Default State",
    'stats_panel_default' => "Stats Panel - Default Position",
    'status_already_exists' => "Status already exists",
    'status_changed' => "Status Changed",
    'status_name' => "Status Name",
    'status' => "Status",
    'stop_all_timers' => "Stop All Timers",
    'stop_my_timer' => "Stop My Timer",
    'stop_recurring' => "Stop Recurring",
    'stop_time' => "Stop Time",
    'stop_timer' => "Stop Timer",
    'street' => "Street",
    'stripe_account_error' => "Unable to connect to your Stripe account.",
    'stripe_authentication_error' => "Stripe Error - Unable to authenticate with Stripe. Check your API keys",
    'stripe_general_info' => "You get this value from inside your Stripe dashboard",
    'stripe_generic_error' => "Stripe Error",
    'stripe_id' => "Stripe ID",
    'stripe_network_error' => "Stripe Network Error - Your server was unable to connect to api.stripe.com",
    'stripe_not_enabled' => "Stripe is not enabled",
    'stripe_payment' => "Stripe Payment",
    'stripe_price' => "Stripe Price",
    'stripe_product' => "Stripe Product",
    'stripe_products_info' => "Subscription products and plans are created inside your Stripe dashboard",
    'stripe_products_not_found' => "No products (plans) were found. You must first create then inside your Stripe dashboard",
    'subject' => "Subject",
    'submit_ticket' => "Submit Support Ticket",
    'submit' => "Submit",
    'subsccription_renewals' => "Subscription Renewals",
    'subscription_complete_your_payment' => "Activate your subscription by completing your payment",
    'subscription_currency_mismatch' => "Subscription currency is not the same as your system currency",
    'subscription_id' => "Subscription ID",
    'subscription_log_cancelled_client' => "Subscription was cancelled by the client",
    'subscription_log_created' => "Subscription was created",
    'subscription_log_modified_price' => "Subscription plan was changed",
    'subscription_log_no_webhook_received' => "The renewal date has passed but no information was received from Stripe. The subscription will now be set to paused status",
    'subscription_log_payment_failed' => "Subscription payment has failed",
    'subscription_log_payment_method_changed_failed' => "Subscription credit card change has failed",
    'subscription_log_price_not_found' => "Subscription plan could not be found in Stripe",
    'subscription_log_renewed' => "Subscription was renewed successfully",
    'subscription_log_stripe_error' => "Subscription error received from Stripe",
    'subscription_name' => "Subscription Name",
    'subscription_not_found' => "Subscription could not be found",
    'subscription_only_available_on_stripe' => "Subscription are only available via the Stripe payment gateway",
    'subscription_payment_failed' => "Your subscription payment failed. Please updated your credit card details to try again",
    'subscription_payment' => "Subscription Payment",
    'subscription_plan' => "Subscription Plan",
    'subscription_plans' => "Subscription Plans",
    'subscription_prefix' => "Subscription Prefix",
    'subscription_price' => "Subscription was created",
    'subscription_product' => "Subscription was created",
    'subscription_renewal_cycle' => "Subscription renewal cycle",
    'subscription' => "Subscription",
    'subscriptions_will_not_automatically_be_deleted' => "Existing subscriptions on this package will automatically not be deleted.",
    'subscriptions' => "Subscriptions",
    'subtotal' => "Subtotal",
    'success' => "Success",
    'summary_tax' => "Summary Tax",
    'summary' => "Summary",
    'support_ticket' => "Support Ticket",
    'support_tickets' => "Support Tickets",
    'support' => "Support",
    'suspended' => "suspended",
    'suspended' => "Suspended",
    'system_bot_name' => "System",
    'system_default_category_cannot_be_deleted' => "This is a system default category and cannot be deleted",
    'system_default' => "System Default",
    'system_email_address' => "System Email Address",
    'system_from_name' => "System From name",
    'system_notifications' => "System Notifications",
    'system' => "System",
    'table_defaults' => "Table Defaults",
    'table_pagination_limits' => "Table Lists - Pagination Limits",
    'table_pagination_limits' => "Table Pagination Limits",
    'tag_already_exists' => "This tag already exists for this resource type",
    'tag_title' => "Tag Title",
    'tag' => "Tag",
    'tags_available_to_all_users' => "Tags created here will be available to all users",
    'tags_no_html' => "Tags should not contain any HTML",
    'tags_settings' => "Tags Settings",
    'tags' => "Tags",
    'target_date_must_be_after_date_added' => "Target date must be after added date",
    'target_date' => "Target Date",
    'task_based_progress' => "Tasks Based Progress",
    'task_collaboration_info' => "Assigned users will be able to view and participate (comment, attach files, etc) in other users tasks",
    'task_collaboration' => "Task Collaboration",
    'task_id' => "Task ID",
    'task_not_found' => "Task could not be found",
    'task_participation' => "Tasks Participation",
    'task_priority' => "Task Priority",
    'task_settings' => "Task Settings",
    'task' => "Task",
    'tasks_collaboration' => "Tasks Colloboration",
    'tasks' => "Tasks",
    'tax_amount' => "Tax Amount",
    'tax_is_invalid' => "Tax is invalid",
    'tax_rate_greater_than_zero' => "Tax rate must be greater than 0",
    'tax_rate' => "Taxes Rate",
    'tax_rates' => "Tax Rates",
    'tax' => "Tax",
    'team_emails' => "Team Emails",
    'team_member' => "Team Member",
    'team_members' => "Team Members",
    'team_permissions' => "Team Permissions",
    'team' => "Team",
    'telephone' => "Telephone",
    'template_lang_email_footer' => "Email Footer",
    'template_lang_estimate_accepted' => "Estimate Accepted",
    'template_lang_estimate_declined' => "Estimate Declined",
    'template_lang_estimate_revised' => "Estimate Revised",
    'template_lang_invoice_reminder' => "Invoice Reminder",
    'template_lang_lead_assignment' => "Lead Assignment",
    'template_lang_lead_comment' => "Lead Comment",
    'template_lang_lead_file_upload' => "Lead File Uploaded",
    'template_lang_lead_status_change' => "Lead Status Change",
    'template_lang_new_estimate' => "New Estimate",
    'template_lang_new_invoice' => "New Invoice",
    'template_lang_new_payment' => "New Payment",
    'template_lang_new_project_created' => "New Project Created",
    'template_lang_new_subscription_created' => "New Subscription Created",
    'template_lang_new_ticket_reply' => "New Ticket Reply",
    'template_lang_new_ticket' => "New Ticket",
    'template_lang_new_user_welcome' => "New User Welcome",
    'template_lang_project_assignment' => "Project Assignment",
    'template_lang_project_comment' => "Project Comment",
    'template_lang_project_file_uploaded' => "Project File Uploaded",
    'template_lang_project_status_change' => "Project Status Change",
    'template_lang_reset_password_request' => "Reset Password Request",
    'template_lang_subscription_renewal_failed' => "Subscription Renewal Failed",
    'template_lang_subscription_renewed' => "Subscription Renewed",
    'template_lang_subscription_started' => "Subscription Started",
    'template_lang_system_notification' => "System Notification",
    'template_lang_task_assignment' => "Task Assignment",
    'template_lang_task_comment' => "Task Comment",
    'template_lang_task_file_uploaded' => "Task File Uploaded",
    'template_lang_task_status_change' => "Task Status Change",
    'template_lang_thank_you_payment' => "Thank You For Payment",
    'template_lang_ticket_closed' => "Ticket Closed",
    'template_variables' => "Template Variables",
    'template' => "Template",
    'templates' => "Templates",
    'terms_and_conditions' => "Terms & Conditions",
    'terms_and_conditions' => "Terms and conditions",
    'terms' => "Terms",
    'test_mode' => "Test Mode",
    'testing' => "Testing",
    'text' => "Text",
    'thank_you' => "Thank You",
    'the_estimate_will_be_marked_as_revised' => "The estimate will be marked as (Revised) and an email will be sent to the customer",
    'the_estimate_will_be_sent_to_customer' => "The estimate will be emailed to the customer",
    'the_following_fields_do_not_have_a_name' => "The following are enabled but do not have a name",
    'the_invoice_will_be_sent_to_customer' => "The invoice will be emailed to the customer",
    'theme' => "Theme",
    'this_acction_cannot_be_undone' => "This action cannot be undone",
    'this_feature_is_disabled_in_demo' => "This feature is disabled in demo mode",
    'this_feature_is_unavailable' => "This feature is unavailable",
    'this_lead_is_archived' => "This lead is archived",
    'this_month' => "This Month",
    'this_project_is_archived' => "This project is archived",
    'this_task_is_archived' => "This task is archived",
    'this_week' => "This Week",
    'this_year' => "This Year",
    'thousands_separator' => "Thousands Separator",
    'ticket_canned_reply_template' => "Ticket Canned Reply Templates",
    'ticket_departments' => "Ticket Departments",
    'ticket_details' => "Ticket Details",
    'ticket_id' => "Ticket ID",
    'ticket_is_on_hold' => "This support ticket is currently on hold and you cannot post a new reply to it",
    'ticket_not_found' => "Ticket could not be found",
    'ticket_options' => "Ticket Options",
    'ticket_reply' => "Support Ticket Reply",
    'ticket_settings' => "Ticket Settings",
    'ticket' => "Ticket",
    'tickets' => "Tickets",
    'time_billing' => "Time Billing",
    'time_invoiced' => "Time Invoiced",
    'time_sheets' => "Time Sheets",
    'time_spent' => "Time Spent",
    'time' => "Time",
    'timeline' => "Timeline",
    'timer' => "Timer",
    'timer' => "Timers",
    'timers' => "Timers",
    'timesheets' => "Time Sheets",
    'timezone' => "Time Zone",
    'title_is_required' => "Title is required",
    'title' => "Title",
    'to_do_list' => "TO DO List",
    'to_do' => "To Do",
    'to' => "To",
    'today' => "Today",
    'total_expenses' => "Total Expenses",
    'total_income' => "Total Income",
    'total_loss' => "Total Loss",
    'total_profit' => "Total Profit",
    'total_time' => "Total Time",
    'total_value' => "Total Value",
    'total' => "Total",
    'transaction_id' => "Transaction ID",
    'trash' => "Trash",
    'try_a_differet_search' => "Try a difference search",
    'try_again_later' => "Try again later",
    'type' => "Type",
    'uncategorised' => "Uncategorised",
    'uninstall' => "Uninstall",
    'unit_already_exists' => "Unit already exists",
    'unit_is_invalid' => "Unit is invalid",
    'unit' => "bar",
    'unit' => "Unit",
    'units_examples' => "Example (Hr, Each, Item)",
    'units_no_html' => "Units should not contain any HTML",
    'units' => "Units",
    'unknown' => "Unknown",
    'update_avatar' => "Update Avatar",
    'update_credit_card' => "Update Credit Card",
    'update_my_profile' => "Update My Profile",
    'update_password' => "Update Password",
    'update_your_company_logo' => "Update Your Company Logo",
    'update_your_profile_picture' => "Update your profile picture",
    'update' => "Update",
    'updates_installation_instructions' => "Updates installation instructions",
    'updates' => "Updates",
    'upload_canceled' => "Upload cancelled",
    'upload_failed' => 'upload failed',
    'upload_new_background' => "Upload New Background",
    'uploaded_by' => "Uploaded By",
    'urgent' => "Urgent",
    'url_expired_or_invalid' => "Url has expired or is invalid",
    'use_the_button_to_upload' => "Use the button to upload",
    'user_already_exists' => "A user with this email address already exists",
    'user_id' => "User ID",
    'user_is_online' => "User is online",
    'user_not_found' => "User could not be found",
    'user_role_not_found' => "The user role could not be found",
    'user_role' => "User Role",
    'user_roles' => "User Roles",
    'user_type' => "User Type",
    'user' => "User",
    'username' => "Username",
    'users' => "Users",
    'value' => "Value",
    'values_do_no_match' => 'values do not match',
    'vat_tax_number' => "VAT/TAX Number",
    'vault' => "Vault",
    'version' => "Version",
    'view_child_invoices' => "View Child Invoices",
    'view_details' => "View Details",
    'view_expenses' => "View Expenses",
    'view_invoice' => "View Invoice",
    'view_parent_invoice' => "View parent invoice",
    'view_payments' => "View Payments",
    'view_subscription' => "View Subscription",
    'view_tags' => "View Tags",
    'view_tasks' => "View Tasks",
    'view_time_sheets' => "View Time Sheets",
    'view' => "View",
    'viewing_permissions_info' => "If these items are selected, then (viewing permission) will be enabled automatically",
    'visible_to_client' => "Visible To Client",
    'visible_to' => "Visible To",
    'visible' => "Visible",
    'warning' => "Warning",
    'webhooks_signing_key' => "Signing Key",
    'webhooks_url' => "Webhooks Url",
    'website' => "Website",
    'week_weeks' => "Week(s)",
    'week' => "Week",
    'weekly' => "Weekly",
    'weeks' => "Weeks",
    'welcome_to_dashboard' => "Welcome to your new project dashboard",
    'welcome' => "Welcome",
    'year_years' => "Year(s)",
    'year' => "Year",
    'yearly' => "Yearly",
    'years' => "Years",
    'yes' => "Yes",
    'yesterday' => "Yesterday",
    'you_are_now_logged_in' => "You are now logged in",
    'you_can_import_from_csv_file' => "You can import from a CSV file. For more details, see documentation",
    'you_can_set_tax_on_each_line' => "You can set the tax on each invoice product",
    'you_cannot_delete_account_owner' => "You cannot delete the account owner",
    'you_cannot_delete_system_default_item' => "You cannot delete a system default resource",
    'you_created_this_contract' => "You created this contract",
    'you_created_this_estimate' => "You created this estimate",
    'you_created_this_invoice' => "You created this invoice",
    'you_created_this_lead' => "You created this lead",
    'you_created_this_project' => "You created this project",
    'you_created_this_proposal' => "You created this proposal",
    'you_created_this_task' => "You created this task",
    'you_have_invoices_awaiting_payment' => "You have invoices that are awaiting payment",
    'you_must_first_setup_stripe_account' => "First setup up your stripe acccount.",
    'you_need_to_activate_stripe' => "You need to activate the Stripe payment gateway",
    'your_payment_is_now_processing' => "Your payment is being processed",
    'your_session_has_expired_login' => "Your session has expired. Please login again.",
    'your_version' => "Your App Version",
    'your_version' => "Your Version",
    'zipcode' => "Zip Code",
    "update_settings" => "Update Settings",

    // Checklist Import Language Additions
    'import_checklist_items' => "Import checklist items",
    'import_checklist_instructions' => "Upload a CSV, Excel, or text file containing checklist items. Each line or row should contain one checklist item.",
    'drag_drop_checklist_file' => "Drop checklist file here or click to upload",
    'checklist_import_successful' => "Checklist items imported successfully",
    'checklist_import_failed' => "Failed to import checklist items",
    'invalid_checklist_file' => "Invalid file format. Please upload a CSV, Excel, or text file",
    'checklist_file_empty' => "The uploaded file is empty or contains no valid checklist items",
    'checklist_items_processed' => "checklist items processed",
    'max_checklist_items_exceeded' => "Maximum number of checklist items exceeded. Only the first items were imported",

    //--------------V1.03 Language Changes-------------------
    'add_estimate_splah_title' => "Who is this estimate for?",
    'add_invoice_splah_title' => "Who is this invoice for?",
    'add_project_splah_title' => "Who is this project for?",
    'allow_clients_to_login' => "Allow clients to login",
    'click_the_image_above_to_download' => "Click the images above, to download sample files",
    'click_to_view_error_log' => "Click here to view the error log",
    'client_has_not_opened' => "client has not opened",
    'client_has_opened' => "client has opened",
    'clients_disabled_login_error' => "Access denied. Please contact customer support for assistance",
    'column_name' => "Column Name",
    'column_name' => "Column Name",
    'copy_estimate_notes' => "Copy estimate notes",
    'copy_estimate_terms' => "Copy estimate terms",
    'create_new_client' => "Create New Client",
    'create_new_lead' => "Create New Lead",
    'csv_sample' => "CSV Sample",
    'delete_original_estimate' => "Delete the original estimate",
    'disable_all_client_emails' => "Disable all client emails",
    'disable_client_emails_notes' => "The system will not send any emails to client users",
    'drag_drop_single_file' => "Drop a single file or click to upload",
    'edit_timesheet' => "Edit Timesheet",
    'enter_new_time' => "Enter the new time for this record",
    'error_log' => "Error Log",
    'error_message' => "Error Message",
    'existing_client' => "Existing Client",
    'existing_lead' => "Existing Lead",
    'failed_reason' => "Failed Reason",
    'import_clients' => "Import Clients",
    'import_expenses' => "Import Expenses",
    'import_projects' => "Import Projects",
    'import_tasks' => "Import Tasks",
    'import_tasks' => "Import Tasks",
    'importing_completed' => "Importing Completed",
    'importing_failed' => "Importing Failed",
    'invoice_payment' => "Invoice Payment",
    'live_api_key' => "Live API Key",
    'manual_entry' => "Manual Entry",
    'mollie_general_info' => "Get this value from inside your Mollie dashboard",
    'new_client' => "New Client",
    'new_lead' => "New lead",
    'no_data_rows_were_found' => "No Valid Rows Founds",
    'no_records_imported' => "No Records Were Imported",
    'no_tasks_assigned_to_you' => "There are no tasks assigned to you on the selected project",
    'no_tasks_found' => "No tasks found",
    'record_time' => "Record Time",
    'record_time' => "Record Time",
    'record_your_work_time' => "Record your work time",
    'records_failed' => "Records Failed",
    'records_imported' => "Records Imported",
    'row' => "Row",
    'send_welcome_email' => "Send Welcome Email",
    'server_requirements_to_use_feature' => "The following server requirements are needed to use this feature",
    'session_timeout_popup' => "Show session timeout popup",
    'set_to_not_opened' => "Change - Not Opened",
    'set_to_opened' => "Change - Opened",
    'show_if_client_has_opened' => "Show if it has been viewed by the client",
    'start_timer' => "Start Timer",
    'task_could_not_be_found' => "Task could not be found",
    'test_api_key' => "Test API Key",
    'the_following_records_could_not_be_imported' => "The following records could not be imported",
    'total_time_must_be_greater_than_1_minute' => "The new time must be greater than 1 minute",
    'what_are_you_working_on' => "What are you working on",
    'xls_sample' => "XLS Sample",
    'xlsx_sample' => "XLSX Sample",
    'you_are_now_not_assigned_to_this_lead' => "You are not assigned to this lead",
    'you_are_now_not_assigned_to_this_project' => "You are not assigned to this project",
    'you_are_now_not_assigned_to_this_task' => "You are not assigned to this task",
    'you_can_download_sample_files' => "You can download sample importing files below",
    'you_can_upload_csv_or_xls' => "You can upload CVS or XLXS files",

    //--------------V1.04 Language Changes-------------------
    'active_timer' => "Active Timer",
    'additional_company_info' => "Additional company information to diplay on invoices and estimates",
    'adjustments' => "Adjustments",
    'copy_task_checklist' => "Copy Task Checklist",
    'copy_task_file' => "Copy Task Files",
    'copy_these_items' => "Copy These Items",
    'create_a_new_project' => "Create a new projecct",
    'create_a_project_template' => "Create A Project Template",
    'create_from_a_template' => "Create from a template",
    'delete_template' => "Delete Template",
    'description_and_details' => "Description & Details",
    'discount_type' => "Discount Type",
    'edit_project_template' => "Edit Project Template",
    'edit_template' => "Edit Template",
    'is_required' => "is required",
    'project_templates' => "Project Templates",
    'project_teplate' => "Project Template",
    'remove_adjustment' => "Remove Adjustment",
    'select_a_template' => "Select A Template",
    'show_template_after_its_created' => "Show template after it has been created",
    'templates' => "Templates",
    'this_is_total_logged_time_task' => "This is the total time you have loggged for this task. For individual time record, see your timesheets",
    'uploaded_by_team_members' => "upload by team members",

    //--------------V1.05 Language Changes-------------------
    'access_top_menu' => "To access the settings menu, use the toggle button on the top toolbar",
    'add_a_reminder' => "Add A Reminder",
    'allow_users_to_change_theme' => "Allow users to change the theme",
    'app_modules' => "App Modules",
    'checked_custom_fields' => "Selected",
    'client_reminder' => "Client Reminder",
    'contract_reminder' => "Contract reminder",
    'create_custom_fields' => "Create Custom Fields",
    'create_notes' => "Create Notes",
    'delete_all' => "Delete All",
    'delete_log' => "Delete Log",
    'edit_tags' => "Edit Tags",
    'enabled_modules' => "Enabled Modules",
    'estimate_reminder' => "Estimate Reminder",
    'field_checkbox' => "Checkbox",
    'field_date' => "Date",
    'field_decimal' => "Decimal",
    'field_dropdown' => "Dropdown",
    'field_number' => "Number",
    'field_paragraph' => "Paragraph",
    'field_simple_text' => "Simple Text",
    'form_field_name' => "Form Field Name",
    'info_standard_form_info_1' => "The standard form is the form that you see inside the app when creating a new resource.",
    'lead_log' => "Lead Log",
    'lead_reminder' => "Lead Reminder",
    'lead_reminder' => "Lead Reminder",
    'linked_to_projects' => "Requires the projects module",
    'linked_to_tasks' => "Requires the tasks module",
    'list_options' => "List Options",
    'log' => "Log",
    'log_type' => "Log Type",
    'logs' => "Logs",
    'module' => "Module",
    'modules' => "Modules",
    'my_notes' => "My Notes",
    'my_reminders' => "My reminders",
    'new_log' => "New Log",
    'only_system_enabled_modules_enabled' => "Only system enabled modules are displayed",
    'phone_call' => "Phone Call",
    'project_reminder' => "Project Reminder",
    'record_new_log' => "Record New Log",
    'reminder' => "Reminder",
    'reminder_cannot_be_past' => "Reminder must be in the future",
    'reminder_notes' => "Reminder Notes",
    'reminder_title' => "Reminder Title",
    'reminders' => "Reminders",
    'reminders_due' => "Reminder - Due",
    'remove' => "Remove",
    'see_notes' => "see notes",
    'see_settings' => "See Settings",
    'selected' => "Selected",
    'send_customer_payment_email' => "Send the client a payment received email",
    'show_form_fields_in_collapsed_toggle' => "Show these form fields in a collapsed toggle",
    'show_in_filter_panel' => "Show in filter panel",
    'standard_form' => "Standard Form",
    'subscription_reminder' => "Subscription Reminder",
    'task_reminder' => "Task Reminder",
    'template_lang_reminder' => "Reminder",
    'template_lang_task_overdue' => "Task Overdue",
    'the_field_is_requred' => "Required - mandatory",
    'the_requested_service_not_found' => "The requested service could not be found",
    'ticket_reminder' => "Ticket Reminder",
    'time_tracking' => "Time Tracking",
    'type_list_options' => "Type your list options. Seperate with a comma,",
    'use_custom_settings' => "Use Custom Settings",
    'use_in_standard_form' => "Use in the standard form",
    'use_system_settings' => "Use System Settings",
    'you_do_not_have_a_reminder_for_item' => "You do not have a reminder for this item",
    'you_do_not_have_custom_fields' => "You do not have any custom fields",
    'you_do_not_have_logs' => "You do not have any logs",
    'you_do_not_have_notes' => "You do not have any notes",

    //--------------V1.06 Language Changes-------------------
    'add_new_task_status' => "Add New Task Status",
    'clone_lead' => "Clone Lead",
    'clone_task' => "Clone Task",
    'copy_checklists' => "Copy Checklists",
    'copy_files' => "Copy Files",
    'delete_task_status' => "Delete Task Status",
    'direct_form_link' => "Direct Form Link",
    'email_address_warning' => "Do not use an email address that is a different domain name from the CRM. It will end up in spam folder.",
    'email_client' => "Email Client",
    'embed_code' => "Embed Code",
    'embed_code' => "Embed Code",
    'embed_code_instructions' => "Paste the embed code in the html body of the page that you would like to form to show on",
    'embed_code_instructions_2' => "If you website is not (https://), you must change the url in the embed code to just (http://)",
    'embed_code_instructions_3' => "If your form is showing with a scroll bar, you can change the (height) value that is set in the embed code",
    'embed_form' => "Embed Form",
    'error_processing_embed_code' => "Error processing Youtube embed code",
    'form_builder' => "Form Builder",
    'form_name' => "Form Name",
    'form_settings' => "Form Settings",
    'instructions' => "Instructions",
    'instructions_webform_code' => "Copy the code above and paste it on a page in your website.",
    'integration_code' => "Integration Code",
    'invalid_permissions_for_project' => "You do not have permissions for this project",
    'invalid_youtube_embed_code' => "Invalid Youtube embed code",
    'lead_first_last_name_required' => "Lead first name and last name are required",
    'moving_kb_categories_warning' => "You can only move atricles to categories of the same type (i.e. text or video)",
    'new_template' => "New Template",
    'new_web_form' => "New Web Form",
    'read' => "Read",
    'save_form' => "Save Form",
    'send_admin_email_notification' => "Admin - Send an email notification",
    'send_assigned_email_notification' => "Assigned - Send an email notification",
    'standard_text' => "Standard Text",
    'statuses' => "Statuses",
    'submit_button_text' => "Submit Button Text",
    'task_status_not_empty' => "This status is not empty. First move its tasks to another status",
    'template_lang_new_lead_form' => "New Lead Form Submitted",
    'template_name' => "Template Name",
    'text' => "Text",
    'thank_you_form_submitted' => "Thank you. Your form has been submitted",
    'thank_you_message' => "Thank You Message",
    'times_submitted' => "Times Submitted",
    'unread' => "Unread",
    'use_a_template' => "Use A Template",
    'video' => "Video",
    'video_article_notes' => "This article is in a [video] category. Add the Youtube embed code in the box above.",
    'web_form' => "Web Form",
    'web_form_exists' => "Web form already exists",
    'web_forms' => "Web Forms",
    'webform_embed_code' => "Webform Embed Code",
    'webmail' => "Webmail",
    'youtube_embed_code' => "Youtube Embed Code",

    //--------------V1.07 Language Changes-------------------
    'update_progress' => "Update Progress",
    'update_progress_info' => "If you do not set the progress manually, it will be set automatically, based on the number of completed tasks",

    //--------------V1.08 Language Changes-------------------

    'a_required_function_is_disabled_on_server' => "A required PHP function is currently disabled on your server",
    'ask_webhost_to_enable_smtp_ports' => "Please contact your web hosting provider to allow connection to external SMTP ports",
    'automatically_assign' => "Automatically Assign",
    'card_view' => "Card View",
    'category_and_users' => "Category & Users",
    'category_based' => "Category Team Based",
    'category_team_info' => "If you are using category based permissions, the users selected here will have access to all the projects that are in this category.",
    'category_users' => "Category Team Members",
    'change_cover_image' => "Change Cover Image",
    'change_theme' => "Change Theme",
    'changing_project_permissions_warning' => "Changing the (Project Team Permissions) setting will cause all projects to be reassigned according to the new setting. As such, this setting should not be changed frequently.",
    'delete_all_emails' => "Delete All Emails",
    'edit_team_members' => "Edit Team Members",
    'email_delivery_problem' => "Email delivery problem?",
    'email_log' => "Email Log",
    'email_log' => "Email Log",
    'email_queue' => "Email Queue",
    'error_logs' => "Error Logs",
    'everything_looks_ok' => "Everything looks ok",
    'first_task_date' => "Next Recurring Date",
    'first_task_date_cannot_be_in_past' => "Next recurring date cannot be in the past",
    'image_is_too_big' => "Image is too big",
    'image_is_too_small' => "Image is too small",
    'image_size_wrong_cover_image' => "Image is not the correct size. It must be a minimum of",
    'infinite' => "Infinite",
    'info' => "Info",
    'invalid_file_name' => "Invalid file name",
    'jpg_png_only' => "jpg and png only",
    'make_recurring' => "Make Recurring",
    'milestone_view' => "Milestone View",
    'processing' => "Processing",
    'project_cover_images_feature' => "Project Cover Images",
    'projects_assigned_auto' => "Users are assigned automatically from the project's category users",
    'projects_user_permission' => "Project Team Permissions",
    'queue_all_email_again' => "Queue All Email Again",
    'recommended_image_size' => "Recommended Image Size",
    'recurring_task' => "Recurring Task",
    'role_based' => "User Role Based",
    'run_test_now' => "Run Test Now",
    'show_project_categories_main_menu' => "Show Project Categories In Main Menu",
    'smtp_error' => "SMTP Error",
    'smtp_not_enabled' => "You have not enabled SMTP in email general settings",
    'smtp_ports_closed' => "SMTP ports are closed",
    'smtp_test_passed' => "SMTP ports are open and you should be able to send email using SMTP",
    'task_is_not_recurring' => "Task in not recurring",
    'task_recurring_cycles_explanation' => "This is the date when the next recurring task will be created. Once set, the future dates will be automatically calculated.",
    'task_recurring_period_info' => "The task will stop recurring after these number of cycles. For infinity, set the value to 0",
    'template_lang_email_signature' => "Email Signature",
    'this_test_can_take_some_time' => "This test can take up to 5 minutes to finish",
    'use_tool_to_debug_smtp' => "Use this tool to debug SMTP email delivery problems",
    'view_layout' => "View Layout",
    'you_can_change_in_settings' => "You can change this in your CRM settings",
    'you_can_delete_these_files' => "You can safely delete these files at anytime. The system will generate new ones as needed",

    //--------------V1.09 Language Changes-------------------
    'accept_proposal' => "Accept Proposal",
    'accepted_value' => "Accepted Value",
    'add_contract' => "Create A New Contract",
    'add_proposal' => "Create A New Proposal",
    'assign_client' => "Assign Client",
    'assign_client_info' => "Use this option to assign this task to the client",
    'change_header_image' => "Change Header Image (optional)",
    'client_or_lead_required' => "A client or a lead is required",
    'confirm_accept_contract' => "Please confirm that you want to accept this Contract",
    'confirm_accept_proposal' => "Please confirm that you want to accept this proposal",
    'confirm_decline_contract' => "Please confirm that you want to decline this Contract",
    'confirm_decline_proposal' => "Please confirm that you want to decline this proposal",
    'content' => "Content",
    'contract_end_date' => "End Date",
    'contract_id' => "Contract ID",
    'contract_not_found' => "Contract not found",
    'contract_start_date' => "Start Date",
    'contract_target' => "Contract Target",
    'contract_title' => "Contract Title",
    'contract_url' => "Contract URL",
    'cover' => "Cover",
    'decline_proposal' => "Decline Proposal",
    'default_heading_color' => "Default Heading Color",
    'default_template' => "Default Template",
    'documents_billing_info' => "To add this billing inside your document, simple paste the code below in the document",
    'documeny_publish_confirm' => "The document will be emailed to the client",
    'draw_your_signature' => "Your Signature",
    'edit_billing' => "Pricing",
    'edit_content' => "Edit Content",
    'edit_cover' => "Edit Cover",
    'edit_details' => "Edit Details",
    'edit_financial_details' => "Edit Financial Details",
    'edit_main_header' => "Edit Main Header",
    'event_accepted_contract' => "Accepted a contract",
    'event_accepted_proposal' => "Accepted a proposal",
    'event_created_contract' => "Created a new contract",
    'event_created_proposal' => "Created a new proposal",
    'event_declined_contract' => "Declined a contract",
    'event_declined_proposal' => "Declined a proposal",
    'exit_editing_mode' => "Exit Editing Mode",
    'financial' => "Financial",
    'header_image' => "Header Image",
    'incorrect_user_for_action' => "You are not signed in as a correct user, for this action",
    'main_heading' => "Main Heading",
    'main_heading_color' => "Main Heading Color",
    'mark_as_accepted' => "Mark As Accepted",
    'mark_as_declined' => "Mark As Declined",
    'mark_as_revised' => "Mark As Revised",
    'next_id_number_contract' => "Next Contract ID",
    'next_id_number_estimate' => "Next Estimate ID",
    'next_id_number_info' => "This is the number of the next record. To change it, the number provided, must be higher than any existing records in the database",
    'next_id_number_invoice' => "Next Invoice ID",
    'next_id_number_proposal' => "Next Proposal ID",
    'none_blank' => "None (blank)",
    'open_ended' => "Open Ended",
    'prepared_by' => "Prepared By",
    'print_contract' => "Print Contract",
    'print_proposal' => "Print Proposal",
    'proposal' => "Proposal",
    'proposal_date' => "Proposal Date",
    'proposal_id' => "Proposal ID",
    'proposal_not_found' => "Proposal not found",
    'proposal_target' => "Proposal Target",
    'proposal_title' => "Proposal Title",
    'proposal_url' => "Proposal URL",
    'proposal_valid_to_date_error' => "Valid to date cannot be before the proposal date",
    'proposals' => "Proposals",
    'proposed_to' => "Proposed To",
    'publish_document' => "Publish Document",
    'service_provider' => "Service Provider",
    'show_contract_after_its_created' => "Show contract after its been created",
    'show_proposal_after_its_created' => "Show proposal after its been created",
    'target' => "Target",
    'template_lang_contract_accepted' => "Contract Accepted",
    'template_lang_contract_declined' => "Contract Decline",
    'template_lang_contract_revised' => "Contract Revised",
    'template_lang_new_contract' => "New Contract",
    'template_lang_new_proposal' => "New Proposal",
    'template_lang_proposal_accepted' => "Proposal Accepted",
    'template_lang_proposal_declined' => "Proposal Decline",
    'template_lang_proposal_revised' => "Proposal Revised",
    'title_color' => "Title Color",
    'upload_signature' => "Upload Signature",
    'valid_until' => "Valid Until",
    'variables' => "Variables",
    'variables_instruction' => "You can use these variables, anywhere in the document",

    //--------------V1.11 Language Changes-------------------
    'add_a_dependency' => "Add A Dependency",
    'admin' => "Admin",
    'all_task_users' => "All Task Users",
    'apply_default_automation_estimates' => "Apply default automation settings. These can be changed, once the estimate has been created",
    'apply_default_automation_project' => "Apply default automation settings. These can be changed, once the project has been created",
    'automation' => "Automation",
    'automation' => "Automation",
    'automation_assign_project' => "Automatically assign the project",
    'automation_create_invoice' => "Create a new invoice",
    'automation_create_project' => "Create a new project",
    'automation_create_project_status' => "Project status",
    'automation_create_tasks_from_line_item' => "Create a task for each line item",
    'automation_create_tasks_from_line_item' => "Create a task for each line item",
    'automation_email_client' => "Email to the client",
    'automation_email_client_invoice_info' => "Send a 'New Invoice Created' email",
    'automation_email_client_project_info' => "Send a 'New Project Created' email",
    'automation_email_invoices_to_client' => "Email invoices to the client",
    'automation_generate_invoice' => "Automatically Generate Invoice(s)",
    'automation_generate_invoice_from_estimates' => "Convert all estimates to invoices",
    'automation_generate_invoice_info' => "Invoices will be generated from existing estimates for this project",
    'automation_invoice_due_date' => "Invoice due date (days)",
    'automation_invoice_project' => "Automatically create invoices for the project",
    'automation_invoice_unbilled_hours' => "Invoice all unbilled hours",
    'automation_option' => "Automation Option",
    'automation_settings' => "Automation Settings",
    'blocking_task' => "Blocking Task",
    'default_tax' => "Default Tax",
    'dependecies_have_been_fulfilled' => "Task dependencies have been fulfilled",
    'dependencies' => "Dependencies",
    'dependency_has_been_fulfilled' => "Dependency has been fulfilled",
    'dependency_prevents_task_from_completing' => "Prevents this task from being completed",
    'dependency_prevents_task_from_starting' => "Prevents this task from being started",
    'dependency_type' => "Dependency Type",
    'dependency_type_cannot_complete' => "Cannot be completed",
    'dependency_type_cannot_start' => "Cannot be started (locked)",
    'dimensions' => "Dimensions",
    'estimate_automation' => "Estimate Automation",
    'estimate_automation_default' => "Estimate Automation (default status)",
    'estimate_automation_default_info' => "If enabled, this automation will be available when creating a new estimate",
    'estimate_automation_info_1' => "When an estimate is accepted, do the following",
    'estimate_automation_info_2' => "These are the default settings, but you can still change them on an estimate by estimate basis",
    'hourly_billing_settings' => "Hourly Billing Settings",
    'invoice_creation_options' => "Invoice Creation Options",
    'invoice_creation_settings' => "Invoice Settings",
    'manage_task_dependencies' => "Manage Task Dependencies",
    'no_automation_options_selected' => "No automation options have been selected",
    'project_automation' => "Project Automation",
    'project_automation_default' => "Project Automation (default status)",
    'project_automation_default_info' => "If enabled, this automation will be available when creating a new project",
    'project_automation_info_1' => "When a project is marked as 'completed', do the following",
    'project_automation_info_3' => "These are the default settings, but you can still change them on a project by project basis",
    'remove_dependency' => "Remove Dependency",
    'select_atleast_one_invoice_creation_option' => "Selected at least (one) invoice creation option",
    'task_blocking_info_1' => "This is the restriction that will be applied to this task. Either the task can not be started, or it cannot be marked as competed",
    'task_blocking_info_2' => "This is the task which, until it has been marked as complete, will cause a restriction on this task",
    'task_dependency_already_exists' => "This task dependancy already exists",
    'task_dependency_info_cannot_be_completed' => "This task is locked (cannot be marked as completed)",
    'task_dependency_info_cannot_be_started' => "This task is locked (cannot be started)",
    'tasks_dependencies' => "Task Dependencies",
    'folders' => "Folders",
    'folder' => "Folder",
    'create_a_folder' => "Add New Folder",
    'enable_file_folders' => "Enable File Folders",
    'edit_folder' => "Edit Folder",
    'edit_folders=' => "Edit Folders",
    'delete_all_files_in_folder' => "Delete all files in this folder",
    'file_folders' => "File Folders",
    'users_that_can_manage_folders' => "Users that can manage file folders",
    'default_folders' => "Default Folders",
    'folder_name' => "Folder Name",
    'system_default_folder_cannot_be_deleted' => "This is the default folder and cannot be deleted",
    'edit_folders' => "Edit Folders",
    'delete_folder' => "Delete Folder",
    'move_to_this_folder' => "Move To This Folder",
    'move_files' => "Move Files",
    'bulk_file_download' => "Bulk File Download",
    'required_php_extension_mission' => "A required PHP extension is  not enabled on this server",
    'no_files_selected' => "No files selected",
    'show_on_ticket' => "Show on ticket",
    'export_ticket' => "Export Tickets",
    'select_table_columns' => "Select table columbs",
    'client_company_name' => "Client Company Name",
    'client_email' => "Client Email",
    'standard_fields' => "Standard Fields",
    'not_checked_custom_fields' => "Not Selected",
    'created_by_name' => "Creator Name",
    'created_by_email' => "Creator Email",
    'export_clients' => "Export Clients",
    'standard_fields' => "Standard Fields",
    'not_checked_custom_fields' => "Not Selected",
    'select_table_columns' => "Select table columbs",
    'contact_name' => "Contact Name",
    'contact_email' => "Contact Email",
    'content' => "Content",
    'import_content' => "Import Content",
    'export_content' => "Export Content",
    'importing_content' => "Importing Content",
    'exporting_content' => "Exporting Content",
    'strip_html' => "Strip HTML",
    'spaces' => "Spaces",
    'affiliates' => "Affiliates",
    'earnings' => "Earnings",
    'earned' => "Earned",
    'pending_clearance' => "Pending Clearance",
    'commission' => "Commission",
    'commissions' => "Commissions",
    'you_earned' => "You earned", //context 'You earned $10 from project ABC with invoice ID: #100'
    'from_project' => "from project", //context - see above
    'with_invoice_id' => "with invoice ID", //context - see above
    'profit' => "Profit",
    'add_affiliate_project' => "Add Affiliate Project",
    'edit_affiliate_project' => "Edit Affiliate Project",
    'edit_affiliate_user' => "Add Affiliate User",
    'affiliate' => "Affiliate",
    'project_automation_info_2' => "This applies to projects that are manually marked as completed",
    'skip_estimates_with_draft_status' => "Skip draft estimates",
    'skip_estimates_with_declined_status' => "Skip declined estimates",
    'this_task' => "This task",
    'roles' => "Roles",
    'home_page' => "Home Page",
    'edit_home_page_setting' => "Edit home page settings",
    'could_not_be_found' => "Could not be found",
    'blocking_task_is_already_completed' => "Blocking task is aleady in completed status",

    //--------------V1.12 Language Changes-------------------

    'tax_type' => "Tax Type",
    'changing_tax_type_will_reset_taxes' => "Changing the tax type will reset current taxes",
    'make_sure_you_have_saved_changes' => "Make sure you have saved any changes before you proceed",
    'tax_rate_deleting_warning' => "This tax rate is used on existing documents.You can delete it but you can disable it",
    'product_tasks' => "Product Tasks",
    'add_file_attachments' => "Add File Attachments",
    'copy_file_attachments' => "Copy file attachments",
    'estimation_notes' => "Estimate Notes",
    'production_notes' => "Production Notes",
    'estimation_notes_information' => "These notes are visible only to your team. They can be used to help staff when creating an estimate",
    'estimation_notes_information' => "These notes are visible only to your team. They can be used to help staff when creating an estimate",
    'set_as_cover_image' => "Set as cover image",
    'remove_cover_image' => "Remove Cover Image",
    'change_tax_type' => "Change Tax Type",
    'this_change_will_refresh_page' => "This change will refresh this page. Make sure you that you have saved any changes to your document.",
    'inline' => "In-line", //content 'in line tax'
    'invalid_tax_type' => "Invalid tax type",
    'copy' => "Copy",
    'copy_file' => "Copy File",
    'copy_to' => "Copy To",
    'copy_from' => "Copy From",
    'product_tasks_info' => "[optional] These are the project tasks that are created for this product (line item), when estimate automation is run",
    'estimate_notes_info' => "These notes will be displayed when editing an estimate or invoice. These notes are not visible to the client",

    //--------------V2.0 Language Changes-------------------
    'online' => "Online",
    'offline' => "Offline",
    'all_team_members' => "All Team Members",
    'delete_message' => "Delete Message",
    'send_files' => "Send Files",
    'chat' => "Chat",
    'team_chat' => "Team Chat",
    'instant_messaging' => "Instant Messaging",
    'messages' => "Messages",
    'message' => "Message",
    'create_new_ticket' => "Create New Ticket",
    'you_can_open_support_ticket' => "You can also open a support ticket",
    'new_status' => "New Status",
    'closed_status' => "Closed Status",
    'edit_ticket_status' => "Edit Ticket Status",
    'delete_ticket_status' => "Delete Ticket Status",
    'ticket_status_not_empty' => "This status is not empty. First move its tickets to another status",
    'move_tasks' => "Move Tasks",
    'move_leads' => "Move Sales Activity",
    'move_tickets' => "Move Tickets",
    'tickets_apply_when_customer_replied' => "Apply this status when a customer has replied to a ticket",
    'tickets_apply_when_staff_replied' => "Apply this status when a team member has replied to a ticket",
    'required_system_status' => "Required System Status",
    'add_new_ticket_status' => "Add New Ticket Status",
    'gateway_general_info' => "Get this value from inside your payment gateway dashboard",
    'language' => "Language",
    'provider' => "Provider", //context 'service provider'

    'my_signature' => "My Signature",
    'sign_the_contract' => "Sign The Contract",
    'delete_signature' => "Delete Signature",
    'unsigned' => "Unsigned",
    'event_signed_contract' => "Signed a contract",
    'date_signed' => "Date Signed",
    'contract_has_already_been_published' => "Contract has already been published",
    'awaiting_signatures' => "Awaiting Signatures",
    'contract_signature_cannot_be_delete' => "Signature can nolonger be delete because the client has already signed the contract",
    'template_lang_contract_signed' => "Contract Signed",
    'contract_end_date_error' => "Contract end date cannot be before the start date",
    'contract_templates' => "Contract Templates",
    'proposal_templates' => "Proposal Templates",
    'create_contract_template' => "Create Contract Template",
    'create_proposal_template' => "Create Proposal Template",
    'task_action_permission_error' => "You current permission levels do not permit this action on this particular task",

    //--------------V2.2 Language Changes-------------------
    'pdf_custom_css' => "Custom CSS for PDF's",
    'pdf_custom_css_info' => "Use this to change the styling of the invoice and estimate pdf files. Example, font size, logo size etc.",
    'site_key' => "Site Key",
    'enable_feature' => "Enable Feature",
    'recaptcha_info' => "To enable reCAPTCH, you will need to signup with Google to get API keys. Make sure you select V2 reCAPTCH and not V3.",
    'recaptcha_validation_error' => "reCAPTCHA validation error",
    'discount_amount' => "Discount Amount",
    'adjustment_description' => "Adjustment Description",
    'adjustment_amount' => "Adjustment Amount",
    'recurring_period' => "Recurring Period",
    'recurring_cycles' => "Recurring Cycles",
    'recurring_duration' => "Recurring Duration",
    'recurring_last' => "Recurring Last",
    'recurring_next' => "Recurring Nest",
    'last_payment_date' => "Last Payment Date",
    'last_payment_amount' => "Last Payment Amount",
    'inline' => "Inline (same page)",
    'popup' => "Popup",
    'replying_tickets' => "Replying tickets",
    'delete_reply' => "Delete Reply",
    'remember_filter' => "Remember Filter",
    'remember_filter_info' => "Filter will be applied everytime you are on this page",
    'filter_has_been_applied' => "a filter has been applied",
    'filtered_results' => "Filtered Results",
    'clear_filter' => "Clear Filter",
    'reset_users_theme' => "Reset all user to this theme",

    //--------------V2.3 Language Changes-------------------
    'archive_tickets' => "Archive Tickets",
    'restore_tickets' => "Restore Tickets",
    'restore_leads' => "Restore Leads",
    'restore_tasks' => "Restore Tasks",
    'restore_invoices' => "Restore Sales Activity",
    'archive_invoices' => "Archive Invoices",
    'archive_estimates' => "Archive Estimates",
    'restore_invoices' => "Restore Invoices",
    'restore_estimates' => "Restore Estimates",
    'show_archive_tickets' => "Show Archived Tickets",
    'show_archive_invoices' => "Show Archived Invoices",
    'show_archive_estimates' => "Show Archived Estimates",
    'ticket_is_archived' => "Ticket is archived",
    'invoice_is_archived' => "Invoice is archived",
    'estimate_is_archived' => "Estimate is archived",
    'submissions' => "Submissions",
    'leads_assigned_info' => "Users who will automatically be assigned when a new lead is created",
    'productivity' => "Productivity",
    'prospects' => "Prospects",
    'sales' => "Sales",
    'financial' => "Financial",
    'product_revenue' => "Product Revenue",
    'client_invoices' => "Client Invoices",
    'project_invoices' => "Project Invoices",
    'monthly_revenue' => "Monthly Revenue",
    'yearly_revenue' => "Yearly Revenue",
    'project_category_invoices' => "Project Category Invoices",
    'overview' => "Overview",
    'update_report' => "Update Report",
    'per_page' => "Per Page",
    'date_range' => "Date Range",
    'custom_range' => "Custom Range",
    'page_totals' => "Page Totals",
    'project_category' => "Project Category",
    'invoice_category' => "Invoice Category",
    'client_estimates' => "Client Estimates",
    'estimate_category' => "Estimate Category",
    'due_tasks' => "Due Tasks",
    'completed_tasks' => "Completed Tasks",
    'client_projects' => "Client Projects",
    'truncate_long_text' => "Trunctate Long Text",
    'tweak' => "Tweak",
    'project_start_date' => "Project Start Date",
    'records' => "Records",
    'hours_mins' => "Hrs:Mins",
    'table_settings' => "Table Settings",
    'date_converted' => "Date Converted",
    'bulk_assign_info' => "To remove all assigned users, submit an empty form",
    'skip_user_notification' => "Skip user notifications",
    'skip_user_notification_info' => "If selected, notifications (email, app) for this update will not be sent to users",
    'income_vs_expenses' => "Income vs Expenses",
    'export_projects' => "Export Projects",
    'export_tasks' => "Export Tasks",
    'export_leads' => "Export Leads",
    'export_contracts' => "Export Contracts",
    'export_proposals' => "Export Proposals",
    'estimated_cost' => "Estimated Cost",
    'visibility' => "Visibility",
    'cloning_projects' => "Cloning Projects",
    'set_copied_tasks_to_new' => "Set copied tasks to new status",
    'estimate_url' => "Estimate URL",
    'public_key' => "Public Key",
    'paystack_general_info' => "Get this from inside your Paystack dashboard",
    'select_section_above_to_start' => "Select a section above to get started",

    //--------------V2.4 Language Changes-------------------
    'provider_signature' => "Provider Signature",
    'product_category' => "Product Category",
    'no_products_in_category' => "No products in this category",
    'priority' => "Priority",
    'priorities' => "Priorities",
    'default_priority' => "Default Priority",
    'required_system_priority' => "Required - System Priority",
    'priority_name' => "Priority Name",
    'add_new_task_status' => "Add New Priority",
    'priority_already_exists' => "Priority Already Exists",
    'edit_task_priority' => "Edit Task Priority",
    'task_priority_not_empty' => "This status is not empty. First move its tasks to another status",
    'delete_task_priority' => "Delete Task Priority",
    'move_to_this_priority' => "Move to this priority",
    'milestone_color' => "Milestone Color",
    'reset_color_on_project_milestone' => "Update color on existing project milestone",
    'unpaid_invoices' => "Invoices Balance",
    'open_tickets' => "Open Tickets",
    'closed_tickets' => "Closed Tickets",
    'accepted_estimates' => "Accepted Estimates",
    'declined_estimates' => "Declined Estimates",
    'accepted_proposals' => "Accepted Proposals",
    'declined_proposals' => "Declined Proposals",
    'pending_projects' => "Pending Projects",
    'export_invoices' => "Export Invoices",
    'export_estimates' => "Export Estimates",
    'export_payments' => "Export Payments",
    'export_expenses' => "Export Expenses",
    'export_timesheets' => "Export Timesheets",
    'sub_total' => "Sub Total",
    'discount_percentage' => "Discount Percentage",
    'times_recurred' => "Times Recurred",
    'last_recurred' => "Last Recurred",
    'next_recurring' => "Next Recurring",
    'sent_overdue_reminder' => "Sent overDue Reminder",
    'viewed_by_client' => "Viewed By Client",
    'estimate_id' => "Estimate ID",
    'estimate_total' => "Estimate Total",
    'export_timesheets' => "Export Timesheets",
    'change_account_owner' => "Change Account Owner",
    'template_lang_lead_form_submitted' => "New Web Form Submitted",
    'hide_completed_tasks' => "(Show/Hide) Completed Tasks",

    //--------------V2.5 Language Changes-------------------
    'publish_now' => "Publish Now",
    'publish_later' => "Scheduled",
    'it_will_be_sent_now' => "It will be sent to the client now",
    'it_will_be_sent_schedule' => "It will be sent to the client on the scheduled date",
    'schedule_date_is_requried' => "Schedule date is required",
    'schedule_date_cannot_be_past' => "Schedule date should not be in the past",
    'schedule_date' => "Schedule Date",
    'error' => "Error",
    'scheduled_publishing_info' => "This item is scheduled to be automatically published on",
    'scheduled_publishing_failed_info' => "The scheduled publishing of this item failed",
    'schedule' => "Schedule",
    'publish_now' => "Publish Now",
    'recurring_date_cannot_be_before_publishing_date' => "The [Next Invoice Date] must be after the scheduled publishing date of the parent invoice",
    'scheduled_publishing_date_cannot_be_after_recurring_date' => "This is a recurring invoice but the [Scheduled Publishing Date] is after the [Next Recurring Date]",
    'set_cover_image' => "Set Cover Image",
    'remove_cover_image' => "Remove Cover Image",
    'set_cover' => "Set Cover",
    'remove_cover' => "Remove Cover",
    'clone_proposal' => "Clone Proposal",
    'clone_contract' => "Clone Contract",
    'canned_responses' => "Canned Reponses",
    'canned_response' => "Canned Response",
    'create_new_response' => "Create A New Canned Response",
    'response_title' => "Response Title",
    'available_to' => "Available To",
    'all_team_members' => "All Team Members",
    'just_me' => "Just Me",
    'canned' => "Canned",
    'add_canned' => "New Canned Response",
    'date_years_ago' => "years ago",
    'date_year_ago' => "year ago",
    'date_months_ago' => "months ago",
    'date_month_ago' => "month ago",
    'date_days_ago' => "days ago",
    'date_day_ago' => "day ago",
    'date_hours_ago' => "hours ago",
    'date_hour_ago' => "hour ago",
    'date_minutes_ago' => "minutes ago",
    'date_minute_ago' => "minute ago",
    'date_seconds_ago' => "seconds ago",
    'date_second_ago' => "second ago",
    'private' => "Private",
    'public' => "Public",
    'manage_categories' => "Manage Categories",
    'count_canned_responses' => "canned responses",
    'canned_categories' => "Canned Categories",
    'canned_messages' => "Canned Messages",
    'canned_no_recently_found' => "No recently used canned responses found",
    'canned_you_can_search_or_browse' => "You can search or browse for responses",
    'recently_used' => "Recently Used",
    'manage_canned_publid' => "Manage Canned Messages (public)",
    'search_begin_message' => "Find everything in one place",
    'search_begin_submessage' => "Results from projects, clients, billing, tasks, etc",
    'search_try_different' => "You can try a different search",
    'search_no_results' => "No results found",
    'view_all' => "View all",
    'attachments' => "Attachments",
    'matched_tags' => "Matched Tags",

    //--------------V2.6 Language Changes-------------------
    'calendar' => "Calendar",
    'calendar_settings' => "Calendar Settings",
    'all_day_event' => "All day event",
    'end_time' => "End Time",
    'attach_files' => "Attach Files",
    'reminder_email' => "Reminder Email",
    'share' => "Share",
    'myself' => "Myself",
    'whole_team' => "Whole Team",
    'selected_team_members' => "Selected Team Members",
    'share_with' => "Share With",
    'location' => "Location",
    'all_team_members' => "All team members",
    'no_users' => "No users",
    'event' => "Event",
    'event_details' => "Event Details",
    'calendar_delete_project' => "This action will delete the actual project",
    'calendar_delete_task' => "This action will delete the actual task",
    'delete_event' => "Delete Event",
    'cancel_editing' => "Cancel Editing",
    'sharing_team_members' => "Sharing Team Members",
    'are_required' => "are required",
    'calendar_assign_project_info' => "Project team members cannot be assigned in this view",
    'calendar_assign_task_info' => "Task team members cannot be assigned in this view",
    'user_required_in_team_list' => "You must be included in the list of team members",
    'email_notification' => "Email Notification",
    'calendar_reminder' => "Calendar Reminder",
    'start_and_due_date' => "Start & Due Date",
    'projects_display_date' => "Projects - Display Date",
    'tasks_display_date' => "Tasks - Display Date",
    'events_display_date' => "Events - Display Date",
    'foo' => "bar",
    'end_date_due' => "End Date (due date)",
    'apply_changes' => "Apply Changes",
    'only_mine' => "Only mine",
    'display_all' => "Display all",
    'projects_and_taks' => "Projects & Tasks",
    'show_project_title_on_invoice' => "Show project title on invoice",
    'login_brute_force_protection' => "Login Security (brute force protection)",
    'login_brute_force_protection_info' => "A user will be locked out for 1 hour, after 3 failed attempts",
    'proposal_automation' => "Proposal Automation",
    'proposal_automation_default' => "Proposal Automation (default status)",
    'proposal_automation_default_info' => "If enabled, this automation will be available when creating a new proposal",
    'proposal_automation_info_1' => "When an proposal is accepted, do the following",
    'proposal_automation_info_2' => "These are the default settings, but you can still change them on an proposal by proposal basis",
    'all_day' => "All day",
    'more' => "more",
    'no_events_to_display' => "No events to display",
    'sunday' => "Sunday",
    'monday' => "Monday",
    'tuesday' => "Tuesday",
    'wednesday' => "Wednesday",
    'thursday' => "Thursday",
    'friday' => "Friday",
    'saturday' => "Saturday",
    'sunday_short' => "Sun",
    'monday_short' => "Mon",
    'tuesday_short' => "Tue",
    'wednesday_short' => "Wed",
    'thursday_short' => "Thur",
    'friday_short' => "Fri",
    'saturday_short' => "Sat",
    'list' => "List",
    'apply_default_automation_proposals' => "Apply default automation settings. These can be changed, once the proposal has been created",
    'show_task_status_changes_in_event_timeline' => "Show task status changes in events timeline",
    'source_webform' => "Source - Webform",
    'additional_client_details' => "Additional Client Details",
    'calender_not_seeing_items' => "Not see some projects or tasks",
    'calender_not_seeing_info' => "Depending on your calendar display preferences (above), projects and tasks without a due date will not be displayed. You should instead select to view by start date or both start and due date.",
    'end_date_cannot_be_before_start_date' => "End date cannot be before the start date",
    'end_time_cannot_be_before_start_time' => "End time cannot be before the start time",
    'event_can_only_be_all_day' => "This type of event can only be set as all day",

    //--------------V2.7 Language Changes-------------------
    'email_integration' => "Email Integration",
    'email_integration_status' => "Email Integration Status",
    'email_integration_info' => "This feature enables customers to create and reply to support tickets via email",
    'tickets_iamp_default_email_info' => "These are the IMAP email address settings for the default email address from which, the system will fetch customer support ticket enquiries. You can also use separate email address for each support ticket department (via App > Settings > Tickets > Departments)",
    'do_nothing' => "Do nothing",
    'delete_email' => "Delete the email",
    'action_after_fetching_email' => "Action after fetching a new email",
    'host' => "Host",
    'port' => "Port",
    'user_name' => "User name",
    'test_imap_connection' => "Test IMAP Connection",
    'imap_connection_passed' => "Successfully connected to IMAP server",
    'imap_connection_failed' => "IMAP connection failed - See error logs for details",
    'email_sync' => "Email Sync",
    'department_email_integration' => "Department Email Integration",
    'department_email_integration_info' => "Support emails will be fetched from the email address specified here and added to support tickets in this department",
    'default_email' => "Default Email",
    'custom_email' => "Custom Email",
    'integrated_email_address' => "Integrated Email Address",
    'department_email_imap' => "Department Email Address (IMAP)",
    'mark_as_read' => "Mark email as read",
    'email_sending_error' => "This email could not be sent. See error logs for details",
    'imap_fetch_limits' => "IMAP fetching limits",
    'imap_connection_timeout' => "IMAP connection timeout",
    'tweak_settings' => "Tweak Settings",
    'reset_email_processing_queue' => "Reset email processing queue",
    'email_last_checked' => "Email Last Checked",
    'email_last_fetched_count' => "Email Last Fetched",
    'email_fetched_count' => "Email Count",
    'email_last_checked_info' => "The last time the application connected to the IMAP email server",
    'email_last_fetched_count_info' => "The number of emails last fetched from the IMAP email server",
    'email_fetched_count_info' => "The total number of all emails fetched from the IMAP email server",
    'this_feature_is_optional' => "This feature is optional and you can choose to disable it",
    'email_contact' => "Email Contact",
    'client_user' => "Client User",
    'new_lead_status' => "New Lead Status",
    'css_style' => "CSS Style",
    'custom_css_webform' => "You can add your custom CSS code here, to style the web form",
    'custom_css_crm' => "You can add your custom CSS code here. The CSS you add here, will be applied to the CRM. Do not add the <style> tag",
    'recaptcha_complete_settings' => "Ensure that you have completed your reCAPTCHA settings",
    'webform_recaptcha_info' => "This will show a reCATCHA challenge on this form",
    'pin' => "Pin",
    'pinned' => "Pinned",
    'imap_connection_failed_message' => "Unable to connect to the IMAP server. Check the [Host, Port, Encryption] details",
    'imap_connection_failed_general' => "Unable to connect to the IMAP server",
    'see_logs_for_details' => "See error logs for details",
    'email_integration_optional' => "Email integration is optional and not required for support tickets. It lets customers create and reply to tickets via email using different addresses for each department",
    'email_already_linked_to_department' => "Email address is already linked to another ticket department",
    'usually_same_as_email_address' => "Usually the same as your email address.",
    'tickets_imap_warning' => "You must use an email address that is also able to send emails from this CRM. This ensures that replies to support tickets from the CRM are successfully delivered and avoid issues with email deliverability or spam filters.",
    'important_notice' => "Important Notice",
    'important' => "Important",
    'see_notice_below' => "See important notice below",
    'pinning' => "Pinning",
    'parent_invoice' => "Parent Invoice",
    'client_contact' => "Contact",
    'publishing' => "Publishing",
    'scheduled_publishing_date' => "Scheduled Publishing Date",
    'billed' => "Invoiced",
    'unbilled' => "Not Invoiced",
    'no_of_tasks' => "Number Of Tasks",

    //--------------V2.8 Language Changes-------------------
    'hide_decimal_points' => "Hide decimal point",
    'contract_date' => "Contract Date",
    'manage' => "Manage",
    'permission_for_modules_info' => "Permission settings for modules will be available afer the role has been created",
    'pdf_font' => "PDF Font",
    'must_be_greater_than_zero' => "must be greater than 0",
    'must_be_greater_than_one' => "must be greater than 1",
    'delete_service' => "Delete Service",
    'edit_profile' => "Edit Profile",
    'next_expense_date' => "Next Expense Date",
    'expense_recurring_cycles_explanation_1' => "This is this is date when the first recurring expense will be created. All other expenses will continue from this date.",
    'expense_automatically_created_from_recurring' => "This expense was automatically created from a recurring expense",
    'next_expense_date_cannot_be_in_past' => "Next expense date should not be in the past",
    'recurring_cycle' => "Recurring Cycle",
    'recurring_period_info' => "Recurring will stop after these number of cycles. For infinity, set the value to 0",
    'recurring_expense' => "Recurring Expense",
    'clone_expense' => "Clone Expense",
    'clone_recurring_settings' => "Clone Recurring Settings",
    'clone_files' => "Clone Files",
    'edit_task_status' => "Edit Task Status",
    'quick_access' => "Quick Access",
    'number_sold' => "Number Sold",
    'amount_sold' => "Amount Sold",
    'export_products' => "Export Products",
    'billing_status' => "Billing Status",
    'recurring_parent_id' => "Recurring Parent ID",
    'last_recurred' => "Last Recurred",
    'recurred_counter' => "Recurred Counter",
    'add_a_note' => "Add A Note",
    'ticket_notes_info' => "Ticket notes are not visible to clients. They are however visible to all team members",
    'save_ticket_note' => "Save Ticket Note",
    'due_reminders' => "Due Reminders",
    'pending_reminders' => "Pending Reminders",
    'no_reminders_found' => "No reminders found",

    //--------------V2.9 Language Changes-------------------
    'is_already_in_use' => "is already in use",
    'project_status' => "Project Status",
    'onetime' => "Onetime",
    'subscription_status' => "Subscription Status",
    'billing_cycle' => "Billing Cycle",
    'onetime_payment' => "Onetime Payment",
    'recurring_payment' => "Recurring Payment",
    'form_submission' => "Form Submission",
    'quantity' => "Quantity",
    'send_overdue_reminder_info' => "Send the client an overdue invoice reminder (email)",
    'send_overdue_reminder' => "Send Overdue Reminder",
    'overdue_reminders_sent' => "Overdue reminders sent",
    'new_invoices' => "New Invoices",
    'no_activity_in_this_period' => "No activity in this period",
    'high_priority' => "High Priority",
    'low_pirority' => "Low Priority",
    'date_accepted' => "Date Accepted",
    'new_contracts' => "New Contracts",
    'active_contracts' => "Active Contracts",
    'expired_contracts' => "Expired Contracts",
    'new_proposals' => "New Proposals",
    'pending_proposals' => "Pending Proposals",
    'expired_proposals' => "Expired Proposals",
    'date_accepted' => "Date Accepted",
    'date_declined' => "Date Declined",
    'date_expired' => "Date Expired",
    'new_proposals' => "New Proposals",
    'accepted_proposals' => "Accepted Proposals",
    'declined_proposals' => "Declined Proposals",
    'pending_proposals' => "Pending Proposals",
    'pending' => "Pending",
    'total_time_entries' => "Total Time Entries",
    'billable_entries' => "Billable Entries",
    'unbilled_entries' => "Unbilled Entries",
    'billable_time' => "Billable Time",
    'unbilled_time' => "Unbilled Time",
    'recent_time_entries' => "Recent Time Entries",
    'new_leads' => "New Sales Activity",
    'conversion_rate' => "Conversion Rate",
    'total_lead_value' => "Total Lead Value",
    'new_tickets' => 'New Tickets',
    'new_expenses' => "New Expenses",
    'billable_expenses' => "Billable Expenses",
    'day_of_month' => "Day of Month",
    'log_in_as_client' => "Log in as client",
    'show_trimesheet_recorded_by' => "Show timesheed recorded by",
    'restore_projects' => "Restore Projects",
    'archive_projects' => "Archive Projects",
    'importing_leads' => "Importing Leads",
    'importing_leads_settings' => "Importing Leads Settings",
    'avoid_duplicates' => "Avoid Duplicates",
    'avoid_duplicates_info' => "Duplicates will be skipped based on the selected criteria",
    'duplicates_skipped' => "Duplicates Skipped",
    'importing_clients_settings' => "Importing Clients Settings",

    //--------------V3.0 Language Changes-------------------
    'notes_project' => "Notes - Project",
    'notes_client' => "Notes - Client",
    'language_support_1' => "Extended Language Support - Option 1",
    'language_support_2' => "Extended Language Support - Option 2",
    'language_support_3' => "Extended Language Support - Option 3",
    'language_support_4' => "Extended Language Support - Option 4",
    'language_support_help' => "If special characters or currency symbols appear incorrectly in your PDF documents, try switching to an differnt [Extended Language Option] above, to resolve display issues",
    'import_checklists' => 'Import Checklists',
    'import_checklist_items' => 'Import Checklist Items',
    'upload_checklist_file' => 'Upload Checklist File',
    'supported_file_types' => 'Supported file types: Excel (.xlsx, .xls), CSV (.csv), Text (.txt)',
    'checklist_import_success' => 'Checklist items imported successfully',
    'checklist_import_failed' => 'Checklist import failed',
    'no_file_uploaded' => 'No file was uploaded',
    'file_upload_failed' => 'File upload failed',
    'invalid_file_type' => 'Invalid file type. Supported formats: Excel, CSV, Text',
    'task_not_found' => 'Task not found',
    'file_is_empty' => 'The uploaded file is empty',
    'file_not_readable' => 'The uploaded file is not readable',
    'import_checklist_help' => 'Import checklist items from a file. Each line will become a checklist item.',
    'maximum_importing_limit_reached' => "Maximum importing limit reaced",
    'add_comment' => 'Add Comment',
    'post_comment' => 'Post Comment',
    'comment_is_required' => 'Comment is required',
    'comment_posted_successfully' => 'Comment posted successfully',
    'comment_deleted_successfully' => 'Comment deleted successfully',
    'delete_comment' => 'Delete Comment',
    'checklist_comment' => 'Checklist Comment',
    'checklist_comments' => 'Checklist Comments',
    'no_comments_found' => 'No comments found',
    'comment_is_required' => 'Comment is required',
    'hide_comments' => "Hide Comments",
    'show_comments' => "Show Comments",
    'hide_checklist_comments' => "Hide Checklist Comments",
    'show_checklist_comments' => "Show Checklist Comments",
    'info_general_activity' => "Status changed, files attached, assignment, etc",
    'tasks_activity' => "Tasks Activity",
    'projects_activity' => "Project Activity",
    'tasks_comments' => "Task Comments",
    'projects_comments' => "Projects Comments",
    'leads_activity' => "Leads Activity",
    'leads_comments' => "Leads Comments",
    'mentions_only' => "Mentions Only",
    'inactive' => "Inactive",
    'manage_project_checklist' => "Manage Project Checklist",
    'project_check_lists' => "Project Checklists",
    'participate' => "Participate",
    'checklist' => "Checklist",
    'project_checklist' => "Project Checklist",
    'lead_logs' => 'Lead Logs',
    'record_a_log' => 'Record a log',
    'you_do_not_have_logs' => 'You do not have any logs',
    'call' => 'Call',
    'meeting' => 'Meeting',
    'general' => 'General',
    'archive_leads' => "Archive Leads",
    'bill_project_tasks' => "Bill Project Tasks",
    'project_tasks' => "Project Tasks",
    'add_selected_tasks' => "Add Selected Tasks",
    'date_completed' => "Date Completed",
    'selected_task_is_already_on_invoice' => "One or more selected tasks are already on this invoice",
    'show_only_completed' => "Show only completed tasks",
    'time_logged' => "Time Logged",
    'billed_time' => "Billed Time",
    'unbilled_time' => "Unbilled Time",
    'filter_tasks' => "Filter Tasks",
    'show_all_tasks' => "Show all tasks",
    'show_only_completed_tasks' => "Show only completed tasks",
    'show_all_tasks_with_unbilled_time' => "Show all tasks with unbilled time",
    'show_completed_tasks_with_unbilled_time' => "Show completed tasks with unbilled time",
    'add_tasks_fixed_billing' => "Add Tasks - Fixed Billing",
    'add_tasks_time_billing' => "Add Tasks - Time Billing",
    'system_information' => "System Information",
    'crm_version' => "CRM Version",
    'database_name' => "Database Name",
    'email_system' => "Email System",
    'smtp' => "SMTP",
    'sendmail' => "Local Server (Sendmail)",
    'last_cronjob_run' => "Last Cronjob Run",
    'never' => "Never",
    'php_version' => "PHP Version",
    'server_memory_limit' => "Server Memory Limit",
    'server_file_upload_limit' => "Server File Upload Limit",
    'crm_files_count' => "CRM Files & Attachments",
    'crm_attachments_count' => "CRM Attachments",
    'crm_hard_drive_usage' => "CRM Hard Drive Usage",
    'calculating' => "Calculating",
    'storage_folder' => "Storage Folder",
    'logs_folder' => "Log Files",
    'cache_folder' => "Cache Files",
    'free_up_space' => "Clean Up Storage",
    'cleanup_info' => "This will clean up temporary files, logs, and cache. Your project files and important CRM data will not be affected.",
    'temp_files' => "Temp Files",
    'cache_files' => "Cache Files",
    'log_files' => "Log Files",
    'clean_up_selected' => "Clean Up Selected",
    'cache_cleared' => "Cache has been cleared",
    'logs_cleared' => "Log files have been cleared",
    'temp_files_cleared' => "Temporary files have been cleared",
    'temp_folder' => "Temp Folder",
    'project_comments' => "Project Comments",
    'star_project_comments' => "Star Project Comments",
    'unstar_project_comments' => "Unstar Project Comments",
    'project_has_been_starred' => "Project has been starred",
    'project_has_been_unstarred' => "Project has been unstarred",
    'latest_activity' => "Latest Activity",
    'recent_activity' => "Recent Activity",
    'remove_from_list' => "Remove From List",
    'open_project' => "Open Project",
    'open_client' => "Open Client",
    'star' => "Star",
    'note_has_been_starred' => "Note has been starred",
    'note_has_been_unstarred' => "Note has been unstarred",
    'note_title' => "Note Title",
    'last_updated' => "Last Updated",
    'star_client' => "Star Client",
    'unstar_client' => "Unstar Client",
    'client_name' => "Client Name",
    'latest_seen' => "Latest Seen",
    'owner' => "Owner",
    'star_project' => "Star Project",
    'unstar_project' => "Unstar Project",
    'star_task' => "Star Task",
    'unstar_task' => "Unstar Task",
    'task_title' => "Task Title",
    'view_activity' => "View Activity",
    'foo' => "bar",
    'foo' => "bar",
    'foo' => "bar",
    'foo' => "bar",
    'foo' => "bar",
    'foo' => "bar",
    'foo' => "bar",
    
    // Accounting Module
    'accounting' => "Accounting",
    
    // Inventory Module
    'inventory' => "Inventory",
    'inventory_name' => "Item Name",
    'inventory_code' => "Item Code",
    'inventory_sku' => "SKU",
    'inventory_barcode' => "Barcode",
    'inventory_description' => "Description",
    'inventory_quantity' => "Quantity",
    'inventory_minimum_quantity' => "Minimum Quantity",
    'inventory_maximum_quantity' => "Maximum Quantity",
    'inventory_cost_price' => "Cost Price",
    'inventory_selling_price' => "Selling Price",
    'inventory_wholesale_price' => "Wholesale Price",
    'inventory_retail_price' => "Retail Price",
    'inventory_currency' => "Currency",
    'inventory_unit' => "Unit",
    'inventory_status' => "Status",
    'inventory_supplier' => "Supplier",
    'inventory_location' => "Location",
    'inventory_brand' => "Brand",
    'inventory_model' => "Model",
    'inventory_serial_number' => "Serial Number",
    'inventory_notes' => "Notes",
    'inventory_last_restocked' => "Last Restocked",
    'inventory_expiry_date' => "Expiry Date",
    'inventory_weight' => "Weight",
    'inventory_dimensions' => "Dimensions",
    'inventory_details' => "Inventory Details",
    'inventory_created_successfully' => "Inventory item created successfully",
    'inventory_updated_successfully' => "Inventory item updated successfully",
    'inventory_deleted_successfully' => "Inventory item deleted successfully",
    'add_inventory_item' => "Add Inventory Item",
    'update_inventory_item' => "Update Inventory Item",
    'no_inventory_items_found' => "No inventory items found",
    'total_items' => "Total Items",
    'active_items' => "Active Items",
    'low_stock' => "Low Stock",
    'total_value' => "Total Value",
    'add_item' => "Add Item",
    'basic_information' => "Basic Information",
    'pricing_stock' => "Pricing & Stock",
    'additional_information' => "Additional Information",
    'stock_pricing' => "Stock & Pricing",
    'system_information' => "System Information",
    'created_by' => "Created By",
    'created_at' => "Created At",
    'updated_at' => "Updated At",
    'inventory_id' => "Inventory ID",
    'no_notes_available' => "No notes available",
    'back_to_list' => "Back to List",
    'cancel' => "Cancel",
    'edit' => "Edit",
    'view' => "View",
    'delete' => "Delete",
    'actions' => "Actions",
    'active' => "Active",
    'inactive' => "Inactive",
    'discontinued' => "Discontinued",
    
    // Sales Module
    'sales' => "Sales",
    'sales_title' => "Sales Title",
    'sales_code' => "Sales Code",
    'sales_description' => "Description",
    'sales_type' => "Type",
    'sales_quantity' => "Quantity",
    'sales_unit_price' => "Unit Price",
    'sales_total_amount' => "Total Amount",
    'sales_discount_amount' => "Discount Amount",
    'sales_discount_percentage' => "Discount Percentage",
    'sales_tax_amount' => "Tax Amount",
    'sales_tax_percentage' => "Tax Percentage",
    'sales_final_amount' => "Final Amount",
    'sales_currency' => "Currency",
    'sales_status' => "Status",
    'sales_payment_status' => "Payment Status",
    'sales_payment_method' => "Payment Method",
    'sales_date' => "Sales Date",
    'sales_due_date' => "Due Date",
    'sales_reference' => "Reference",
    'sales_notes' => "Notes",
    'sales_salesperson' => "Salesperson",
    'sales_customer_name' => "Customer Name",
    'sales_customer_phone' => "Customer Phone",
    'sales_customer_address' => "Customer Address",
    'sales_invoice_number' => "Invoice Number",
    'sales_details' => "Sales Details",
    'sales_created_successfully' => "Sales record created successfully",
    'sales_updated_successfully' => "Sales record updated successfully",
    'sales_deleted_successfully' => "Sales record deleted successfully",
    'add_sales_record' => "Add Sales Record",
    'update_sales_record' => "Update Sales Record",
    'no_sales_records_found' => "No sales records found",
    'total_sales' => "Total Sales",
    'completed_sales' => "Completed Sales",
    'pending_sales' => "Pending Sales",
    'total_revenue' => "Total Revenue",
    'add_record' => "Add Record",
    'pricing_quantity' => "Pricing & Quantity",
    'status_payment' => "Status & Payment",
    'customer_information' => "Customer Information",
    'pricing_payment' => "Pricing & Payment",
    'sales_id' => "Sales ID",
    'sale' => "Sale",
    'return' => "Return",
    'refund' => "Refund",
    'pending' => "Pending",
    'completed' => "Completed",
    'cancelled' => "Cancelled",
    'refunded' => "Refunded",
    'unpaid' => "Unpaid",
    'paid' => "Paid",
    'partially_paid' => "Partially Paid",
    'overdue' => "Overdue",
    'delete_selected_items' => "Delete Selected Items",
    'delete_selected_records' => "Delete Selected Records",
    'inventory' => "Inventory",
    'sales' => "Sales",
    'add_inventory' => "Add Inventory",
    'add_sales' => "Add Sales",
        'save_inventory' => "Save Inventory",
        
        // New Inventory Fields
        'current_quantity' => "Current Quantity",
        'current_avg_price' => "Current Avg Price",
        'current_amount' => "Current Amount",
        'minimum_stock' => "Minimum Stock",
        'maximum_stock' => "Maximum Stock",
        'discrepancy' => "Discrepancy",
        'main_unit' => "Main Unit",
        'sub_unit' => "Sub Unit",
        'first_period_quantity' => "First Period Quantity",
        'first_period_sub_quantity' => "First Period Sub Quantity",
        'first_period_amount' => "First Period Amount",
        'first_period_avg_price' => "First Period Avg Price",
        'input_quantity' => "Input Quantity",
        'input_sub_quantity' => "Input Sub Quantity",
        'input_amount' => "Input Amount",
        'input_avg_price' => "Input Avg Price",
        'output_quantity' => "Output Quantity",
        'output_sub_quantity' => "Output Sub Quantity",
        'output_amount' => "Output Amount",
        'output_avg_price' => "Output Avg Price",
        'weighing_input' => "Weighing Input",
        'weighing_output' => "Weighing Output",
        
        // New Sales Fields
        'document_type' => "Document Type",
        'document_number' => "Document Number",
        'document_date' => "Document Date",
        'customer_code' => "Customer Code",
        'customer_name' => "Customer Name",
        'customer_full_name' => "Customer Full Name",
        'product_code' => "Product Code",
        'product_name' => "Product Name",
        'product_barcode' => "Product Barcode",
        'tracking_code' => "Tracking Code",
        'main_quantity' => "Main Quantity",
        'warehouse' => "Warehouse",
        'base_price' => "Base Price",
        'base_sales_amount' => "Base Sales Amount",
        'base_tax_amount' => "Base Tax Amount",
        'base_duty_amount' => "Base Duty Amount",
        'base_additional_amount' => "Base Additional Amount",
        'base_increasing_factors' => "Base Increasing Factors",
        'base_net_amount' => "Base Net Amount",
        'month' => "Month",
        'issued_main_quantity' => "Issued Main Quantity",
        'issued_sub_quantity' => "Issued Sub Quantity",
        'remaining_main_quantity' => "Remaining Main Quantity",
        'remaining_sub_quantity' => "Remaining Sub Quantity",
        'current_stock' => "Current Stock",
        'document_information' => "Document Information",
        'customer_information' => "Customer Information",
        'product_information' => "Product Information",
        'base_currency_pricing' => "Base Currency Pricing",
        'first_period' => "First Period",
        'input_section' => "Input Section",
        'output_section' => "Output Section",
        'weighing_section' => "Weighing Section",
        'stock_limits_units' => "Stock Limits & Units",
        'quantities' => "Quantities",
    'save_sales' => "Save Sales",
    'no_inventory_found' => "No inventory items found",
    'no_inventory_found_description' => "Start by adding your first inventory item",
    'no_sales_found' => "No sales records found",
    'no_sales_found_description' => "Start by adding your first sales record",
    'search_inventory' => "Search inventory...",
    'search_sales' => "Search sales...",
    'sku' => "SKU",
    'barcode' => "Barcode",
    'quantity' => "Quantity",
    'cost_price' => "Cost Price",
    'selling_price' => "Selling Price",
    'minimum_quantity' => "Minimum Quantity",
    'maximum_quantity' => "Maximum Quantity",
    'unit' => "Unit",
    'pieces' => "Pieces",
    'kilograms' => "Kilograms",
    'liters' => "Liters",
    'boxes' => "Boxes",
    'supplier' => "Supplier",
    'location' => "Location",
    'last_restocked' => "Last Restocked",
    'expiry_date' => "Expiry Date",
    'discontinued' => "Discontinued",
    'quantity_min' => "Min Quantity",
    'quantity_max' => "Max Quantity",
    'unit_price' => "Unit Price",
    'total_amount' => "Total Amount",
    'discount_amount' => "Discount Amount",
    'tax_amount' => "Tax Amount",
    'final_amount' => "Final Amount",
    'payment_status' => "Payment Status",
    'payment_method' => "Payment Method",
    'unpaid' => "Unpaid",
    'paid' => "Paid",
    'partially_paid' => "Partially Paid",
    'overdue' => "Overdue",
    'cash' => "Cash",
    'bank_transfer' => "Bank Transfer",
    'credit_card' => "Credit Card",
    'check' => "Check",
    'select_payment_method' => "Select Payment Method",
    'salesperson' => "Salesperson",
    'due_date' => "Due Date",
    'all_payment_statuses' => "All Payment Statuses",
    'sale' => "Sale",
    'return' => "Return",
    'refund' => "Refund",
    'refunded' => "Refunded",
    'completed' => "Completed",
    'cancelled' => "Cancelled",
    'pending' => "Pending",
    'select_client' => "Select Client",
    'select_project' => "Select Project",
    'select_category' => "Select Category",
    'all_clients' => "All Clients",
    'date_from' => "Date From",
    'date_to' => "Date To",
    
    // Import functionality
    'import' => "Import",
    'import_inventory' => "Import Inventory",
    'import_sales' => "Import Sales",
    'import_inventory_data' => "Import Inventory Data",
    'import_sales_data' => "Import Sales Data",
    'import_inventory_description' => "Upload an Excel or CSV file to import inventory data. The file should contain columns matching the inventory fields.",
    'import_sales_description' => "Upload an Excel or CSV file to import sales data. The file should contain columns matching the sales fields.",
    'select_file' => "Select File",
    'supported_formats' => "Supported formats",
    'max_file_size' => "Max file size",
    'sample_format' => "Sample Format",
    'import_data' => "Import Data",
    'import_results' => "Import Results",
    'imported' => "Imported",
    'skipped' => "Skipped",
    'skipped_details' => "Skipped Details",
    'row_number' => "Row Number",
    'reason' => "Reason",
    'back_to_inventory' => "Back to Inventory",
    'back_to_sales' => "Back to Sales",
    'maximum_importing_limit_reached' => "Maximum importing limit reached",
    'invalid_file_type' => "Invalid file type",
    'file_not_found' => "File not found",
    'no_file_uploaded' => "No file uploaded",
    'invoice_settlements' => "Invoice & Settlement",
    'import_invoice_settlements' => "Import Invoice & Settlement",
    'import_invoice_settlements_data' => "Import settlement data from Excel or CSV files. Ensure the column order matches the template.",
    'import_invoice_settlements_description' => "Each row should contain the document number, date, customer name, base net amount, paid amount, and remaining balance.",
    'import_invoice_settlements_success' => ":count invoice settlements imported successfully.",
    'import_failed_generic' => "Import failed. Please check the file and try again.",
    'total_records' => "Total Records",
    'total_net_amount' => "Total Net Amount",
    'total_paid_amount' => "Total Paid Amount",
    'total_balance_amount' => "Total Outstanding",
    'paid_amount' => "Paid Amount",
    'balance_amount' => "Balance",
    'filter_invoice_settlements' => "Filter Invoice & Settlement",
    'search_invoice_settlements' => "Search documents or customers...",
    'back_to_invoice_settlements' => "Back to Invoice & Settlement",
    'uploading' => "Uploading...",
    'columns' => "Columns",
    'min' => "Min",
    'max' => "Max",
    'start' => "Start",
    'end' => "End",
    
    // Units settings
    'units' => "Units",
    'units_settings' => "Units Settings",
    'units_description' => "Configure system units and measurements",
    'units_configuration' => "Units Configuration",
    'enter_units_configuration' => "Enter units configuration",
    'save_settings' => "Save Settings",
    'back_to_settings' => "Back to Settings",
    'settings_updated_successfully' => "Settings updated successfully",
    
    // Reports
    'expenses_by_client' => "Expenses by Client",
    'expenses_by_project' => "Expenses by Project",
    'by_client' => "By Client",
    'by_project' => "By Project",
    'expenses_by_client_report' => "Expenses by Client Report",
    'expenses_by_project_report' => "Expenses by Project Report",
    'expenses_by_client_description' => "View expenses report grouped by client",
    'expenses_by_project_description' => "View expenses report grouped by project",
    'report_coming_soon' => "This report feature is coming soon",
    
    // Proposals Reports
    'proposals_by_client' => "Proposals by Client",
    'proposals_by_client_report' => "Proposals by Client Report",
    'proposals_by_client_description' => "View proposals report grouped by client",

    // Pagination
    'showing' => "Showing",
    'to' => "to",
    'of' => "of",
    'results' => "results",

    // Column Settings
    'column_settings' => "Column Settings",
    'show_hide_columns' => "Show/Hide Columns",

    // View and Edit
    'view_inventory' => "View Inventory",
    'edit_inventory' => "Edit Inventory",
    'view_sales' => "View Sales",
    'edit_sales' => "Edit Sales",
    'back_to_inventory' => "Back to Inventory",
    'back_to_sales' => "Back to Sales",
    'update' => "Update",
    'cancel' => "Cancel",
    'active' => "Active",
    'inactive' => "Inactive",
    'pending' => "Pending",
    'completed' => "Completed",
    'cancelled' => "Cancelled",
    
    // Filter and Search
    'all_status' => "All Status",
    'all_items' => "All Items",
    'all_types' => "All Types",
    'low_stock' => "Low Stock",
    'out_of_stock' => "Out of Stock",
    'date_from' => "Date From",
    'sale' => "Sale",
    'invoice' => "Invoice",
    'quote' => "Quote",
];
```

```php
<?php

//persian

return [

'a_project_is_required_for_billable_expenses' => "برای هزینه‌های قابل فاکتور، پروژه الزامی است",
'accept_amendments' => "پذیرش اصلاحات",
'accept_estimate_confirm' => "لطفاً تأیید کنید که می‌خواهید این برآورد را بپذیرید",
'accept_estimate' => "پذیرش برآورد",
'accept' => "پذیرش",
'accepted' => "پذیرفته شده",
'account_has_been_suspended' => "حساب شما معلق شده است",
'account_not_found' => "کاربر یافت نشد",
'account_number' => "شماره حساب",
'account_owner' => "صاحب حساب",
'account_status' => "وضعیت حساب",
'account' => "حساب",
'action_items' => "اقدامات",
'action_not_available_in_demo_mode' => "این عمل در حالت نمایشی در دسترس نیست",
'action_not_completed_errors_found' => "درخواست قابل تکمیل نیست",
'action_only_available_on_declined_estimates' => "این عمل فقط برای برآوردهای رد شده در دسترس است",
'action' => "عمل",
'actions_not_available' => "این عمل برای این منبع در دسترس نیست",
'actions' => "اقدامات",
'activate' => "فعال‌سازی",
'active_users' => "کاربران فعال",
'active' => "فعال",
'activity' => "فعالیت",
'add_a_new_category' => "افزودن دسته‌بندی جدید",
'add_a_new_item' => "افزودن آیتم جدید",
'add_a_payment' => "افزودن پرداخت جدید",
'add_article' => "ایجاد مقاله پایگاه دانش",
'add_attachment' => "افزودن پیوست به فایل",
'add_category' => "ایجاد دسته‌بندی جدید",
'add_client_manager' => "افزودن مدیر مشتریان",
'add_client' => "افزودن مشتری",
'add_comment' => "افزودن نظر",
'add_contact' => "افزودن مخاطب",
'add_credit_note' => "افزودن یادداشت اعتباری",
'add_department' => "افزودن بخش",
'add_due_date' => "افزودن تاریخ سررسید",
'add_estimate' => "ایجاد برآورد جدید",
'add_event' => "افزودن رویداد",
'add_expense' => "افزودن هزینه جدید",
'add_file' => "افزودن فایل",
'add_invoice' => "ایجاد فاکتور جدید",
'add_item' => "افزودن نظر",
'add_knowledge_base' => "ایجاد مقاله جدید",
'add_lead' => "افزودن سرنخ",
'add_milestone' => "افزودن نقطه عطف",
'add_note' => "افزودن یادداشت",
'add_payment' => "افزودن پرداخت",
'add_project' => "افزودن پروژه",
'add_reminder' => "افزودن یادآوری",
'add_task' => "افزودن وظیفه",
'add_team_member' => "افزودن عضو تیم",
'add_template' => "افزودن قالب",
'add_ticket' => "افزودن تیکت",
'add_timesheet' => "افزودن برگه زمان",
'add_user' => "افزودن کاربر",
'address' => "آدرس",
'admin' => "مدیر",
'admin_panel' => "پنل مدیریت",
'advanced' => "پیشرفته",
'after' => "بعد از",
'ago' => "پیش",
'all' => "همه",
'all_clients' => "همه مشتریان",
'all_projects' => "همه پروژه‌ها",
'all_tasks' => "همه وظایف",
'amount' => "مبلغ",
'and' => "و",
'annual' => "سالانه",
'april' => "آوریل",
'archive' => "آرشیو",
'archived' => "آرشیو شده",
'assign' => "تخصیص",
'assigned' => "تخصیص داده شده",
'assigned_to' => "تخصیص داده شده به",
'assignee' => "تخصیص داده شده",
'attachment' => "پیوست",
'attachments' => "پیوست‌ها",
'august' => "اوت",
'author' => "نویسنده",
'auto_assign' => "تخصیص خودکار",
'available' => "در دسترس",
'back' => "بازگشت",
'balance' => "موجودی",
'before' => "قبل از",
'billable' => "قابل فاکتور",
'billable_hours' => "ساعات قابل فاکتور",
'billing' => "فاکتورسازی",
'body' => "متن",
'budget' => "بودجه",
'bulk_actions' => "اقدامات گروهی",
'business_hours' => "ساعات کاری",
'calendar' => "تقویم",
'cancel' => "لغو",
'category' => "دسته‌بندی",
'change' => "تغییر",
'change_password' => "تغییر رمز عبور",
'checklist' => "چک‌لیست",
'city' => "شهر",
'client' => "مشتری",
'clients' => "مشتریان",
'close' => "بستن",
'closed' => "بسته شده",
'code' => "کد",
'color' => "رنگ",
'comment' => "نظر",
'comments' => "نظرات",
'company' => "شرکت",
'completed' => "تکمیل شده",
'confirm' => "تأیید",
'contact' => "مخاطب",
'contacts' => "مخاطبین",
'contract' => "قرارداد",
'contracts' => "قراردادها",
'copy' => "کپی",
'country' => "کشور",
'create' => "ایجاد",
'created' => "ایجاد شده",
'created_by' => "ایجاد شده توسط",
'credit' => "اعتبار",
'currency' => "واحد پول",
'current' => "فعلی",
'custom' => "سفارشی",
'custom_field' => "فیلد سفارشی",
'custom_fields' => "فیلدهای سفارشی",
'dashboard' => "داشبورد",
'date' => "تاریخ",
'date_created' => "تاریخ ایجاد",
'date_due' => "تاریخ سررسید",
'date_modified' => "تاریخ تغییر",
'day' => "روز",
'december' => "دسامبر",
'default' => "پیش‌فرض",
'delete' => "حذف",
'deleted' => "حذف شده",
'description' => "توضیحات",
'details' => "جزئیات",
'discount' => "تخفیف",
'do_not_reply' => "به این ایمیل پاسخ ندهید",
'draft' => "پیش‌نویس",
'due' => "سررسید",
'duration' => "مدت زمان",
'edit' => "ویرایش",
'email' => "ایمیل",
'end_date' => "تاریخ پایان",
'error' => "خطا",
'estimate' => "برآورد",
'estimates' => "برآوردها",
'event' => "رویداد",
'events' => "رویدادها",
'expense' => "هزینه",
'expenses' => "هزینه‌ها",
'export' => "صادرات",
'failed' => "ناموفق",
'february' => "فوریه",
'file' => "فایل",
'files' => "فایل‌ها",
'filter' => "فیلتر",
'first_name' => "نام",
'forgot_password' => "فراموشی رمز عبور",
'friday' => "جمعه",
'from' => "از",
'full_name' => "نام کامل",
'general' => "عمومی",
'go_back' => "بازگشت",
'group' => "گروه",
'groups' => "گروه‌ها",
'help' => "راهنما",
'high' => "بالا",
'home' => "خانه",
'hour' => "ساعت",
'hours' => "ساعات",
'id' => "شناسه",
'image' => "تصویر",
'import' => "واردات",
'in_progress' => "در حال انجام",
'inactive' => "غیرفعال",
'income' => "درآمد",
'invoice' => "فاکتور",
'invoices' => "فاکتورها",
'item' => "آیتم",
'items' => "آیتم‌ها",
'january' => "ژانویه",
'july' => "جولای",
'june' => "ژوئن",
'language' => "زبان",
'last_login' => "آخرین ورود",
'last_name' => "نام خانوادگی",
'lead' => "سرنخ",
'leads' => "سرنخ‌ها",
'link' => "لینک",
'list' => "لیست",
'location' => "مکان",
'login' => "ورود",
'logout' => "خروج",
'low' => "پایین",
'mail' => "پست",
'march' => "مارس",
'may' => "مه",
'medium' => "متوسط",
'member' => "عضو",
'members' => "اعضا",
'message' => "پیام",
'messages' => "پیام‌ها",
'milestone' => "نقطه عطف",
'milestones' => "نقاط عطف",
'minute' => "دقیقه",
'minutes' => "دقایق",
'module' => "ماژول",
'modules' => "ماژول‌ها",
'monday' => "دوشنبه",
'month' => "ماه",
'months' => "ماه‌ها",
'name' => "نام",
'new' => "جدید",
'no' => "خیر",
'none' => "هیچ",
'normal' => "عادی",
'note' => "یادداشت",
'notes' => "یادداشت‌ها",
'november' => "نوامبر",
'number' => "شماره",
'october' => "اکتبر",
'of' => "از",
'off' => "خاموش",
'on' => "روشن",
'open' => "باز",
'options' => "گزینه‌ها",
'order' => "ترتیب",
'overdue' => "سررسید گذشته",
'overview' => "نمای کلی",
'owner' => "مالک",
'page' => "صفحه",
'pages' => "صفحات",
'paid' => "پرداخت شده",
'password' => "رمز عبور",
'payment' => "پرداخت",
'payments' => "پرداخت‌ها",
'pending' => "در انتظار",
'permission' => "مجوز",
'permissions' => "مجوزها",
'phone' => "تلفن",
'please_wait' => "لطفاً صبر کنید",
'preview' => "پیش‌نمایش",
'price' => "قیمت",
'priority' => "اولویت",
'private' => "خصوصی",
'profile' => "پروفایل",
'project' => "پروژه",
'projects' => "پروژه‌ها",
'public' => "عمومی",
'quantity' => "تعداد",
'rate' => "نرخ",
'recent' => "اخیر",
'record' => "رکورد",
'records' => "رکوردها",
'ref' => "مرجع",
'reference' => "مرجع",
'refresh' => "تازه‌سازی",
'reminder' => "یادآوری",
'reminders' => "یادآوری‌ها",
'report' => "گزارش",
'reports' => "گزارش‌ها",
'required' => "الزامی",
'reset' => "بازنشانی",
'role' => "نقش",
'roles' => "نقش‌ها",
'saturday' => "شنبه",
'save' => "ذخیره",
'saved' => "ذخیره شده",
'schedule' => "برنامه‌ریزی",
'search' => "جستجو",
'select' => "انتخاب",
'selected' => "انتخاب شده",
'send' => "ارسال",
'sent' => "ارسال شده",
'settings' => "تنظیمات",
'start_date' => "تاریخ شروع",
'status' => "وضعیت",
'subject' => "موضوع",
'submit' => "ارسال",
'success' => "موفق",
'sunday' => "یکشنبه",
'system' => "سیستم",
'tag' => "برچسب",
'tags' => "برچسب‌ها",
'task' => "وظیفه",
'tasks' => "وظایف",
'tax' => "مالیات",
'template' => "قالب",
'templates' => "قالب‌ها",
'text' => "متن",
'thursday' => "پنج‌شنبه",
'ticket' => "تیکت",
'tickets' => "تیکت‌ها",
'time' => "زمان",
'timesheet' => "برگه زمان",
'timesheets' => "برگه‌های زمان",
'title' => "عنوان",
'to' => "به",
'today' => "امروز",
'total' => "مجموع",
'tuesday' => "سه‌شنبه",
'type' => "نوع",
'unassigned' => "تخصیص داده نشده",
'unit' => "واحد",
'update' => "به‌روزرسانی",
'updated' => "به‌روزرسانی شده",
'upload' => "آپلود",
'user' => "کاربر",
'users' => "کاربران",
'value' => "مقدار",
'view' => "نمایش",
'views' => "نمایش‌ها",
'wednesday' => "چهارشنبه",
'week' => "هفته",
'weeks' => "هفته‌ها",
'year' => "سال",
'years' => "سال‌ها",
'yes' => "بله",
'yesterday' => "دیروز",
'zip' => "کد پستی",

    // Inventory and Sales - Persian Translations
    'inventory' => "انبارداری",
    'sales' => "فروش",
    'accounting' => "حسابداری",
    'add_inventory_item' => "افزودن آیتم انبار",
    'add_sales_record' => "افزودن رکورد فروش",
    'import_inventory' => "وارد کردن انبار",
    'import_sales' => "وارد کردن فروش",
'invoice_settlements' => "فاکتور و تسویه",
'import_invoice_settlements' => "وارد کردن فاکتور و تسویه",
'import_invoice_settlements_data' => "داده‌های فاکتور و تسویه را از فایل اکسل یا CSV مطابق قالب نمونه وارد کنید.",
'import_invoice_settlements_description' => "ستون‌های فایل باید شامل شماره، تاریخ، نام مشتری، مبلغ خالص، مبلغ پرداختی و مانده باشد.",
'import_invoice_settlements_success' => ":count رکورد فاکتور و تسویه با موفقیت وارد شد.",
'import_failed_generic' => "عملیات وارد کردن انجام نشد. لطفاً فایل را بررسی و دوباره تلاش کنید.",
'total_records' => "تعداد کل رکوردها",
'total_net_amount' => "جمع مبلغ خالص",
'total_paid_amount' => "جمع پرداختی",
'total_balance_amount' => "جمع مانده",
'paid_amount' => "مبلغ پرداخت شده",
'balance_amount' => "مانده",
'filter_invoice_settlements' => "فیلتر فاکتور و تسویه",
'search_invoice_settlements' => "جستجو در فاکتور و تسویه...",
'back_to_invoice_settlements' => "بازگشت به فاکتور و تسویه",
'uploading' => "در حال آپلود...",
'columns' => "ستون‌ها",
'min' => "کمترین",
'max' => "بیشترین",
'start' => "شروع",
'end' => "پایان",
    
    // 📦 Inventory Columns
    'inventory_code' => "كد كالا",
    'inventory_name' => "عنوان كالا",
    'first_period_quantity' => "اول دوره-مقدار",
    'first_period_sub_quantity' => "اول دوره-مقدار واحد فرعي",
    'first_period_amount' => "اول دوره-مبلغ",
    'first_period_avg_price' => "اول دوره-في متوسط",
    'input_quantity' => "ورودي-مقدار",
    'input_sub_quantity' => "ورودي-مقدار واحد فرعي",
    'input_amount' => "ورودي-مبلغ",
    'input_avg_price' => "ورودي-في متوسط",
    'output_quantity' => "خروجي-مقدار",
    'output_sub_quantity' => "خروجي-مقدار واحد فرعي",
    'output_amount' => "خروجي-مبلغ",
    'output_avg_price' => "خروجي-في متوسط",
    'current_quantity' => "موجودي-مقدار",
    'current_sub_quantity' => "موجودي-مقدار واحد فرعي",
    'weighing_input' => "توزين - ورود",
    'weighing_output' => "توزين - خروج",
    'current_amount' => "موجودي-مبلغ",
    'current_avg_price' => "موجودي-في متوسط",
    'minimum_stock' => "حداقل موجودي",
    'maximum_stock' => "حداكثر موجودي",
    'discrepancy' => "مغايرت",
    'main_unit' => "واحد اصلي",
    'sub_unit' => "واحد فرعي",
    'inventory_status' => "وضعیت انبار",
    
    // 💰 Sales Columns
    'document_type' => "نوع سند",
    'document_number' => "شماره",
    'document_date' => "تاريخ",
    'customer_code' => "كد مشتري",
    'customer_name' => "مشتري",
    'customer_full_name' => "نام مشتري",
    'sales_type' => "نوع فروش",
    'product_code' => "كد كالا/خدمت",
    'product_name' => "كالا/خدمت",
    'product_barcode' => "بار كد كالا",
    'tracking_code' => "رديابي",
    'main_unit' => "واحد اصلي",
    'main_quantity' => "مقدار-اصلي",
    'warehouse' => "انبار",
    'base_price' => "في به ارز پايه",
    'base_sales_amount' => "مبلغ فروش به ارز پايه",
    'base_tax_amount' => "ماليات به ارز پايه",
    'base_duty_amount' => "عوارض به ارز پايه",
    'base_additional_amount' => "اضافات به ارز پايه",
    'base_increasing_factors' => "عوامل افزاينده به ارز پايه",
    'base_net_amount' => "خالص به ارز پايه",
    'month' => "ماه",
    'description' => "توضيحات",
    'issued_main_quantity' => "مقدار خارج شده اصلي",
    'issued_sub_quantity' => "مقدار خارج شده فرعي",
    'remaining_main_quantity' => "مانده خارج نشده اصلي",
    'remaining_sub_quantity' => "مانده خارج نشده فرعي",
    'currency' => "ارز",
    'sales_status' => "وضعیت فروش",
    
    // Common Actions
    'view_inventory' => "مشاهده انبار",
    'edit_inventory' => "ویرایش انبار",
    'view_sales' => "مشاهده فروش",
    'edit_sales' => "ویرایش فروش",
    'back_to_inventory' => "بازگشت به انبار",
    'back_to_sales' => "بازگشت به فروش",
    'update' => "به‌روزرسانی",
    'cancel' => "لغو",
    'active' => "فعال",
    'inactive' => "غیرفعال",
    'pending' => "در انتظار",
    'completed' => "تکمیل شده",
    'cancelled' => "لغو شده",
    'all_status' => "همه وضعیت‌ها",
    'all_items' => "همه آیتم‌ها",
    'all_types' => "همه انواع",
    'low_stock' => "موجودی کم",
    'out_of_stock' => "تمام شده",
    'date_from' => "از تاریخ",
    'sale' => "فروش",
    'invoice' => "فاکتور",
    'quote' => "پیشنهاد قیمت",
    'column_settings' => "تنظیمات ستون",
    'show_hide_columns' => "نمایش/مخفی کردن ستون‌ها",
    'showing' => "نمایش",
    'to' => "تا",
    'of' => "از",
    'results' => "نتایج",
    'search_inventory' => "جستجو در انبار",
    'search_sales' => "جستجو در فروش",
    'created_at' => "تاریخ ایجاد",
    'updated_at' => "تاریخ به‌روزرسانی",

];


```

## Summary
- Added the complete backend scaffold for the new `invoice_settlements` domain (migration, repository, middleware, controller, response, routes) so the Accounting menu now exposes a fully filterable/stat-driven endpoint for the Excel data set, including nav wiring under Accounting and REST hookups for both listing and import flows.  
```24:55:application/app/Http/Controllers/InvoiceSettlementController.php
    public function index()
    {
        if (request()->get('action') === 'unique_values' && request()->has('column')) {
            $values = $this->repo->getUniqueValues(request('column'));
            return response()->json([
                'success' => true,
                'data' => $values,
            ]);
        }

        if (request()->get('action') === 'datatables') {
            return $this->getDataTablesData();
        }

        $page = $this->pageSettings();
        $settlements = $this->repo->search();

        $stats = [
            'total_records' => $settlements->total(),
            'total_net' => InvoiceSettlement::sum('base_net_amount'),
            'total_paid' => InvoiceSettlement::sum('paid_amount'),
            'total_balance' => InvoiceSettlement::sum('balance_amount'),
        ];

        $payload = [
            'page' => $page,
            'settlements' => $settlements,
            'stats' => $stats,
            'settlementrepo' => $this->repo,
        ];

        return new IndexResponse($payload);
    }
```

- Recreated the Sales-style UI for Invoice & Settlement: stats cards, search+filter bar, import button, dynamic column toggles, column-level AJAX searches, and the responsive table wrapper that shares the global NX interactions.  
```20:138:application/resources/views/pages/invoice-settlements/index.blade.php
    <div class="row g-3">
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="ti-file font-24 text-primary"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-0">{{ cleanLang(__('lang.total_records')) }}</p>
                        <h4 class="my-1">{{ number_format($stats['total_records'] ?? 0) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        ...
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.column-search-input').forEach(function (input) {
            input.addEventListener('change', function () {
                let column = this.dataset.column;
                let value = encodeURIComponent(this.value);
                let url = this.dataset.url + '?action=search&column_search_' + column + '=' + value;
                NX.ajaxUxRequest(url);
            });
        });
        ...
```

- Delivered a dedicated import workflow (new upload page plus `InvoiceSettlementsImport`) that maps the six spreadsheet columns into the new table, tracks skipped rows, and surfaces per-row issues to the user.  
```31:57:application/app/Imports/InvoiceSettlementsImport.php
    public function model(array $row)
    {
        $this->rowIndex++;

        if ($this->isEmptyRow($row)) {
            $this->recordSkip('empty_row', $row);
            return null;
        }

        ++$this->rows;

        try {
            return new InvoiceSettlement([
                'document_number' => $this->normalizeDocumentNumber($row[0] ?? null),
                'document_date' => $this->normalizeDate($row[1] ?? null),
                'customer_name' => trim($row[2] ?? ''),
                'base_net_amount' => $this->parseDecimal($row[3] ?? 0),
                'paid_amount' => $this->parseDecimal($row[4] ?? 0),
                'balance_amount' => $this->parseDecimal($row[5] ?? 0),
                'currency' => 'IRR',
                'creator_id' => auth()->id(),
            ]);
        } catch (\Exception $e) {
            Log::error('Invoice settlement import failed', ['error' => $e->getMessage()]);
            $this->recordSkip('exception: ' . $e->getMessage(), $row);
            return null;
        }
    }
```

## Testing
- Not run (not requested).

## Next Steps
- Run `php artisan migrate` to create the `invoice_settlements` table.
- Visit `/invoice-settlements` to verify the new sidebar entry, filters, and column settings behave as expected, then upload `فاكتور و تسويه.xlsx` through `/import/invoice-settlements` to populate data.

```php
    public function index()
    {
        if (request()->get('action') === 'unique_values' && request()->has('column')) {
            $values = $this->repo->getUniqueValues(request('column'));
            return response()->json([
                'success' => true,
                'data' => $values,
            ]);
        }

        if (request()->get('action') === 'datatables') {
            return $this->getDataTablesData();
        }

        $page = $this->pageSettings();
        $settlements = $this->repo->search();

        $stats = [
            'total_records' => $settlements->total(),
            'total_net' => InvoiceSettlement::sum('base_net_amount'),
            'total_paid' => InvoiceSettlement::sum('paid_amount'),
            'total_balance' => InvoiceSettlement::sum('balance_amount'),
        ];

        $payload = [
            'page' => $page,
            'settlements' => $settlements,
            'stats' => $stats,
            'settlementrepo' => $this->repo,
        ];

        return new IndexResponse($payload);
    }
```

```php
    <div class="row g-3">
        <div class="col-lg-3 col-md-6">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="ti-file font-24 text-primary"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-0">{{ cleanLang(__('lang.total_records')) }}</p>
                        <h4 class="my-1">{{ number_format($stats['total_records'] ?? 0) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        ...
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.column-search-input').forEach(function (input) {
            input.addEventListener('change', function () {
                let column = this.dataset.column;
                let value = encodeURIComponent(this.value);
                let url = this.dataset.url + '?action=search&column_search_' + column + '=' + value;
                NX.ajaxUxRequest(url);
            });
        });
        ...
```

```php
    public function model(array $row)
    {
        $this->rowIndex++;

        if ($this->isEmptyRow($row)) {
            $this->recordSkip('empty_row', $row);
            return null;
        }

        ++$this->rows;

        try {
            return new InvoiceSettlement([
                'document_number' => $this->normalizeDocumentNumber($row[0] ?? null),
                'document_date' => $this->normalizeDate($row[1] ?? null),
                'customer_name' => trim($row[2] ?? ''),
                'base_net_amount' => $this->parseDecimal($row[3] ?? 0),
                'paid_amount' => $this->parseDecimal($row[4] ?? 0),
                'balance_amount' => $this->parseDecimal($row[5] ?? 0),
                'currency' => 'IRR',
                'creator_id' => auth()->id(),
            ]);
        } catch (\Exception $e) {
            Log::error('Invoice settlement import failed', ['error' => $e->getMessage()]);
            $this->recordSkip('exception: ' . $e->getMessage(), $row);
            return null;
        }
    }
```

