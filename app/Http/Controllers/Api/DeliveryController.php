<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Delivery;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $deliverys = Delivery::all();

        if(count($deliverys) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $deliverys
            ], 200); // return data semua deliverys dalam bentuk json
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data deliverys kosong

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'tujuanPengiriman' => 'required',
            'jenisPengiriman' => 'required',
            'lamaPengiriman' => 'required',
            'harga' => 'required|numeric',
        ]); // membuat rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
        

        $delivery = Delivery::create($storeData);
        return response([
            'message' => 'Add Delivery Success',
            'data' => $delivery
        ], 200); // return data delivery baru dalam bentuk json
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $delivery = Delivery::find($id);

        if(!is_null($delivery)){
            return response([
                'message' => 'Retrieve Delivery Success',
                'data' => $delivery
            ], 200);
        } // return data course yang ditemukan dalam bentuk json

        return response([
            'message' => 'Delivery Not Found',
            'data' => null
        ], 404); // return message saat data course tidak ditemukan
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
         $delivery = Delivery::find($id);

        if(is_null($delivery)){
            return response([
                'message' => 'Delivery Not Found',
                'data' => null
            ], 404); // Return message saat data tidak ditemukan
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'tujuanPengiriman' => 'required',
            'jenisPengiriman' => 'required',
            'lamaPengiriman' => 'required',
            'harga' => 'required|numeric',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $delivery->tujuanPengiriman = $updateData['tujuanPengiriman'];
        $delivery->jenisPengiriman = $updateData['jenisPengiriman'];
        $delivery->lamaPengiriman= $updateData['lamaPengiriman'];
        $delivery->harga= $updateData['harga'];

        if($delivery->save()){
            return response([
                'message' => 'Update Delivery Success',
                'data' => $delivery
            ], 200);
        }

        return response([
            'message' => 'Update Delivery Failed',
            'data' => null,
        ], 400); // return message saat delivery gagal di edit
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $delivery = Delivery::find($id);

        if(is_null($delivery)){
            return response([
                'message' => 'Delivery Not Found',
                'data' => null
            ], 404); // Return message saat data tidak ditemukan
        }

        if($delivery->delete()){
            return response([
                'message' => 'Delete Delivery Success',
                'data' => $delivery
            ], 200);
        } // return message saat berhasil menghapus data del$delivery

        return response([
            'message' => 'Delete Delivery Failed',
            'data' => null,
        ], 400); // return message saat gagal menghapus data delivery
    }
}
