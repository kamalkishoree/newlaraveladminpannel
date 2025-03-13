<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Wallet;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class WalletController extends Controller
{

  public function index(Request $request)
  {
      if ($request->ajax()) {
          $data = Wallet::orderBy('id', 'asc');
          
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
      return view('admin.wallet.index',compact('request'));
  }
  


	public function create()
	{

		return view('admin.wallet.create');


	}

	public function store(Request $request)
	{


		$rules = [
            'balance' => 'required|numeric|min:0',
            'pending_balance' => 'nullable|numeric|min:0',
            'withdrawn_balance' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:10',
            'wallet_type' => 'required|string|max:50',
            'is_active' => 'required|boolean',

        ];
        $messages = [
            'balance.required' => 'The balance field is required.',
            'balance.numeric' => 'The balance must be a number.',
            'balance.min' => 'The balance must be at least 0.',
            'pending_balance.numeric' => 'The pending balance must be a number.',
            'pending_balance.min' => 'The pending balance cannot be negative.',
            'withdrawn_balance.numeric' => 'The withdrawn balance must be a number.',
            'withdrawn_balance.min' => 'The withdrawn balance cannot be negative.',
            'currency.required' => 'The currency field is required.',
            'currency.string' => 'The currency must be a valid string.',
            'currency.max' => 'The currency code must not exceed 10 characters.',
            'wallet_type.required' => 'The wallet type field is required.',
            'wallet_type.string' => 'The wallet type must be a valid string.',
            'wallet_type.max' => 'The wallet type must not exceed 50 characters.',
        ];
          $this->validate($request, $rules, $messages);
          $request->merge(['user_id'=>1]);
          $input = request()->all();
    	try {
            $wallet = Wallet::create($input);
			Toastr::success(__('Wallet data Added Successfully'));
		    return redirect()->route('wallet.index');

		} catch (Exception $e) {
			Toastr::error(__('Failed to create Wallet data record.'));
		    return redirect()->route('wallet.index');
		}
	}

	public function edit($id)
	{
        $wallet = Wallet::find($id);
		return view('admin.wallet.edit',compact('wallet'));
	}

	public function update(Request $request, $id)
	{
        $wallet = Wallet::find($id);
        $rules = [
            'balance' => 'required|numeric|min:0',
            'pending_balance' => 'nullable|numeric|min:0',
            'withdrawn_balance' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:10',
            'wallet_type' => 'required|string|max:50',
            'is_active' => 'required|boolean',

        ];
        $messages = [
            'balance.required' => 'The balance field is required.',
            'balance.numeric' => 'The balance must be a number.',
            'balance.min' => 'The balance must be at least 0.',
            'pending_balance.numeric' => 'The pending balance must be a number.',
            'pending_balance.min' => 'The pending balance cannot be negative.',
            'withdrawn_balance.numeric' => 'The withdrawn balance must be a number.',
            'withdrawn_balance.min' => 'The withdrawn balance cannot be negative.',
            'currency.required' => 'The currency field is required.',
            'currency.string' => 'The currency must be a valid string.',
            'currency.max' => 'The currency code must not exceed 10 characters.',
            'wallet_type.required' => 'The wallet type field is required.',
            'wallet_type.string' => 'The wallet type must be a valid string.',
            'wallet_type.max' => 'The wallet type must not exceed 50 characters.',
        ];
        
           $this->validate($request, $rules, $messages);
           $input = request()->all();
    	try {
            $wallet->update($input);
			Toastr::success(__('Wallet data Updated Successfully'));
		    return redirect()->route('wallet.index');

		} catch (Exception $e) {
			Toastr::error(__('Failed to Update Wallet data record.'));
		    return redirect()->route('wallet.index');
		}
      
	}

	public function destroy(Request $request)
	{
        $id = request()->input('id');
        $Wallet = Wallet::find($id);
        try {
            Wallet::find($id)->delete();
            return back()->with(Toastr::error(__('Wallet deleted')));
        } catch (Exception $e) {
            $error_msg = Toastr::error(__('Failed to delete Wallet'));
            return redirect()->route('wallet.index')->with($error_msg);
        }
		 
	}

}
