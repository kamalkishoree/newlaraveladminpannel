<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Ticket::orderBy('id', 'desc');
            
            if ($request->has('start_date') && $request->has('end_date') && !empty($request->start_date) && !empty($request->end_date)) {
                $data->whereBetween('created_at', [$request->start_date, $request->end_date]);
            }
            
            if ($request->has('status') && !empty($request->status)) {
                $data->where('status', $request->status);
            }
            
            $data = $data->get();
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('user', function($row) {
                    return $row->user ? $row->user->name . ' ' . $row->user->last_name : 'N/A';
                })
                ->addColumn('order_amount', function($row) {
                    return number_format($row->order_amount, 2);
                })
                ->addColumn('status', function($row) {
                    $statusClass = [
                        'inactive' => 'badge-secondary',
                        'opened' => 'badge-warning',
                        'approved' => 'badge-success',
                        'closed' => 'badge-danger'
                    ];
                    
                    return '<span class="badge ' . $statusClass[$row->status] . '">' . ucfirst($row->status) . '</span>';
                })

                ->addColumn('image_url', function($row) {
                    return $row->image_url;
                })
                ->addColumn('action', function($row) {
                    $html = '';
                    
                    if ($row->status == 'opened') {
                        $html .= '<button type="button" class="btn btn-success btn-sm change-ticket-status" 
                            data-status="approved" data-id="' . $row->id . '">
                            <i class="fe fe-check"></i> Approve
                        </button>';
                        
                        $html .= ' <button type="button" class="btn btn-danger btn-sm change-ticket-status" 
                            data-status="closed" data-id="' . $row->id . '">
                            <i class="fe fe-x"></i> Close
                        </button>';
                    }
                    
                    $html .= ' <button type="button" class="btn btn-info btn-sm view-ticket" 
                        data-id="' . $row->id . '">
                        <i class="fe fe-eye"></i> View
                    </button>';
                    
                    $html .= ' <button type="button" class="btn btn-danger btn-sm delete-ticket" 
                        data-id="' . $row->id . '">
                        <i class="fe fe-trash"></i> Delete
                    </button>';
                    
                    return $html;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        
        return view('admin.tickets.index', compact('request'));
    }

    public function statusUpdate(Request $request)
    {
        $ticket = Ticket::findOrFail($request->id);
        $ticket->status = $request->status;
        $ticket->save();
        
        return response()->json(['message' => 'Ticket status updated successfully']);
    }

    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();
        return response()->json(['message' => 'Ticket deleted successfully']);
    }
}
