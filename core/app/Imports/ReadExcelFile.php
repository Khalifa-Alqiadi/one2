<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ReadExcelFile implements ToCollection
{
    public $data = [];
    public $row_key = 0;

    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $i = 0;
        foreach ($rows as $row) {
            if ((@$row[0] != "" && @$row[0] != null) || (@$row[1] != "" && @$row[1] != null)) {
                $this->data = $row;
                $this->row_key = $i;
                break;
            }
            $i++;
        }
    }
}
