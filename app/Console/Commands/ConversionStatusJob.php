<?php

namespace App\Console\Commands;

use App\Models\Conversion;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Services\TrackierService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ConversionStatusJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'conversion:status-job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch conversions and update their statuses.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $conversions = Conversion::where('status', 'pending')
                ->limit(10)
                ->get();
            foreach ($conversions as $conversion) {
                $brand = $conversion->campaign->brand;
                if (!str_contains($brand->target_url ?? '', 'vcommission')) {
                    continue;
                }
                $conversions = $conversion->campaign;
                $conversion = (new TrackierService())->getConversions($conversions);
                $conversion = $conversion['conversions'][0] ?? [];
                if (!empty($conversion) && $conversion['status'] == 'approved') {
                    $conversion->update(['status' => 'approved']);
                    $this->updateWallet($brand,$conversion->user_id,$conversion->payout,'approved');
                }
                elseif(!empty($conversion) && $conversion['status'] == 'rejected')
                {
                    $conversion->update(['status' => 'rejected']);
                    $this->updateWallet($brand,$conversion->user_id,$conversion->payout,'rejected');
                    
                }   
            }
        } catch (\Exception $e) {
            Log::error("Error in ConversionStatusJob: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function updateWallet($brand,$user_id,$amount,$status)
    {
        if($brand->payout_type == 'flat')
        {
            $amount = $brand->payout_amount;
        }
        elseif($brand->payout_type == 'percentage')
        {
            $amount = $amount * $brand->payout_percentage / 100;
        }
        elseif($brand->payout_type == 'custom')
        {
            $amount = $brand->payout_amount;
        }
     
         $wallet = Wallet::where('user_id',$user_id)->first();
         if($wallet)
         {
            if($status == 'approved')
            {
                $wallet->balance += $amount;
                $wallet->pending_balance -= $amount;
                $wallet->save();
                Transaction::createTransaction($user_id,$amount,'credit','conversion','approved','');
            }
            elseif($status == 'rejected')
            {
                $wallet->pending_balance -= $amount;
                $wallet->save();
                Transaction::createTransaction($user_id,$amount,'debit','conversion','rejected','');
            }
         }
        
    }
 }
