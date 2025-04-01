<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    protected $supplier_id;

    public function __construct($supplier_id)
    {
        $this->supplier_id = $supplier_id;
    }

    public function model(array $row)
    {

        return new Product([
            'product_name' => $row['product_name'],
            'size' => $row['size'] ?? null,
            'category' => $row['category'],
            'types' => $row['types'] ?? null,
            'description' => $row['description'] ?? null,
            'cost_price' => $row['cost_price'],
            'sell_price' => $row['sell_price'],
            'stock' => $row['stock'] ?? 0,
            'supplier_id' => $this->supplier_id,
        ]);


    }
}