<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Faq::orderBy('id', 'desc');
            
            if ($request->has('status') && !empty($request->status)) {
                $data->where('status', $request->status);
            }
            
            $data = $data->get();
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row) {
                    $statusClass = [
                        'inactive' => 'badge-secondary',
                        'active' => 'badge-success'
                    ];
                    
                    return '<span class="badge ' . $statusClass[$row->status] . '">' . ucfirst($row->status) . '</span>';
                })
                ->addColumn('action', function($row) {
                    $html = '';
                    
                    if ($row->status == 'inactive') {
                        $html .= '<button type="button" class="btn btn-success btn-sm change-faq-status" 
                            data-status="active" data-id="' . $row->id . '">
                            <i class="fe fe-check"></i> Activate
                        </button>';
                    } else {
                        $html .= '<button type="button" class="btn btn-warning btn-sm change-faq-status" 
                            data-status="inactive" data-id="' . $row->id . '">
                            <i class="fe fe-x"></i> Deactivate
                        </button>';
                    }
                    
                    $html .= ' <button type="button" class="btn btn-info btn-sm edit-faq" 
                        data-id="' . $row->id . '">
                        <i class="fe fe-edit"></i> Edit
                    </button>';
                    
                    $html .= ' <button type="button" class="btn btn-danger btn-sm delete-faq" 
                        data-id="' . $row->id . '">
                        <i class="fe fe-trash"></i> Delete
                    </button>';
                    
                    return $html;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        
        return view('admin.faqs.index', compact('request'));
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'question' => 'required',
            'answer' => 'required',
            'status' => 'required|in:active,inactive'
        ]);

        Faq::create($request->all());
        return response()->json(['message' => 'FAQ created successfully']);
    }

    public function edit($id)
    {
        $faq = Faq::findOrFail($id);
        return response()->json($faq);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'question' => 'required',
            'answer' => 'required',
            'status' => 'required|in:active,inactive'
        ]);

        $faq = Faq::findOrFail($id);
        $faq->update($request->all());
        return response()->json(['message' => 'FAQ updated successfully']);
    }

    public function statusUpdate(Request $request)
    {
        $faq = Faq::findOrFail($request->id);
        $faq->status = $request->status;
        $faq->save();
        
        return response()->json(['message' => 'FAQ status updated successfully']);
    }

    public function destroy(Request $request)
    {
        $faq = Faq::findOrFail($request->id);
        if($faq){   
            $faq->delete(); 
            return response()->json(['message' => 'FAQ deleted successfully']);
        }else{
            return response()->json(['message' => 'FAQ not found']);
        }
    }
} 