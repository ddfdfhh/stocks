<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        $user = auth()->user();
     
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
        $data['top_five_orders_paid'] = \DB::table('create_order')->wherePaidStatus('paid')->orderBy('created_at', 'DESC')->take(5)->get();
        /**Leads */
        $data['top_five_leads'] = \DB::table('leads')->orderBy('created_at', 'DESC')->take(5)->get();
        $data['top_five_converted_leads'] = \DB::table('leads')->whereStatus('Converted')->orderBy('created_at', 'DESC')->take(5)->get();
        $data['total_leads_entered'] = \DB::table('leads')->count();
        $data['total_leads_failed'] = \DB::table('leads')->whereStatus('Failed')->orWhere('status', 'Cancelled')->count();
        $data['today_leads'] = \DB::table('leads')->whereDay('created_at', '=', Carbon::now()->day)->count();
        $data['total_leads_success'] = \DB::table('leads')->whereStatus('Converted')->count();
        $data['today_leads_success'] = \DB::table('leads')->whereStatus('Converted')->whereDay('created_at', '=', Carbon::now()->day)->count();


        $data['total_customers'] = \DB::table('customer')->count();
        /**sell** */
        $data['total_sell'] = \DB::table('company_ledger')->whereMode('Income')->sum('amount');
        $data['today_sell'] = \DB::table('company_ledger')->whereMode('Income')->whereDate('created_at', Carbon::today()->toDateString())->sum('amount');
        $data['weekly_sell'] = \DB::table('company_ledger')->whereMode('Income')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('amount');
        $data['monthly_sell'] = \DB::table('company_ledger')->whereMode('Income')->whereMonth('created_at', Carbon::now()->month)->sum('amount');

        /**Expense** */
        $data['total_expense'] = \DB::table('company_ledger')->whereMode('Spent')->sum('amount');
        $data['today_expense'] = \DB::table('company_ledger')->whereMode('Spent')->whereDate('created_at', Carbon::today()->toDateString())->sum('amount');
        $data['weekly_expense'] = \DB::table('company_ledger')->whereMode('Spent')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('amount');
        $data['monthly_expense'] = \DB::table('company_ledger')->whereMode('Spent')->whereMonth('created_at', Carbon::now()->month)->sum('amount');

          $data['total_orders'] = \DB::table('create_order')->count();
          $data['today_orders'] = \DB::table('create_order')->whereDate('created_at', Carbon::today()->toDateString())->count();
          $data['total_order_income'] = \DB::table('create_order')->sum('paid_amount');
          $data['today_order_income'] = \DB::table('create_order')->whereDate('created_at', Carbon::today()->toDateString())->sum('paid_amount');
         $data['total_products_count'] = \DB::table('product')->count();
         $data['raw_meterials_count'] = \DB::table('input_materials')->count();

        $data['income']= $data['total_sell']-$data['total_expense'];
//dd($data);
        return view('admin.dashboard', with($data));
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
