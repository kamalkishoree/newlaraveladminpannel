<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{Brand, Campaign, Conversion, Ticket, User, Withdrawal};

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function dashboard(Request $request)
    {
        $dateRange = $request->get('date_range');
        $startDate = null;
        $endDate = null;

        // Only set dates if a date range is selected
        if ($dateRange) {
            $endDate = Carbon::now();

            switch ($dateRange) {
                case 'today':
                    $startDate = Carbon::today();
                    break;
                case 'last_7_days':
                    $startDate = Carbon::now()->subDays(7);
                    break;
                case 'last_30_days':
                    $startDate = Carbon::now()->subDays(30);
                    break;
                case 'this_month':
                    $startDate = Carbon::now()->startOfMonth();
                    break;
                case 'last_month':
                    $startDate = Carbon::now()->subMonth()->startOfMonth();
                    $endDate = Carbon::now()->subMonth()->endOfMonth();
                    break;
                case 'custom':
                    $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date'))->startOfDay() : null;
                    $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date'))->endOfDay() : Carbon::now();
                    // If no start date is set for custom range, use the end date minus 7 days
                    if (!$startDate && $endDate) {
                        $startDate = $endDate->copy()->subDays(7)->startOfDay();
                    }
                    break;
            }
        }
        // Query builder for users
        $userQuery = User::query();
        $recent_signup_user = (clone $userQuery)
            ->where('created_at', '>=', Carbon::now()->subDays(3))
            ->get();
        if ($startDate && $endDate) {
            $userQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        $user = $userQuery->get();
        $totalUsers = $userQuery->count(); // Use count() instead of get()
        $activeUsersQuery = User::query();
        if ($startDate && $endDate) {
            $activeUsersQuery->whereBetween('updated_at', [$startDate, $endDate]);
        } else {
            $activeUsersQuery->whereBetween('updated_at', [Carbon::now()->subDay(), Carbon::now()]);
        }
        $activeUsers = $activeUsersQuery->count();
        // Get inactive users
        $inactiveUsersQuery = User::query();
        if ($startDate && $endDate) {
            $inactiveUsersQuery->where('updated_at', '<', $startDate);
        } else {
            $inactiveUsersQuery->where('updated_at', '<', Carbon::now()->subDays(7));
        }
        $inactiveUsers = $inactiveUsersQuery->count();

        $activeUserPercentage = $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 2) : 0;
        $recent_signup_user_count = (clone $userQuery)->count();

        // Query builder for conversion
        $conversionQuery = Conversion::query();
        if ($startDate && $endDate) {
            $conversionQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        $number_of_conversion = (clone $conversionQuery)->count();
        $all_over_conversion = (clone $conversionQuery)->sum('payout');
        $reject_conversion = (clone $conversionQuery)->where('conversion_status', 'rejected')->count();
        $pending_conversion = (clone $conversionQuery)->where('conversion_status', 'pending')->count();
        $approved_conversion = (clone $conversionQuery)->where('conversion_status', 'approved')->count();
        $revenue_conversion = (clone $conversionQuery)->where('conversion_status', 'approved')->sum('payout');
        // Cashback builder for conversion


        //withdrawal builder for conversion
        $withdrawalQuery = Withdrawal::query();
        if ($startDate && $endDate) {
            $withdrawalQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        $withdrawal_total = (clone $withdrawalQuery)->count();
        $withdrawal_pending = (clone $withdrawalQuery)->where('status', 'pending')->count();
        $withdrawal_approved = (clone $withdrawalQuery)->where('status', 'approved')->count();
        $withdrawal_rejected = (clone $withdrawalQuery)->where('status', 'rejected')->count();
        $total_payout = (clone $withdrawalQuery)->where('status', 'approved')->sum('amount');


        //Brand builder for conversion
        $brandQuery = Brand::query();
        if ($startDate && $endDate) {
            $brandQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        $brand_total = (clone $brandQuery)->count();
        $brand_pending = (clone $brandQuery)->where('status', 'pending')->count();
        $brand_approved = (clone $brandQuery)->where('status', 'approved')->count();
        $brand_rejected = (clone $brandQuery)->where('status', 'rejected')->count();

        //Ticket builder for conversion
        $ticketQuery = Ticket::query();
        if ($startDate && $endDate) {
            $brandQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        $ticket_total = (clone $ticketQuery)->count();
        $ticket_pending = (clone $ticketQuery)->where('status', 'pending')->count();
        $ticket_approved = (clone $ticketQuery)->where('status', 'approved')->count();
        $ticket_rejected = (clone $ticketQuery)->where('status', 'rejected')->count();

        // Query builder for campaigns
        $campaignQuery = Campaign::query();
        if ($startDate && $endDate) {
            $campaignQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        $campaign_all = $campaignQuery->get();
        $most_clicked_brand = NULL;
        $most_clicked = (clone $campaignQuery)
            ->select('brand_id', DB::raw('COUNT(*) as total_clicks'))
            ->groupBy('brand_id')
            ->orderByDesc('total_clicks')
            ->first();
        if($most_clicked)
        {
                $most_clicked_brand =  $most_clicked->brand;
        }
        $series = [
            [
                'name' => 'Active Users',
                'y' => $activeUsers,
                'color' => '#28a745'
            ],
            [
                'name' => 'Inactive Users',
                'y' => $inactiveUsers,
                'color' => '#dc3545'
            ]
        ];

        // Monthly clicks query
        $monthlyClicksQuery = Campaign::query();

        if ($startDate && $endDate) {
            $monthlyClicksQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $monthlyClicks = $monthlyClicksQuery
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total_clicks')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months = [];
        $clicks = [];

        foreach ($monthlyClicks as $click) {
            $months[] = Carbon::createFromFormat('m', $click->month)->format('F');
            $clicks[] = $click->total_clicks;
        }
        // Get campaign chart data
        $campaign = $this->showCampaignChart($startDate, $endDate);
        $campaign_totalSeries = $campaign['totalSeries'];
        $campaign_pendingSeries = $campaign['pendingSeries'];
        $campaign_approvedSeries = $campaign['approvedSeries'];
        $campaign_rejectedSeries = $campaign['rejectedSeries'];


        $conversionChartData = $this->showConversionChart($startDate, $endDate);
        $conversion_totalSeries = $conversionChartData['totalSeries'];
        $conversion_pendingSeries = $conversionChartData['pendingSeries'];
        $conversion_approvedSeries = $conversionChartData['approvedSeries'];
        $conversion_rejectedSeries = $conversionChartData['rejectedSeries'];

        $ticketChartData = $this->showTicketChart($startDate, $endDate);
        $ticket_totalSeries = $ticketChartData['totalSeries'];
        $ticket_pendingSeries = $ticketChartData['pendingSeries'];
        $ticket_approvedSeries = $ticketChartData['approvedSeries'];
        $ticket_rejectedSeries = $ticketChartData['rejectedSeries'];


        return view('admin.dashboard', compact(
            'user',
            'series',
            'campaign',
            'campaign_all',
            'dateRange',
            'startDate',
            'endDate',
            'number_of_conversion',
            'withdrawal_approved',
            'withdrawal_pending',
            'withdrawal_rejected',
            'withdrawal_total',
            'total_payout',
            'activeUserPercentage',
            'inactiveUsers',
            'recent_signup_user',
            'recent_signup_user_count',
            'reject_conversion',
            'pending_conversion',
            'approved_conversion',
            'most_clicked_brand',
            'brand_total',
            'brand_pending',
            'brand_approved',
            'brand_rejected',
            'ticket_total',
            'ticket_pending',
            'ticket_approved',
            'ticket_rejected',
            'revenue_conversion',

            'campaign_totalSeries',
            'campaign_pendingSeries',
            'campaign_approvedSeries',
            'campaign_rejectedSeries',

            'conversion_totalSeries',
            'conversion_pendingSeries',
            'conversion_approvedSeries',
            'conversion_rejectedSeries',

            'ticket_totalSeries',
            'ticket_pendingSeries',
            'ticket_approvedSeries',
            'ticket_rejectedSeries'
        ));
    }
    public function showCampaignChart($startDate = null, $endDate = null)
    {
        // Get total campaigns
        $totalQuery = DB::table('campaigns')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'));

        // Get pending campaigns
        $pendingQuery = DB::table('campaigns')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->where('conversion_status', 2);

        // Get approved campaigns
        $approvedQuery = DB::table('campaigns')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->where('conversion_status',2);

        // Get rejected campaigns
        $rejectedQuery = DB::table('campaigns')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->where('conversion_status', 2);

        if ($startDate && $endDate) {
            $totalQuery->whereBetween('created_at', [$startDate, $endDate]);
            $pendingQuery->whereBetween('created_at', [$startDate, $endDate]);
            $approvedQuery->whereBetween('created_at', [$startDate, $endDate]);
            $rejectedQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Get data for each status
        $totalData = $totalQuery->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month', 'asc')
            ->get();

        $pendingData = $pendingQuery->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month', 'asc')
            ->get();

        $approvedData = $approvedQuery->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month', 'asc')
            ->get();

        $rejectedData = $rejectedQuery->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month', 'asc')
            ->get();

        // Initialize arrays for all months
        $months = range(1, 12);
        $totalSeries = array_fill_keys($months, 0);
        $pendingSeries = array_fill_keys($months, 0);
        $approvedSeries = array_fill_keys($months, 0);
        $rejectedSeries = array_fill_keys($months, 0);

        // Fill in the actual data
        foreach ($totalData as $data) {
            $totalSeries[$data->month] = $data->count;
        }

        foreach ($pendingData as $data) {
            $pendingSeries[$data->month] = $data->count;
        }

        foreach ($approvedData as $data) {
            $approvedSeries[$data->month] = $data->count;
        }

        foreach ($rejectedData as $data) {
            $rejectedSeries[$data->month] = $data->count;
        }

        return [
            'totalSeries' => array_values($totalSeries),
            'pendingSeries' => array_values($pendingSeries),
            'approvedSeries' => array_values($approvedSeries),
            'rejectedSeries' => array_values($rejectedSeries)
        ];
    }

    public function showConversionChart($startDate = null, $endDate = null)
    {
        // Get total conversions
        $totalQuery = DB::table('conversions')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'));

        // Get pending conversions
        $pendingQuery = DB::table('conversions')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->where('conversion_status', 'pending');

        // Get approved conversions
        $approvedQuery = DB::table('conversions')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->where('conversion_status', 'approved');

        // Get rejected conversions
        $rejectedQuery = DB::table('conversions')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->where('conversion_status', 'rejected');

        if ($startDate && $endDate) {
            $totalQuery->whereBetween('created_at', [$startDate, $endDate]);
            $pendingQuery->whereBetween('created_at', [$startDate, $endDate]);
            $approvedQuery->whereBetween('created_at', [$startDate, $endDate]);
            $rejectedQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Get data for each status
        $totalData = $totalQuery->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month', 'asc')
            ->get();

        $pendingData = $pendingQuery->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month', 'asc')
            ->get();

        $approvedData = $approvedQuery->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month', 'asc')
            ->get();

        $rejectedData = $rejectedQuery->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month', 'asc')
            ->get();

        // Initialize arrays for all months
        $months = range(1, 12);
        $totalSeries = array_fill_keys($months, 0);
        $pendingSeries = array_fill_keys($months, 0);
        $approvedSeries = array_fill_keys($months, 0);
        $rejectedSeries = array_fill_keys($months, 0);

        // Fill in the actual data
        foreach ($totalData as $data) {
            $totalSeries[$data->month] = $data->count;
        }

        foreach ($pendingData as $data) {
            $pendingSeries[$data->month] = $data->count;
        }

        foreach ($approvedData as $data) {
            $approvedSeries[$data->month] = $data->count;
        }

        foreach ($rejectedData as $data) {
            $rejectedSeries[$data->month] = $data->count;
        }

        return [
            'totalSeries' => array_values($totalSeries),
            'pendingSeries' => array_values($pendingSeries),
            'approvedSeries' => array_values($approvedSeries),
            'rejectedSeries' => array_values($rejectedSeries)
        ];
    }

    public function showWithdrawlChart($startDate = null, $endDate = null)
    {
        // Get total campaigns
        $totalQuery = DB::table('withdrawls')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'));

        // Get pending campaigns
        $pendingQuery = DB::table('withdrawls')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->where('status', 'pending');

        // Get approved campaigns
        $approvedQuery = DB::table('withdrawls')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->where('status', 'approved');

        // Get rejected campaigns
        $rejectedQuery = DB::table('withdrawls')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->where('status', 'rejected');

        if ($startDate && $endDate) {
            $totalQuery->whereBetween('created_at', [$startDate, $endDate]);
            $pendingQuery->whereBetween('created_at', [$startDate, $endDate]);
            $approvedQuery->whereBetween('created_at', [$startDate, $endDate]);
            $rejectedQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Get data for each status
        $totalData = $totalQuery->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month', 'asc')
            ->get();

        $pendingData = $pendingQuery->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month', 'asc')
            ->get();

        $approvedData = $approvedQuery->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month', 'asc')
            ->get();

        $rejectedData = $rejectedQuery->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month', 'asc')
            ->get();

        // Initialize arrays for all months
        $months = range(1, 12);
        $totalSeries = array_fill_keys($months, 0);
        $pendingSeries = array_fill_keys($months, 0);
        $approvedSeries = array_fill_keys($months, 0);
        $rejectedSeries = array_fill_keys($months, 0);

        // Fill in the actual data
        foreach ($totalData as $data) {
            $totalSeries[$data->month] = $data->count;
        }

        foreach ($pendingData as $data) {
            $pendingSeries[$data->month] = $data->count;
        }

        foreach ($approvedData as $data) {
            $approvedSeries[$data->month] = $data->count;
        }

        foreach ($rejectedData as $data) {
            $rejectedSeries[$data->month] = $data->count;
        }

        return [
            'totalSeries' => array_values($totalSeries),
            'pendingSeries' => array_values($pendingSeries),
            'approvedSeries' => array_values($approvedSeries),
            'rejectedSeries' => array_values($rejectedSeries)
        ];
    }




    public function showTicketChart($startDate = null, $endDate = null)
    {
        // Get total tickets
        $totalQuery = DB::table('tickets')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'));

        // Get pending tickets
        $pendingQuery = DB::table('tickets')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->where('status', 'pending');

        // Get approved tickets
        $approvedQuery = DB::table('tickets')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->where('status', 'approved');

        // Get rejected tickets
        $rejectedQuery = DB::table('tickets')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->where('status', 'rejected');

        if ($startDate && $endDate) {
            $totalQuery->whereBetween('created_at', [$startDate, $endDate]);
            $pendingQuery->whereBetween('created_at', [$startDate, $endDate]);
            $approvedQuery->whereBetween('created_at', [$startDate, $endDate]);
            $rejectedQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Get data for each status
        $totalData = $totalQuery->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month', 'asc')
            ->get();

        $pendingData = $pendingQuery->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month', 'asc')
            ->get();

        $approvedData = $approvedQuery->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month', 'asc')
            ->get();

        $rejectedData = $rejectedQuery->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month', 'asc')
            ->get();

        // Initialize arrays for all months
        $months = range(1, 12);
        $totalSeries = array_fill_keys($months, 0);
        $pendingSeries = array_fill_keys($months, 0);
        $approvedSeries = array_fill_keys($months, 0);
        $rejectedSeries = array_fill_keys($months, 0);

        // Fill in the actual data
        foreach ($totalData as $data) {
            $totalSeries[$data->month] = $data->count;
        }

        foreach ($pendingData as $data) {
            $pendingSeries[$data->month] = $data->count;
        }

        foreach ($approvedData as $data) {
            $approvedSeries[$data->month] = $data->count;
        }

        foreach ($rejectedData as $data) {
            $rejectedSeries[$data->month] = $data->count;
        }

        return [
            'totalSeries' => array_values($totalSeries),
            'pendingSeries' => array_values($pendingSeries),
            'approvedSeries' => array_values($approvedSeries),
            'rejectedSeries' => array_values($rejectedSeries)
        ];
    }
}
