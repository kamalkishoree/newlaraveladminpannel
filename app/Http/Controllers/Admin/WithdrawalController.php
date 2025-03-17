<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class WithdrawalController extends Controller
{
    
public function index(Request $request)
{      
    if ($request->ajax()) {
        $data = Withdrawal::orderBy('id', 'asc');
        if ($request->has('start_date') && $request->has('end_date') && !empty($request->start_date) && !empty($request->end_date) ) {
            $data->whereBetween('created_at', [@$request->start_date, @$request->end_date]);
        }
        if($request->has('status') && !empty($request->status) ) {
            $data->where('status', $request->status);
        }
        
        $data = $data->get();  
        
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('user', function($row) {
                return ($row->getuser->name && $row->getuser->last_name ) ? $row->getuser->name.' '.$row->getuser->last_name : 'N/A';
            })
            ->addColumn('amount', function($row) {
                return number_format($row->amount, 2).' '.$row->currency;
            })
           
            ->addColumn('bank_account', function($row) {
                return $row->userBankAccount->account_number.'  ' . '<i class="fa fa-info-circle text-primary" style="cursor:pointer" 
                data-toggle="modal" data-target="#bankDetailModal" 
                data-account-number="'.$row->userBankAccount->account_number.'" 
                data-bank-name="'.$row->userBankAccount->bank_name.'" 
                data-branch="'.$row->userBankAccount->branch.'" 
                data-ifsc="'.$row->userBankAccount->ifsc_code.'"></i>';
               })
                 ->addColumn('status', function($row) {
          
                $html = $row->status == 'pending' ? '<span class="badge badge-warning">Pending</span>' : ($row->status == 'approved' ? '<span class="badge badge-success">Completed</span>' : '<span class="badge badge-danger">Rejected</span>');        
                return $html;
            })
            ->addColumn('action', function($row) {
                $html = '';
                if($row->status == 'pending') {
                $html .='<button type="button" class="custom-edit-btn change-status-withdrawal" data-status="approved" data-id="'.$row->id.'" data-action="'.route('withdrawal.status_update').'">
                                <i class="fe fe-check"></i>
                                '.__('Approve').'
                            </button>';
                
                $html .= ' '.'<button  type="button" class="custom-delete-btn change-status-withdrawal" data-status="rejected" data-id="'.$row->id.'" data-action="'.route('withdrawal.status_update').'">
                                <i class="fe fe-close"></i>
                                '.__('Reject').'
                            </button>';
                }
                elseif ($row->status != 'pending') {
                    $html .= '<button type="button" class="custom-warning-btn change-status-withdrawal" data-status="pending" data-id="'.$row->id.'" data-action="'.route('withdrawal.status_update').'">
                                <i class="fe fe-check"></i>
                                '.__('Pending').'
                            </button>';
                }
                return $html;
            })
            ->rawColumns(['action', 'status'])
            ->editColumn('created_at', '{{date("jS M Y", strtotime($created_at))}}')
            ->editColumn('updated_at', '{{date("jS M Y", strtotime($updated_at))}}')
            ->escapeColumns([])
            ->make(true);
    }
    return view('admin.withdrawal.index',compact('request'));
}


public function statusUpdate(Request $request)
{
    $withdrawal = Withdrawal::find($request->id);
    $withdrawal->status = $request->status;
    $withdrawal->save();
    return response()->json(['message' => 'Status updated successfully']);
}
}
