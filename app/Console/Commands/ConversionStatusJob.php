<?php

namespace App\Console\Commands;

use App\Models\Conversion;
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
            $conversions = Conversion::where('status', 'pending')->get();
            foreach ($conversions as $conversion) {

                pr($conversion->brand);
                if (!str_contains($campaign->brand->target_url ?? '', 'vcommission')) {
                    continue;
                }
                $conversion = (new TrackierService())->getConversions($campaign);
                $conversion = $conversion['conversions'][0] ?? [];
                if (!empty($conversion)) {
                   
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
