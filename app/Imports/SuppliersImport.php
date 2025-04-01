<?php

namespace App\Imports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SuppliersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Supplier([
            'brand_name' => $row['brand_name'],
            'email'      => $row['email'] ?? null,
            'phone'      => $row['phone'] ?? null,
            'address'    => $row['address'] ?? null
        ]);
    }
}