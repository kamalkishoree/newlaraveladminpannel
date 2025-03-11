<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversionController extends Controller
{
    public function myConversion(Request $request)
    {
        $user = Auth::user();
        $conversions = Conversion::where('user_id', $user->id)->get();
        return response()->json(['conversions' => $conversions]);
    }
}
