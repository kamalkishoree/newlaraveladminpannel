<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function getFaq(Request $request)
    {
        $faq = Faq::where('status', 'active')->get();
        return response()->json(['faq' => $faq]);
    }
}
