<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    // method untuk menampilkan semua data product (read)
    public function index(){
        $wishlists = Wishlist::all();

        if(count($wishlists) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $wishlists
            ], 200); // return data semua products dalam bentuk json
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data products kosong
    }

    public function show($id){
        $wishlist = Wishlist::find($id);

        if(!is_null($wishlist)){
            return response([
                'message' => 'Retrieve Wishlist Success',
                'data' => $wishlist
            ], 200);
        } // return data course yang ditemukan dalam bentuk json

        return response([
            'message' => 'Wishlist Not Found',
            'data' => null
        ], 404); // return message saat data course tidka ditemukan
    }

    public function showbyuser($id){
        // $user = User::find($id);
        $wishlist = Wishlist::where('id_user', $id)->get();

        if(count($wishlist) > 0){
            return response([
                'message' => 'Retrieve Wishlist Success',
                'data' => $wishlist
            ], 200);
        } // return data course yang ditemukan dalam bentuk json

        return response([
            'message' => 'Wishlist Not Found',
            'data' => null
        ], 404); // return message saat data course tidka ditemukan
    }

    public function store(Request $request){
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'namaBarangWish' => 'required',
            'hargaBarangWish' => 'required|numeric',
        ]); // membuat rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
        

        $wishlist = Wishlist::create($storeData);
        return response([
            'message' => 'Add wishlist Success',
            'data' => $wishlist
        ], 200); // return data product baru dalam bentuk json
    }

    public function destroy($id){
        $wishlist = Wishlist::find($id);

        if(is_null($wishlist)){
            return response([
                'message' => 'Wishlist Not Found',
                'data' => null
            ], 404); // Return message saat data tidak ditemukan
        }

        if($wishlist->delete()){
            return response([
                'message' => 'Delete Wishlist Success',
                'data' => $wishlist
            ], 200);
        } // return message saat berhasil menghapus data product

        return response([
            'message' => 'Delete Wishlist Failed',
            'data' => null,
        ], 400); // return message saat gagal menghapus data product
    }

    public function update(Request $request, $id){
        $wishlist = Wishlist::find($id);

        if(is_null($wishlist)){
            return response([
                'message' => 'Wishlist Not Found',
                'data' => null
            ], 404); // Return message saat data tidak ditemukan
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'namaBarangWish' => ['required', Rule::unique('products')->ignore($wishlist)],
            'hargaBarangWish' => 'required|numeric'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $wishlist->namaBarangWish = $updateData['namaBarangWish'];
        $wishlist->hargaBarangWish = $updateData['hargaBarangWish'];

        if($wishlist->save()){
            return response([
                'message' => 'Update Wishlist Success',
                'data' => $wishlist
            ], 200);
        }

        return response([
            'message' => 'Update Wishlist Failed',
            'data' => null,
        ], 400); // return message saat product gagal di edit
    }
}
