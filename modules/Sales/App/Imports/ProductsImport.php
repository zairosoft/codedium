<?php

namespace Modules\Sales\App\Imports;

use Modules\Sales\App\Models\Product;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\ToModel;


class ProductsImport implements ToModel
{
    public $row = 0;

    /**
     * @param array $row
     *
     * @return Product|null
     */
    public function model(array $rows)
    {
        if ($this->row > 0) {
            return new Product([                
                'product_code'             => $rows[1],
                'type'                     => $rows[2],
                'product_name'             => $rows[3],
                'unit'                     => $rows[4],
            ]);
        }
        $this->row++;
    }

    public function rules(): array
    {
        return [
            'product_code'      => 'required',
            'type'              => 'required',
            'product_name'      => 'required',
        ];
    }
}
