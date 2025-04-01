<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Referral;
use Yajra\DataTables\Facades\DataTables;

class ReferralController extends Controller
{

    public function index(Request $request)
	{
        
		if ($request->ajax()) {
            $data = Referral::orderBy('id', 'asc');
            if ($request->has('start_date') && $request->has('end_date') && !empty($request->start_date) && !empty($request->end_date)) {
                $data->whereBetween('created_at', [@$request->start_date, @$request->end_date]);
            }
            // Get data before passing to DataTables
            $data = $data->get();  
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $approve = "<a  data-id='".$row->id."' href='javascript:void(0)' onclick='changeStatus(this, $row->id);'  data-value='completed' title='Approve' data-action='".route('referral.change-status')."'>
                               <i style='color:rgb(27, 90, 14);' class='fa-solid fa-check'></i>
                               </a>";
                            $reject = "<a   href='javascript:void(0)'   onclick='changeStatus(this, $row->id);'  data-value='rejected' title='Reject' data-id='".$row->id."' data-action='".route('referral.change-status')."'>
                                <i style='color:rgb(216, 12, 12);' class='fa-solid fa-x'></i>
                                </a>";
                    $action = $approve.' &nbsp&nbsp&nbsp&nbsp '.$reject;
                    return $action;
                })

                ->addColumn('referral_by', function($row){
                    return $row->referrer->name;
                })
                ->addColumn('refered_to', function($row){
                    return $row->referred->name;
                })
                ->addColumn('reward_amount', function($row){
                    return $row->reward_amount;
                })
                ->addColumn('status', function($row){
                    $html = $row->status == 'pending' ? '<span class="badge badge-warning">Pending</span>' : ($row->status == 'completed' ? '<span class="badge badge-success">Completed</span>' : '<span class="badge badge-danger">Rejected</span>');        
                    return $html;
                })
                ->rawColumns(['action', 'status'])
                ->editColumn('created_at', '{{date("jS M Y", strtotime($created_at))}}')
	            ->editColumn('updated_at', '{{date("jS M Y", strtotime($updated_at))}}')
	            ->escapeColumns([])
                ->make(true);
        }
        return view('admin.referral.index',compact('request'));
	}

       public function changeStatus(Request $request)
       {
            $referral = Referral::find($request->id);
            $referral->status = $request->status;
            if($request->status == 'completed'){
              $referral->referrer->referral_balance +=$referral->reward_amount;
              $referral->referrer->save();
            }else{
                
            }   
            $referral->save();
            $event = $request->status == 'completed'?1:0;
            
            return response()->json(['success' => true, 'message' => 'Referral status updated to ' .$request->status.' successfully','event'=>$event]);
       }


}
