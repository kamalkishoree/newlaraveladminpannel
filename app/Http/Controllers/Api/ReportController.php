<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function userConversionReport(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            // Get the authenticated user's ID
            $userId = $request->user()->id;

            $query = Conversion::where('user_id', $userId) // Filter by authenticated user's ID
                ->with(['campaign']);

            if ($request->has('campaign_id')) {
                $query->where('campaign_id', $request->campaign_id);
            }

            // Date range filtering
            if ($request->has('start_date')) {
                $startDate = $request->start_date . ' 00:00:00';
                $query->where('conversion_datetime', '>=', $startDate);
            }

            if ($request->has('end_date')) {
                $endDate = $request->end_date . ' 23:59:59';
                $query->where('conversion_datetime', '<=', $endDate);
            }
            $conversions = $query->paginate($perPage);
            $totalCashback = $query->sum('payout');
            $incentive = 0;

            return response()->json([
                'status' => 'success',
                'data' => $conversions,
                'total_cashback' => $totalCashback,
                'incentive' => $incentive,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch conversion report: ' . $e->getMessage()
            ], 500);
        }
    }
}
