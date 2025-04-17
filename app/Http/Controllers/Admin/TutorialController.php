<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tutorial;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TutorialController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Tutorial::orderBy('id', 'desc');
            if ($request->has('type') && !empty($request->type)) {
                $data->where('type', $request->type);
            }
            
            if ($request->has('is_active') && $request->is_active != '') {
                $data->where('is_active', $request->is_active);
            }
            
            $data = $data->get();
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('type', function($row) {
                    $typeClass = [
                        'video' => 'badge-primary',
                        'image' => 'badge-success',
                        'document' => 'badge-info'
                    ];
                    
                    return '<span class="badge ' . $typeClass[$row->type] . '">' . ucfirst($row->type) . '</span>';
                })
                ->addColumn('status', function($row) {
                    return $row->is_active 
                        ? '<span class="badge badge-success">Active</span>'
                        : '<span class="badge badge-danger">Inactive</span>';
                })
                ->addColumn('expiry_date', function($row) {
                    return $row->expiry_date ? $row->expiry_date->format('Y-m-d') : 'N/A';
                })
                ->addColumn('action', function($row) {
                    $html = '';
                    
                    $html .= '<button type="button" class="btn btn-info btn-sm view-tutorial" 
                        data-id="' . $row->id . '">
                        <i class="fe fe-eye"></i> View
                    </button>';
                    
                    $html .= ' <button type="button" class="btn btn-primary btn-sm edit-tutorial" 
                        data-id="' . $row->id . '">
                        <i class="fe fe-edit"></i> Edit
                    </button>';
                    
                    $html .= ' <button type="button" class="btn btn-danger btn-sm delete-tutorial" 
                        data-id="' . $row->id . '">
                        <i class="fe fe-trash"></i> Delete
                    </button>';
                    
                    return $html;
                })
                ->rawColumns(['type', 'status', 'action'])
                ->make(true);
        }
        
        return view('admin.tutorials.index');
    }

    public function create()
    {
        return view('admin.tutorials.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'url' => 'required|string|max:255',
            'type' => 'required|in:video,image,document',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'expiry_date' => 'nullable|date',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('thumbnail')) {
            $image = $request->file('thumbnail');
            $filename = Str::random(40) . '.' . $image->getClientOriginalExtension();
            $path = 'tutorials/' . date('Y/m');
            $imageUrl = Storage::disk('s3')->putFileAs($path, $image, $filename, 'public');
            $data['thumbnail'] = Storage::disk('s3')->url($imageUrl);
        }

        Tutorial::create($data);

        return redirect()->route('tutorial.index')
            ->with('success', 'Tutorial created successfully.');
    }

    public function edit($id)
    {
        $tutorial = Tutorial::findOrFail($id);
        return view('admin.tutorials.edit', compact('tutorial'));
    }

    public function update(Request $request, Tutorial $tutorial)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'url' => 'required|string|max:255',
            'type' => 'required|in:video,image,document',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'expiry_date' => 'nullable|date',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($tutorial->thumbnail) {
                $oldImagePath = str_replace(Storage::disk('s3')->url(''), '', $tutorial->thumbnail);
                Storage::disk('s3')->delete($oldImagePath);
            }

            $image = $request->file('thumbnail');
            $filename = Str::random(40) . '.' . $image->getClientOriginalExtension();
            $path = 'tutorials/' . date('Y/m');
            $imageUrl = Storage::disk('s3')->putFileAs($path, $image, $filename, 'public');
            $data['thumbnail'] = Storage::disk('s3')->url($imageUrl);
        }

        $tutorial->update($data);

        return redirect()->route('tutorial.index')
            ->with('success', 'Tutorial updated successfully.');
    }

    public function destroy(Tutorial $tutorial)
    {
        // Delete thumbnail from S3 if exists
        // if ($tutorial->thumbnail) {
        //     $imagePath = str_replace(Storage::disk('s3')->url(''), '', $tutorial->thumbnail);
        //     Storage::disk('s3')->delete($imagePath);
        // }
        
        $tutorial->delete();
        
        return response()->json(['message' => 'Tutorial deleted successfully']);
    }

    public function show($id)
    {
        $tutorial = Tutorial::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data' => $tutorial
        ]);
    }
} 