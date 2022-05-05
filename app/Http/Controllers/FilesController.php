<?php

namespace App\Http\Controllers;

use App\Imports\FilesImport;
use Illuminate\Auth\Events\Validated;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\File;
use App\Models\Header;
use App\Models\Row;

class FilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        echo('bomb');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'spreadsheet' => ['required', 'max:5000', 'mimes:xlsx,xls,csv'],
        ]);
        
        $dateTime = date('Ymd_His');
        $file = $request->file('spreadsheet');
        $path = $file->store('spreadsheets/uploaded');
        $fileName = $file->getClientOriginalName();
        $excel = (new FilesImport)->toArray($path);

        $valid_file = $file->isValid();

        if($valid_file) {
           $new_file = File::create([
                'user_id' => 1,
                'name' => $fileName,
                'path' => $path,
                'description' => $request->description ?? null,
           ]);           
        } 
        $rows = $excel[0];
        if($new_file->exists()) {
            $has_header = $request->has_header ?? false;
            
            if ($has_header == true ||$has_header ==  'true') {
                $headers = $rows[0];
                $column = 0;
                foreach ($headers as $header) {
                    ++$column;
                    $heading = Header::create([
                        'file_id' => $new_file->id,
                        'content' => $header,
                        'column' => (string)$column,
                    ]);
                }
                
                // insert remaining records
                $record_row = 0;                
                for ($i=1; $i < count($rows); $i++) {
                    ++$record_row;
                    $record_column = 0;
                    foreach ($rows[$i] as $cell) {
                        ++$record_column;
                        $entry = Row::create([
                            'file_id' => $new_file->id,
                            'content' => $cell,
                            'row' => (string)$record_row,
                            'column' => (string)$record_column,
                        ]);
                    }
                }
            } elseif ($has_header == false ||$has_header ==  'false') {
                $headers = $rows[0];
                $makeshift = 'A';
                $record_column = 0;
                foreach ($headers as $header) {
                    ++$record_column;
                    $heading = Header::create([
                        'file_id' => $new_file->id,
                        'content' => $makeshift,                        
                        'column' => (string)$record_column,
                    ]);
                    ++$makeshift;
                }
                
                // insert remaining records
                $record_row = 0;
                for ($i=0; $i < count($rows); $i++) { 
                    ++$record_row;
                    $record_column = 0;
                    foreach ($rows[$i] as $cell) {
                        ++$record_column;

                        $entry = Row::create([
                            'file_id' => $new_file->id,
                            'content' => $cell,
                            'row' => (string)$record_row,
                            'column' => (string)$record_column,
                        ]);
                    }
                }
            }
        } else {
            echo 'could not insert file';
        }
        dd($entry);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
