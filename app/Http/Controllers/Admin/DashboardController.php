<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $user = User::get();
        $campaign_all = Campaign::get();
        $activeUsers = User::whereDate('updated_at', '>=', Carbon::now()->toDateString())->count();
        $inactiveUsers = User::where('updated_at', '<', Carbon::now()->subDays(2))->count();
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
           
        
         $currentYear = Carbon::now()->year;
         $monthlyClicks = Campaign::selectRaw('MONTH(created_at) as month, COUNT(*) as total_clicks')
                ->whereYear('created_at', $currentYear) // Filter by current year
                ->groupBy('month') // Group by month
                ->orderBy('month') // Ensure the months are ordered correctly
                ->get();

            // Prepare the data for Highcharts (labels and series)
            $months = [];
            $clicks = [];


      
            foreach ($monthlyClicks as $click) {
                $months[] = Carbon::createFromFormat('m', $click->month)->format('F'); // Month name (e.g., January, February)
                $clicks[] = $click->total_clicks; // Total clicks in that month
            }

            $campaign = $this->showCampaignChart();

  
       return view('admin.dashboard',compact('user','series','campaign','campaign_all'));
    }



     public function showCampaignChart(){

        $clicksData = DB::table('campaigns')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as clicks_count'))
            ->groupBy(DB::raw('MONTH(created_at)'))->orderBy('month','asc')
            ->get();

            // Format data for chart
           $data =   $clicksData->pluck('clicks_count','month')->toArray();
           return $data;
    }
}