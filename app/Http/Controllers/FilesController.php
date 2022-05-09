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
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generateResJSON($status, $data=['data' => null], $message) {
        $response = array();
        $response['status'] = $status;
        $response['data'] = $data;
        $response['message'] = $message;
        return $response;
    }
    public function index()
    {
        if(Auth::check()) {
            $files = File::where([
                'user_id' => Auth::user()->id,
            ])->get();
            
            return view('files.files', ['user' => Auth::user(), 'files' => $files]);            
        } else {
            return route('login');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function store(Request $request)
    {   
        
        
        $validated = $request->validate([
            'spreadsheet' => ['required', 'max:5000', 'mimes:xlsx,xls,csv'],
        ]);
        if ($validated) {
            $dateTime = date('Ymd_His');
            $file = $request->file('spreadsheet');
            $path = $file->store('spreadsheets/uploaded');
            $fileName = $file->getClientOriginalName();
            $excel = (new FilesImport)->toArray($path);

            $gate = ['1', 'yes', 'YES', 'true'];
            $has_header = $request['has_header'] ? 'true' : $request['has_header'];
            $has_header = in_array($request['has_header'], $gate) ? true: false;

            $valid_file = $file->isValid();
            isset($request->email) ? $user = User::where(['email' => $request->email])->get() : $user = [];
            $user_id = $user[0]['id'] ?? "";
            
            if($valid_file) {
                
                $new_file = File::create([
                    'user_id' => $user_id,
                    'name' => $fileName,
                    'path' => $path,
                    'description' => $request->description ?? '',
                    'has_header' => $has_header,
                ]);

                $rows = $excel[0];
                if($new_file->exists()) {
                    
                    if ($has_header) {
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
                        if($heading->exists) {
                            $data = [
                                'file_name' => $fileName,                            
                                'file_details' => [
                                    'header' => $has_header,
                                    'rows' => $record_row,
                                    'columns' => $record_column,
                                    'owner' => $user[0]['name'],
                                    'email' => $user[0]['email'],
                                ],  
                            ];
                            $response = $this->generateResJSON('success', $data, 'successfuly imported file: '.$fileName);
                        } else {
                            $response = $this->generateResJSON('error', ['error'=> true], 'Sorry, couldn not insert rows. Check if file has content');
                        }
                    } else {
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
                        if($entry->exists) {
                            $data = [                            
                                'file_name' => $fileName,                            
                                'file_details' => [
                                    'header' => $has_header,
                                    'rows' => $record_row,
                                    'columns' => $record_column,
                                    'owner' => $user[0]['name'],
                                    'email' => $user[0]['email'],
                                ], 
                            ];
                            $response = $this->generateResJSON('success', $data, 'successfuly imported file: '.$fileName);
                        } else {
                            $response = $this->generateResJSON('error', ['error'=> true], 'Sorry, couldn not insert rows. Check if file is empty');
                        }
                    }
                } else {
                    $response = $this->generateResJSON('error', ["last_entry" => null], 'sorry, we are having some issues finding your file');
                }
            } else {
                $response = $this->generateResJSON('error', ["valid" => $valid_file], 'Sorry, your file is not valid');
            }
        } else {
            $response = $this->generateResJSON('error', ["all_fields" => false], 'Please provide a spreadsheet file and an email');
        }
        

        
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Auth::check()) {
            $file = File::where([
                'id' => $id,
            ])->get();
            $header = Header::where([
                'file_id' => $id,
            ])->get();
            $rows = Row::where([
                'file_id' => $id,
            ])->get();
            
            return view('files.file', ['user' => Auth::user(), 'file' => $file, 'header' => $header, 'rows' => $rows,]);            
        } else {
            return route('login');
        }
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
        
        $file_id = $request->file_id;

        $records = $request->records;
        
        foreach ($records as $record) {
            $entry = Row::create([
                'file_id' => $id,
                'content' => $record['content'],
                'row' => '',
                'column' => (string)$record['column'],
            ]);
        }
        if ($entry) {
            $response = $this->generateResJSON('success', ["last_entry" => $entry], 'Your new record was added successfully');
        } else {
            $response = $this->generateResJSON('error', ["last_entry" => null], 'Your new record was added successfully');
        }
        return response()->json($response);
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
