<?php

namespace Modules\Sales\App\Imports;

use Modules\Sales\App\Models\Sale;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\ToModel;


class TargetsImport implements ToModel
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
            echo "<pre>";
            print_r($rows);
            echo "</pre>";
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
            'name'      => 'required',
            'password'  => 'required',
            'email'     => 'required',
        ];
    }
}
