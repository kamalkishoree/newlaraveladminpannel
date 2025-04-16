<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Brand,Category};

class SearchController extends Controller
{
    public function searchBrand(Request $request)
    {
        $brands =[];
        $brands = Brand::select('id', 'name', 'description','image_url','category_id');
        if ($request->filled('keyword')) {
            $search = $request->keyword;
            $brands =  $brands->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                          ->orWhere('description', 'like', '%' . $search . '%')
                          ->orWhere('slug', 'like', '%' . $search . '%');
                });
             }

            if($request->filled('category_id'))
            {
                $category_id = $request->category_id;
                $brands = $brands->where('category_id',$category_id);
            }

         $brands = $brands->paginate(10);// Optional: Limit results for performance
        return response()->json([
            'status' => 200,
            'data' => $brands
        ]);
    }
    public function homeCategory(Request $request)
    {
        $categories = [];
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
