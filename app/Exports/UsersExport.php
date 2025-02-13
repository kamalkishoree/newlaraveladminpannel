<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection,WithHeadings,WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::all()->map(function ($user) {
            return [
                $user->id,
                $user->name.' '. $user->last_name,
                $user->email,
                $user->phone,
                $user->created_at,
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
            'Name',
            'Email',
            'Phone',
            'Created At',
        ];
    }



    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Apply bold to the first row (headings)
        ];
    }
}
