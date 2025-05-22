<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use Illuminate\Http\Request;

class CmsPageController extends Controller
{
    
    public function getCmsPage(Request $request)
    {
        $cmsPage = CmsPage::whereNotNull('slug')->get();
        return response()->json(['cmsPage' => $cmsPage]);
    }
}
