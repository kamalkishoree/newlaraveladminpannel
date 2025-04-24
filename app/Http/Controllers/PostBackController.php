<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Campaign,Conversion};
use App\Http\Traits\WalletTrait;
use Illuminate\Support\Facades\Log;
class PostBackController extends Controller
{
    
    use WalletTrait;

    public function vCommissionpostBack(Request $request)
    {
        Log::info('vCommission Webhook Triggered', $request->all());

        // dd($request->all());
        if(!empty($request) && !empty($request->source))
        {

            $campaign = Campaign::where('unique_source_id',$request->source)->first();
            $data = $request->all();
            $filteredArray = [
            'user_id' => $campaign->user_id ?? null,
            'click_table_id' => $data['click_id'] ?? null,
            'unique_source_id' => $data['source'] ?? null,
            'user_campaign_id' =>$campaign->id,
            'campaign_id' => $data['campaign_id'] ?? null,
            'campaign_name' => $data['campaign_name'] ?? null,
            'publisher_id' => $data['publisher_id'] ?? null,
            'aff_name' => $data['aff_name'] ?? null,
            'aff_id' => $data['aff_id'] ?? null,
            'click_id' => $data['click_id'] ?? null,
            'click_time' => $data['click_time'] ?? null,
            'conversion_id' => $data['conversion_id'] ?? null,
            'conversion_datetime' => $data['conversion_datetime'] ?? null,
            'conversion_status' => $data['conversion_status'] ?? null,
            'payout' => $data['payout'] ?? null,
            'currency' => $data['currency'] ?? null,
            'txn_id' => $data['txn_id'] ?? null,
            'sale_amount' => $data['sale_amount'] ?? null,
            'click_ip' => $data['click_ip'] ?? null,
            'city' => $data['city'] ?? null,
            'region' => $data['region'] ?? null,
            'isp' => $data['isp'] ?? null,
            'goal_name' => $data['goal_name'] ?? null,
            'goal_id' => $data['goal_id'] ?? null,
            'order_id' =>  $data['order_id'] ?? ($data['sub_id'] ?? $data['sub1_id'] ?? $data['orderId']),
            ];
         
            if (!empty(array_filter($filteredArray))) {
                $conversion = Conversion::firstOrCreate(
                    ['unique_source_id' => $data['source'] ?? null], // Lookup key
                    $filteredArray // Data to insert if not found
                );
                if($filteredArray['conversion_status'] != $conversion->conversion_status || $conversion->conversion_status == 0)
                {
                    $this->updateWallet($campaign->brand,$campaign->user_id,$filteredArray['payout'],$filteredArray['conversion_status']);
                }
                $campaign->update(['conversion_status'=>1]);
                Log::info("Conversion stored successfully", ['campaign_id' => $campaign->id]);
                return response()->json([
                'status' => 'success',
                'message' => 'Webhook received successfully',
                'data' => $request->all()
                ]);

            }
        }
        else{
            return response()->json([
                'status' => 'faild',
                'message' => 'Webhook received empty successfully',
                'data' => $request->all()
             ]);
        }
            
    }
}
