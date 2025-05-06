<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AffilateIntegration;
use App\Models\Campaign;
use App\Models\Conversion;
use App\Services\TrackierService;
use Aws\Api\ApiProvider;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ConversionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Conversion::orderBy('id', 'desc');
            
            if ($request->has('start_date') && $request->has('end_date') && !empty($request->start_date) && !empty($request->end_date)) {
                $data->whereBetween('created_at', [$request->start_date, $request->end_date]);
            }
            
            $data = $data->get();
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('campaign', function($row) {
                    return isset($row->campaign) ? (isset($row->campaign->brand)?$row->campaign->brand->name: 'N/A'): 'N/A';
                })
                ->addColumn('user', function($row) {
                    return $row->user ? $row->user->name : 'N/A';
                })
                ->addColumn('status', function($row) {
                    $statusClass = [
                        'pending' => 'badge-warning',
                        'approved' => 'badge-success',
                        'rejected' => 'badge-danger'
                    ];
                    
                    return '<span class="badge ' . ($statusClass[$row->conversion_status] ?? 'badge-secondary') . '">' . ucfirst($row->conversion_status) . '</span>';
                })
                ->addColumn('payout', function($row) {
                    return number_format($row->payout, 2) . ' ' . ($row->currency ?? 'USD');
                })
                ->addColumn('action', function($row) {
                    $html = '<button type="button" class="btn btn-info btn-sm view-conversion" 
                        data-id="' . $row->id . '">
                        <i class="fe fe-eye"></i> View
                    </button>';
                    
                    $html .= ' <button type="button" class="btn btn-danger btn-sm delete-conversion" 
                        data-id="' . $row->id . '">
                        <i class="fe fe-trash"></i> Delete
                    </button>';
                    
                    return $html;
                })
                ->editColumn('created_at', '{{date("jS M Y", strtotime($created_at))}}')
                ->editColumn('updated_at', '{{date("jS M Y", strtotime($updated_at))}}')
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        
        return view('admin.conversions.index', compact('request'));
    }

    public function view(Request $request)
    {
        $campaign = Campaign::find($request->id);
        if($campaign) {
            $affiliate_url = $campaign->brand->target_url;
            if(str_contains($affiliate_url,'vcommission')) {
                $conversion = Conversion::where('unique_source_id', $request->unique_source_id)->first();
                if($conversion) {
                    return response()->json(['status' => 'success', 'conversion' => $conversion]);
                } else {
                    return response()->json(['status' => 'success', 'conversion' => []]);
                }
            }
        }
    }

    public function destroy($id)
    {
        $conversion = Conversion::findOrFail($id);
        $conversion->delete();
        return response()->json(['message' => 'Conversion deleted successfully']);
    }

    public function show($id)
    {
        $conversion = Conversion::with(['user', 'campaign.brand'])->findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data' => $conversion
        ]);
    }


    public function viewMain(Request $request){
      $campaign =  Campaign::find($request->id);
      if($campaign)
      {

         $affiliate_url  = $campaign->brand->target_url;
         if(str_contains($affiliate_url,'vcommission'))
         {

           $conversion = Conversion::where('unique_source_id',$request->unique_source_id)->first();
           if($conversion)
           {
              return response()->json(['status'=>'success','conversion'=>$conversion]);
           }
           else{
              return response()->json(['status'=>'success','conversion'=>[]]);
           }
           // else{
           //    $conversion = (new TrackierService())->getConversions($campaign);
           //    $conversion = isset($conversion['conversions'][0])?$conversion['conversions'][0]:[];
           //    if(count($conversion)>0)
           //    {
           //       $filteredArray = [
           //          'user_id' => $campaign->user_id ?? null,
           //          'click_id' => $conversion['click_id'] ?? null,
           //          'unique_source_id' => $conversion['source'] ?? null,
           //          'method' => $conversion['method'] ?? null,
           //          'sale' => $conversion['sale'] ?? null,
           //          'p1' => $conversion['p1'] ?? null,
           //          'p2' => $conversion['p2'] ?? null,
           //          'p3' => $conversion['p3'] ?? null,
           //          'p4' => $conversion['p4'] ?? null,
           //          'p5' => $conversion['p5'] ?? null,
           //          'sub1' => $conversion['sub1'] ?? null,
           //          'txn_id' => $conversion['txn_id'] ?? null,
           //          'note' => $conversion['note'] ?? null,
           //          'currency' => $conversion['currency'] ?? null,
           //          'payout' => $conversion['payout'] ?? null,
           //          'brand' => $conversion['brand'] ?? null,
           //          'status' => $conversion['status'] ?? null,
           //          'campaign_id' => $conversion['campaign_id'] ?? null,
           //          'campaign_name' => $conversion['campaign_name'] ?? null,
           //      ];

           //       $conversion_data =  Conversion::create($filteredArray);
           //      if($conversion['status'] == 'approved')

           //      {

           //      }

                 
           //       return response()->json(['status'=>'success','conversion'=>$conversion_data]);
           //    }
           //    return response()->json(['status'=>'success','conversion'=>[]]);
           // }
           
         }
      }
   }
}