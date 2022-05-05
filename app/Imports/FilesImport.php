<?php

namespace App\Imports;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;

class FilesImport implements ToCollection
{
    use Importable;
    public function collection(Collection $records) {
        $report = '2';
        
        // foreach ($records as $record) {
        //     Row::create([
        //         'file_id' => 1,
        //         'content' => $record[0],
        //     ]);
        // }
    }
}
