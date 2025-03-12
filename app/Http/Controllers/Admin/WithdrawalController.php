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
        
        if ($request->has('start_date') && $request->has('end_date') && !empty($request->start_date) && !empty($request->end_date)) {
            $data->whereBetween('created_at', [@$request->start_date, @$request->end_date]);
        }
        
        $data = $data->get();  
        
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('user', function($row) {
                return ($row->user->name && $row->user->last_name ) ? $row->user->name.' '.$row->user->last_name : 'N/A';
            })
            ->addColumn('balance', function($row) {
                return number_format($row->balance, 2).' '.$row->currency;
            })
            ->addColumn('pending_balance', function($row) {
                return number_format($row->pending_balance, 2).' '.$row->currency;
            })
            ->addColumn('withdrawn_balance', function($row) {
                return number_format($row->withdrawn_balance, 2).' '.$row->currency;
            })
            ->addColumn('wallet_type', function($row) {
                return ucfirst($row->wallet_type);
            })
            ->addColumn('status', function($row) {
                $checked = $row->is_active ? 'Checked' : '';
                return "
                    <input type='checkbox' id='status_$row->id' class='check' onclick='changeWalletStatus(event.target, $row->id);' $checked>
                    <label for='status_$row->id' class='checktoggle'>checkbox</label>
                ";
            })
            ->addColumn('action', function($row) {
                $edit = '<a href="'.route('wallet.edit', $row->id).'" class="custom-edit-btn mr-1">
                            <i class="fe fe-pencil"></i>
                            '.__('default.form.edit-button').'
                         </a>';
                $delete = '<button class="custom-delete-btn remove-wallet" data-id="'.$row->id.'" data-action="'.route('wallet.destroy').'">
                                <i class="fe fe-trash"></i>
                                '.__('default.form.delete-button').'
                            </button>';
                return $edit.' '.$delete;
            })
            ->rawColumns(['action', 'status'])
            ->editColumn('created_at', '{{date("jS M Y", strtotime($created_at))}}')
            ->editColumn('updated_at', '{{date("jS M Y", strtotime($updated_at))}}')
            ->escapeColumns([])
            ->make(true);
    }
    return view('admin.withdrawal.index');
}

}
