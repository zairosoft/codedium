<?php

namespace Modules\Sales\App\Imports;

use Modules\Sales\App\Models\Sale;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\ToModel;


class SalesImport implements ToModel
{
    public $row = 0;

    /**
     * @param array $row
     *
     * @return Sale|null
     */
    public function model(array $rows)
    {
        if ($this->row > 0) {
            if ($rows[5] == 'ออกแล้ว') {
                $saleName = @explode(',', $rows[39])[0];
                if ('คุณ'!== '' && str_contains($saleName, needle: 'คุณ')) {
                    $name = @explode(',', $rows[39])[0];
                } else {
                    $name = @explode(',', $rows[39])[1];
                }
                return new Sale([
                    'invoice'           => $rows[1],
                    'date'              => date('Y-m-d', strtotime(str_replace('/', '-', $rows[2]))),
                    'refer'             => $rows[3],
                    'status'            => $rows[5],
                    'contact_code'      => $rows[6],
                    'customer_name'     => $rows[7],
                    'product_code'      => $rows[14],
                    'product_name'      => $rows[15],
                    'amount'            => $rows[18],
                    'unit'              => $rows[19],
                    'price'             => $rows[20],
                    'discount'          => $rows[21],
                    'pre_tax'           => $rows[22],
                    'tax'               => $rows[23],
                    'total_vat'         => $rows[24],
                    'total'             => ($rows[32] != '' ? $rows[32] : '0.00'),
                    'sale_name'         => ($saleName != '' ? $name : '')
                ]);
            }
        }
        $this->row++;
    }

    // public function collection(Collection $rows)
    // {
    //     foreach($rows as $row){
    //         $data=[
    //             'name'     => $row['name'],
    //             'email'    => $row['email'],
    //             'password' => $row['password'],
    //         ];
    //         Sale::create($data);
    //     }
    // }

    public function rules(): array
    {
        return [
            'invoice'      => 'required',
            'customer_name'  => 'required',
            'product_code'     => 'required',
        ];
    }
}
