<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use App\Models\Conversion;
use App\Services\TrackierService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CreateClickConversionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'conversion:create-job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will created ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $campaigns = Campaign::where('conversion_status', 0)->get();
            foreach ($campaigns as $campaign) {
                if (!str_contains($campaign->brand->target_url ?? '', 'vcommission')) {
                    continue;
                }
                $conversion = (new TrackierService())->getConversions($campaign);
                $conversion = $conversion['conversions'][0] ?? [];
                if (!empty($conversion)) {
                    $filteredArray = [
                        'user_id' => $campaign->user_id ?? null,
                        'click_id' => $conversion['click_id'] ?? null,
                        'unique_source_id' => $conversion['source'] ?? null,
                        'method' => $conversion['method'] ?? null,
                        'sale' => $conversion['sale'] ?? null,
                        'p1' => $conversion['p1'] ?? null,
                        'p2' => $conversion['p2'] ?? null,
                        'p3' => $conversion['p3'] ?? null,
                        'p4' => $conversion['p4'] ?? null,
                        'p5' => $conversion['p5'] ?? null,
                        'sub1' => $conversion['sub1'] ?? null,
                        'txn_id' => $conversion['txn_id'] ?? null,
                        'note' => $conversion['note'] ?? null,
                        'currency' => $conversion['currency'] ?? null,
                        'payout' => $conversion['payout'] ?? null,
                        'brand' => $conversion['brand'] ?? null,
                        'status' => $conversion['status'] ?? null,
                        'campaign_id' => $conversion['campaign_id'] ?? null,
                        'campaign_name' => $conversion['campaign_name'] ?? null,
                    ];

                    // Ensure no empty record is inserted
                    if (!empty(array_filter($filteredArray))) {
                        Conversion::create($filteredArray);
                        $campaign->update(['conversion_status'=>1]);
                        Log::info("Conversion stored successfully", ['campaign_id' => $campaign->id]);
                    }
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
}
