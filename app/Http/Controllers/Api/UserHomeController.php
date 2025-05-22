<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{Category,Brand,Banner, CmsPage, Faq, Tutorial};
use Illuminate\Http\Request;

class UserHomeController extends Controller
{
    public function HomepageToOLD(Request $request)
    {
        $data = [];
        $data['top_category'] = [];
        $data['new_category'] = [];
        $data['feature_category'] =[];
        $banner = Banner::whereNotNull('id')->get();
        $category = Category::where('is_top','!=',1)->where('is_new','!=',1)->where('is_feature','!=',1)->get();
        $brand = Brand::whereNotNull('id')->get();

        $top_category = Category::where('is_top',1)->get();
        $new_category = Category::where('is_new',1)->get();
        $feature_category = Category::where('is_feature',1)->get();

        $data['banner'] = $banner;
        $data['category'] = $category;
        $data['brand'] = $brand;

        return response()->json([
            'status' => 200,
            'data'  =>$data
        ]);
    }

    public function HomepageOLD(Request $request)
    {
       $categories = Category::with(['brands' => function ($query) {
                // $query->inRandomOrder();        
         }])->get();
         
        $data = [
            'banner' =>  Banner::whereNotNull('id')->get(),
            'brand' => Brand::whereNotNull('id')->paginate(15),
            'category' => $categories->filter(function ($item) {
                return !$item->is_top && !$item->is_new && !$item->is_feature;
            })->values(),
            'top_category' => $categories->filter(function ($item) {
                return $item->is_top;
            })->values(),
            'new_category' => $categories->filter(function ($item) {
                return $item->is_new;
            })->values(),
            'feature_category' => $categories->filter(function ($item) {
                return $item->is_feature;
            })->values(),
        ];
        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }

    public function Homepage(Request $request)
    {
        
       $categories = Category::get();
        $brands = Brand::hydrate(\DB::select(\DB::raw("select * from(select *, (row_number() over (partition by brands.category_id)) as row_ident from `brands`) as internal HAVING row_ident <= 5;")))
            ->groupBy('category_id');
        // dd($brands); 
        $categories->map(function ($c) use ($brands) {
            $c->setRelation('brands', $brands->get($c->id));
            return $c;
        });

        $faqs = Faq::where('status',1)->get();
        $cms = CmsPage::where('status',1)->get();

        $data = [
            'banner' =>  Banner::whereNotNull('id')->get(),
            'brand' => Brand::whereNotNull('id')->paginate(15),
            'category' => $categories->filter(function ($item) {
                return !$item->is_top && !$item->is_new && !$item->is_feature;
            })->values(),
            'top_category' => $categories->filter(function ($item) {
                return $item->is_top;
            })->values(),
            'new_category' => $categories->filter(function ($item) {
                return $item->is_new;
            })->values(),
            'feature_category' => $categories->filter(function ($item) {
                return $item->is_feature;
            })->values(),

            'tutorials' => Tutorial::where('is_active',1)->get(),
            'faqs' => $faqs,
            'cms' => $cms
        ];
        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }



}
