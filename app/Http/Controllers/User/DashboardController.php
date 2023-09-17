<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\CityRequest;
use App\Http\Controllers\Controller;
use App\Models\City;
use \App\Models\User;
use File;
use \Carbon\Carbon;
use \Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
class DashboardController extends Controller
{
     public function __construct(){
       
		
     }
     public function index()
    {
        $user = auth()->user();
     //   dd(can('list_leads'));
      // dd($user->getPermissionsViaRoles()->toArray());
     //   $user->assignRole('Admin');
     /*
        $data['total_expense'] = \DB::table('company_ledger')->whereMode('Spent')->sum('amount');
        $data['total_sell'] = \DB::table('company_ledger')->whereMode('Income')->sum('amount');
        $data['total_expense_today'] = \DB::table('company_ledger')->whereMode('Spent')->whereDay('created_at', '=', Carbon::now()->day)->sum('amount');
        $data['total_sell_today'] = \DB::table('company_ledger')->whereMode('Income')->whereDay('created_at', '=', Carbon::now()->day)->sum('amount');
        $data['total_expense_weekly'] = \DB::table('company_ledger')->whereMode('Spent')->whereBetween('created_at',
            [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]
        )->sum('amount');
        $data['total_expense_weekly'] = \DB::table('company_ledger')->whereMode('Income')->whereBetween('created_at',
            [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]
        )->sum('amount');

        $data['total_expense_monthly'] = \DB::table('company_ledger')->whereMode('Spent')->whereMonth('created_at', Carbon::now()->month)->sum('amount');
        $data['total_sell_monthly'] = \DB::table('company_ledger')->whereMode('Income')->whereMonth('created_at', Carbon::now()->month)->sum('amount');

        $data['top_five_expense'] = \DB::table('company_ledger')->whereMode('Spent')->orderBy('created_at', 'DESC')->take(5)->get();
        $data['top_five_sells'] = \DB::table('company_ledger')->whereMode('Income')->orderBy('created_at', 'DESC')->take(5)->get();
        $data['top_five_orders'] = \DB::table('create_order')->orderBy('created_at', 'DESC')->take(5)->get();
        $data['top_five_orders_paid'] = \DB::table('create_order')->wherePaidStatus('paid')->orderBy('created_at', 'DESC')->take(5)->get();*/
        /**Leads */
        $data=[];
        if(!auth()->user()->hasRole(['Store Incharge'])){
        $data['top_five_leads'] = \DB::table('leads')->where('assigned_id',auth()->id())->orderBy('created_at', 'DESC')->take(5)->get();
        $data['top_five_converted_leads'] = \DB::table('leads')->where('assigned_id',auth()->id())->whereStatus('Converted')->orderBy('created_at', 'DESC')->take(5)->get();
        $data['total_leads_entered'] = \DB::table('leads')->where('assigned_id',auth()->id())->count();
        $data['total_leads_failed'] = \DB::table('leads')->where('assigned_id',auth()->id())->whereStatus('Failed')->count();
        $data['total_leads_active'] = \DB::table('leads')->where('assigned_id',auth()->id())->whereStatus('Working')->orWhere('status','New')->count();
       
        $data['today_leads'] = \DB::table('leads')->where('assigned_id',auth()->id())->whereDay('created_at', '=', Carbon::now()->day)->count();
        $data['total_leads_success'] = \DB::table('leads')->where('assigned_id',auth()->id())->whereStatus('Converted')->count();
        $data['today_leads_success'] = \DB::table('leads')->where('assigned_id',auth()->id())->whereStatus('Converted')->whereDay('created_at', '=', Carbon::now()->day)->count();
        $data['top_five_followup'] = \DB::table('leads')->whereNotIn('status',['Failed','Converted'])->whereNotNull('followup_date')->where('assigned_id',auth()->id())->whereDate('created_at', '>=', Carbon::now())->orderBy('followup_date', 'ASC')->take(5)->get();
        $data['top_five_today_followup'] = \DB::table('leads')->whereNotIn('status',['Failed','Converted'])->whereNotNull('followup_date')->where('assigned_id',auth()->id())->whereDay('followup_date', '=', Carbon::now()->day)->orderBy('followup_date', 'ASC')->take(5)->get();
        }
        else{
            $store=\DB::table('stores')->whereOwnerId(auth()->id())->first();
            if(is_null($store)){
                return '<center><h1>Please assign some store,then login </h1></center';
            }
            $data['total_products_count']=\DB::table('store_assigned_product_stocks')->whereStoreId($store->id)->count();
            $data['top_five_orders'] = \DB::table('create_order')->whereStoreId($store->id)->orderBy('created_at', 'DESC')->take(5)->get();
           $data['top_five_orders_paid'] = \DB::table('create_order')->whereStoreId($store->id)->wherePaidStatus('paid')->orderBy('created_at', 'DESC')->take(5)->get();
            $data['total_orders_count'] = \DB::table('create_order')->whereStoreId($store->id)->count();
           $data['total_orders_paid_count'] = \DB::table('create_order')->whereStoreId($store->id)->wherePaidStatus('paid')->count();
           $data['today_orders_count'] = \DB::table('create_order')->whereStoreId($store->id)->count();
           $data['today_orders_paid_count'] = \DB::table('create_order')->whereStoreId($store->id)->wherePaidStatus('paid')->count();

        }
        
//dd($data);
        return view('user.dashboard', with($data));
    }
    public function dashboard_data(Request $r)
    {
        if ($r->ajax()) {
            $expense_perday_record = getDailyRecord('company_ledger', $date_column = 'created_at', $to_do = 'sum', $cond = "`mode`='Spent'", $column_for_sum = "amount", $for_days = 7);
            $sell_perday_record = getDailyRecord('company_ledger', $date_column = 'created_at', $to_do = 'sum', $cond = "`mode`='Income'", $column_for_sum = "amount", $for_days = 7);

            $expsnse_val = $expense_perday_record['val'];

            $sell_val = $sell_perday_record['val'];
            $dates_expsense = $expense_perday_record['datetime'];
            $dates_sell = $sell_perday_record['datetime'];

            $dates = array_unique(array_merge($dates_expsense, $dates_sell));

            $sell_monthwise_val = getMonthlyRecord('company_ledger', $date_column = 'created_at', $to_do = 'sum', $cond = "`mode`='Income'", $column_for_sum = "amount");

            $expense_monthwise_val = getMonthlyRecord('company_ledger', $date_column = 'created_at', $to_do = 'sum', $cond = "`mode`='Spent'", $column_for_sum = "amount");

            $order_monthwise_val = getMonthlyRecord('create_order', $date_column = 'created_at', $to_do = 'count', $cond = '');

            $daily_order_record = getDailyRecord('create_order', $date_column = 'created_at', $to_do = 'count', $cond = '');
            $weekly_order_val = getWeeklyRecord('create_order', $date_column = 'created_at', $to_do = 'count', $cond = '');
            $daily_order_val = $daily_order_record['val'];
            $dates_order = $daily_order_record['datetime'];
            /***Paid order */
            $paid_order_monthwise_val = getMonthlyRecord('create_order', $date_column = 'created_at', $to_do = 'count', $cond = "`paid_status`='Paid'");

            $paid_daily_order_record = getDailyRecord('create_order', $date_column = 'created_at', $to_do = 'count', $cond = "`paid_status`='Paid'");
            $paid_weekly_order_val = getWeeklyRecord('create_order', $date_column = 'created_at', $to_do = 'count', $cond = "`paid_status`='Paid'");
            $paid_daily_order_val = $paid_daily_order_record['val'];
            $paid_dates_order = $paid_daily_order_record['datetime'];

            /***Leads Report */
            $leads_monthwise_val = getMonthlyRecord('leads', $date_column = 'created_at', $to_do = 'count', $cond = '');

            $daily_leads_record = getDailyRecord('leads', $date_column = 'created_at', $to_do = 'count', $cond = '');
            $weekly_leads_val = getWeeklyRecord('leads', $date_column = 'created_at', $to_do = 'count', $cond = '');
            $daily_leads_val = $daily_leads_record['val'];
            $dates_leads = $daily_leads_record['datetime'];
            /***Paid order */
            $successfull_leads_monthwise_val = getMonthlyRecord('leads', $date_column = 'created_at', $to_do = 'count', $cond = "`status`='Converted'");

            $successfull_daily_leads_record = getDailyRecord('leads', $date_column = 'created_at', $to_do = 'count', $cond = "`status`='Converted'");
            $successfull_weekly_leads_val = getWeeklyRecord('leads', $date_column = 'created_at', $to_do = 'count', $cond = "`status`='Converted'");
            $successfull_daily_leads_val = $successfull_daily_leads_record['val'];
            $successfull_dates_leads = $successfull_daily_leads_record['datetime'];
            /***Sell Report   */
            $sell_monthwise_val = getMonthlyRecord('company_ledger', $date_column = 'created_at', $to_do = 'sum', "`mode`='Income'");
            $daily_sell_record = getDailyRecord('company_ledger', $date_column = 'created_at', $to_do = 'sum', "`mode`='Income'");
            $weekly_sell_val = getWeeklyRecord('company_ledger', $date_column = 'created_at', $to_do = 'sum', "`mode`='Income'");
            $daily_sell_val = $daily_sell_record['val'];
            $dates_sell = $daily_sell_record['datetime'];
            /***Expense Report   */
            $expense_monthwise_val = getMonthlyRecord('company_ledger', $date_column = 'created_at', $to_do = 'sum', "`mode`='Spent'");
            $daily_expense_record = getDailyRecord('company_ledger', $date_column = 'created_at', $to_do = 'sum', "`mode`='Spent'");
            $weekly_expense_val = getWeeklyRecord('company_ledger', $date_column = 'created_at', $to_do = 'sum', "`mode`='Spent'");
            $daily_expense_val = $daily_expense_record['val'];
            $dates_expense = $daily_expense_record['datetime'];

            return createResponse(true, json_encode(
                ['expense' => $expsnse_val,
                    'sell' => $sell_val,
                    'dates' => $dates,
                    'expense_monthwise_val' => $expense_monthwise_val,
                    'sell_monthwise_val' => $sell_monthwise_val,
                    'order_monthwise_val' => $order_monthwise_val,
                    'order_daily_val' => $daily_order_val,
                    'order_daily_dates' => $dates_order,
                    'order_weekly_val' => $weekly_order_val,
                    'paid_order_monthwise_val' => $paid_order_monthwise_val,
                    'paid_order_daily_val' => $paid_daily_order_val,
                    'paid_order_daily_dates' => $paid_dates_order,
                    'paid_order_weekly_val' => $paid_weekly_order_val,

                    'leads_monthwise_val' => $leads_monthwise_val,
                    'leads_daily_val' => $daily_leads_val,
                    'leads_daily_dates' => $dates_leads,
                    'leads_weekly_val' => $weekly_leads_val,

                    'successfull_leads_monthwise_val' => $successfull_leads_monthwise_val,
                    'successfull_leads_daily_val' => $successfull_daily_leads_val,
                    'successfull_leads_daily_dates' => $successfull_dates_leads,
                    'successfull_leads_weekly_val' => $successfull_weekly_leads_val,

                    'sell_monthwise_val' => $sell_monthwise_val,
                    'sell_daily_val' => $daily_sell_val,
                    'sell_daily_dates' => $dates_sell,
                    'sell_weekly_val' => $weekly_sell_val,

                    'exp_monthwise_val' => $expense_monthwise_val,
                    'exp_daily_val' => $daily_expense_val,
                    'exp_daily_dates' => $dates_expense,
                    'exp_weekly_val' => $weekly_expense_val,

                ]));

        }

    }
 }