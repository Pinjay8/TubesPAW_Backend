<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Toko;

class TokoController extends Controller
{
    public function index(){
        $tokos = Toko::all();

        if(count($tokos) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $tokos
            ], 200); // return data semua products dalam bentuk json
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data products kosong
    }

    public function show($id){
        $toko = Toko::find($id);

        if(!is_null($toko)){
            return response([
                'message' => 'Retrieve Product Success',
                'data' => $toko
            ], 200);
        } // return data course yang ditemukan dalam bentuk json

        return response([
            'message' => 'Product Not Found',
            'data' => null
        ], 404); // return message saat data course tidka ditemukan
    }

    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'nama_toko' => 'required',
            'alamat_toko' => 'required',
        ]); // membuat rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
        

        $toko = Toko::create($storeData);
        return response([
            'message' => 'Add Product Success',
            'data' => $toko
        ], 200); // return data product baru dalam bentuk json
    }

    public function destroy($id){
        $toko = Toko::find($id);

        if(is_null($toko)){
            return response([
                'message' => 'Toko Not Found',
                'data' => null
            ], 404); // Return message saat data tidak ditemukan
        }

        if($toko->delete()){
            return response([
                'message' => 'Delete Product Success',
                'data' => $toko
            ], 200);
        } // return message saat berhasil menghapus data product

        return response([
            'message' => 'Delete Toko Failed',
            'data' => null,
        ], 400); // return message saat gagal menghapus data product
    }

    public function update(Request $request, $id){
        $toko = Toko::find($id);

        if(is_null($toko)){
            return response([
                'message' => 'Toko Not Found',
                'data' => null
            ], 404); // Return message saat data tidak ditemukan
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_toko' => ['required', Rule::unique('tokos')->ignore($toko)],
            'alamat_toko' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $toko->nama_toko = $updateData['nama_toko'];
        $toko->alamat_toko = $updateData['alamat_toko'];

        if($toko->save()){
            return response([
                'message' => 'Update Toko Success',
                'data' => $toko
            ], 200);
        }

        return response([
            'message' => 'Update Toko Failed',
            'data' => null,
        ], 400); // return message saat product gagal di edit
    }
}
