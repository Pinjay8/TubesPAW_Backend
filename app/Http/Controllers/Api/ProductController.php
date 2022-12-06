<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;

class ProductController extends Controller
{
    // method untuk menampilkan semua data product (read)
    public function index(){
        $products = Product::all();

        if(count($products) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $products
            ], 200); // return data semua products dalam bentuk json
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data products kosong
    }

    public function show($id){
        $product = Product::find($id);

        if(!is_null($product)){
            return response([
                'message' => 'Retrieve Product Success',
                'data' => $product
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
            'nama_barang' => 'required',
            'harga_barang' => 'required|numeric',
        ]); // membuat rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
        

        $product = Product::create($storeData);
        return response([
            'message' => 'Add Product Success',
            'data' => $product
        ], 200); // return data product baru dalam bentuk json
    }

    public function destroy($id){
        $product = Product::find($id);

        if(is_null($product)){
            return response([
                'message' => 'Product Not Found',
                'data' => null
            ], 404); // Return message saat data tidak ditemukan
        }

        if($product->delete()){
            return response([
                'message' => 'Delete Product Success',
                'data' => $product
            ], 200);
        } // return message saat berhasil menghapus data product

        return response([
            'message' => 'Delete Product Failed',
            'data' => null,
        ], 400); // return message saat gagal menghapus data product
    }

    public function update(Request $request, $id){
        $product = Product::find($id);

        if(is_null($product)){
            return response([
                'message' => 'Product Not Found',
                'data' => null
            ], 404); // Return message saat data tidak ditemukan
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_barang' => ['required', Rule::unique('products')->ignore($product)],
            'harga_barang' => 'required|numeric'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $product->nama_barang = $updateData['nama_barang'];
        $product->harga_barang = $updateData['harga_barang'];

        if($product->save()){
            return response([
                'message' => 'Update Product Success',
                'data' => $product
            ], 200);
        }

        return response([
            'message' => 'Update Product Failed',
            'data' => null,
        ], 400); // return message saat product gagal di edit
    }
}
