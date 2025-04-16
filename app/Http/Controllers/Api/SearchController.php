<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Brand,Category};

class SearchController extends Controller
{
    public function homeBrand(Request $request)
    {
        if ($request->filled('keyword')) {
            $search = $request->keyword;
        
            $brands = Brand::select('id', 'name', 'description','image_url','category_id')
                ->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                          ->orWhere('description', 'like', '%' . $search . '%')
                          ->orWhere('slug', 'like', '%' . $search . '%');
                })
                ->paginate(10) // Optional: Limit results for performance
                ->get();
            return response()->json([
                'status' => 200,
                'data' => $brands
            ]);
        }
    }
    public function homeCategory(Request $request)
    {
        if ($request->filled('keyword')) {
            $search = $request->keyword;
    
            $categories = Category::where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                          ->orWhere('slug', 'like', '%' . $search . '%');
                })
                ->select('id', 'name', 'description','image_url') // Optional: only select required fields
                ->paginate(10);
    
            return response()->json([
                'status' => 200,
                'data' => $categories
            ]);
        }
    
        return response()->json([
            'status' => 400,
            'message' => 'Keyword is required'
        ]);
    }
}
