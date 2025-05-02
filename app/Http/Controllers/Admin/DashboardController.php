<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{Campaign, Conversion, User, Withdrawal};

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
        $reject_conversion = (clone $conversionQuery)->where('status','rejected')->count();
        $pending_conversion = (clone $conversionQuery)->where('status','pending')->count();
        $approved_conversion = (clone $conversionQuery)->where('status','approved')->count();
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

        // Query builder for campaigns
        $campaignQuery = Campaign::query();
        if ($startDate && $endDate) {
            $campaignQuery->whereBetween('created_at', [$startDate, $endDate]);
        }
        $campaign_all = $campaignQuery->get();

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
            'recent_signup_user',
            'recent_signup_user_count'
        ));
    }
    public function showCampaignChart($startDate = null, $endDate = null)
    {
        $query = DB::table('campaigns')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as clicks_count'));

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $clicksData = $query->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month', 'asc')
            ->get();

        return $clicksData->pluck('clicks_count', 'month')->toArray();
    }
}
