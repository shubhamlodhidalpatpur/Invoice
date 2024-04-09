<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\UserData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $fields = Field::where('role_id', 0)->get();
        $select = [];
        $select[] = 'json_tbl.user_id';
        $table_columns = [];
        $table_columns[] = 'sr_no';
        foreach($fields as $field){
            $select[] = DB::raw('MAX(CASE WHEN fields.title = "'.$field->title.'"THEN json_tbl.field_value END) AS '.Str::snake($field->title));
            $table_columns[] = Str::snake($field->title);
        }
        $table_columns[] = 'action';
        $table_data = DB::query()->fromSub(function ($query){
            $query->from('user_data')
            ->leftJoin(DB::raw('JSON_TABLE(user_data.data, "$[*]" COLUMNS (field_id INT PATH "$.id", field_value VARCHAR(255) PATH "$.value")) as jt'), DB::raw('1'),DB::raw('1'))
            ->select(
                'user_data.user_id',
                'jt.field_id',
                'jt.field_value',
            );
        }, 'json_tbl')
        ->select($select)
        ->leftJoin('fields', 'fields.id', '=', 'json_tbl.field_id')
        // ->where('fields.is_default_field', 1)
        ->groupBy('json_tbl.user_id')
        ->get();
        

        return response(['table_data'=> $table_data, 'table_column' => $table_columns]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function edit($id)
    {
        $id = base64_decode($id);
        $user_data = UserData::where('user_id', $id)->first();
        dd($user_data->user_data);
        // $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

        // $table_data = DB::query()->fromSub(function ($query){
        //     $query->from('user_data')
        //     ->leftJoin(DB::raw('JSON_TABLE(user_data.data, "$[*]" COLUMNS (field_id INT PATH "$.id", field_value VARCHAR(255) PATH "$.value")) as jt'), DB::raw('1'),DB::raw('1'))
        //     ->select(
        //         'user_data.user_id',
        //         'jt.field_id',
        //         'jt.field_value',
        //     );
        // }, 'json_tbl')
        // ->select($select)
        // ->leftJoin('fields', 'fields.id', '=', 'json_tbl.field_id')
        // // ->where('fields.is_default_field', 1)
        // ->groupBy('json_tbl.user_id')
        // ->get();


        // $fields = Field::where('role_id', $role_id)
        //     ->leftJoin('field_types','field_types.id','fields.field_type_id')
        //     // ->orWhere('role_id', 0)
        //     ->select(
        //         'fields.*',
        //         'field_types.title as field_type',
        //         'field_types.slug as field_type_slug',
        //         DB::raw("
        //             Case When field_types.slug = 'string' then 'AAAAAA' 
        //              When field_types.slug = 'integer' then 123 
        //              When field_types.slug = 'float' then '78.34' 
        //              When field_types.slug = 'boolean' then 1 
        //              When field_types.slug = 'date' then '2023-12-24' 
        //              When field_types.slug = 'enum' then 'Male' 
        //              When field_types.slug = 'datetime' then '2023-12-24 11:00:00' END
        //         as value")
        //     )
        //     ->get();
        // if(isset($trace[1]['function']) && $trace[1]['function'] == 'callAction'){
        //     return response(['data' => $fields, 'status' => 'success'], 200);
        // }
        // else{
        //     return $fields;
        // }
        
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

    public function submitUserForm(Request $request)
    {
        $data = $request->all();
        $value = [];
        DB::beginTransaction();
        try{

            foreach($data as $d){
                $value[] = ['id' => $d['id'], 'value' => $d['value']];
            }
            \DB::table('user_data')->insert([
                'data' => json_encode($value)
            ]);
            DB::commit();
        }
        catch(\Exception $e){
            DB::rollback();
            dd($e, $d);
        }
        return response(['status'=>'success', 'message' => 'Employee Added Successfully']);
    }
}
