<?php

namespace App\Exports;

use App\Models\Campaign;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CampaignsExport implements FromCollection, WithHeadings, WithStyles
{

    public function collection()
    {
        return $campaign = campaign::get()->map(function ($campaign) {
            return [
                $campaign->id,
                $campaign->campaign_id,
                $campaign->unique_p1_id,
                $campaign->brand_id,
                $campaign->campaign_provider_id,
                $campaign->user_id,
                $campaign->pub_id
            ];
        });
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Campaign Id',
            'Unique P1 ID',
            'Brand ID',
            'Campaign Provider Id', 
            'User Id',
            'Publisher ID'
        ];
    }


    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Apply bold to the first row (headings)
        ];
    }
}
