<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Liga;
use Validator;
use Storage;

class LigaController extends Controller
{
    public function index()
    {
        $liga = Liga::latest()->get();
        $res = [
            'success' => true,
            'massage' =>'Daftar Liga Sepak Bola',
            'data' => $liga,
        ];
        return response()->json($res, 200);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nama_liga' => 'required|unique:ligas',
            'negara' => 'required',
        ]);
        
        if($validate->fails()){
            return response()->json([
                'success'=>false,
                'messege'=>'validasi gagal',
                'errors' => $validate->errors(),
            ], 422);
        }

        try {
            $liga = new Liga;
            $liga->nama_liga = $request->nama_liga;
            $liga->negara = $request->negara;
            $liga->save();
            return response()->json([
                'success'=>true,
                'messege'=>'Data liga berhasil dibuat',
                'data' => $liga,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success'=>false,
                'messege'=>'Terjadi kesalahan',
                'data' => $e->getMessage(),
            ], 500);
        }
    }
    public function show($id){
        try {
            $liga = Liga::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'data liga berhasil dibuat',
                'data' => $liga,
            ], 201);
        } catch (\Exception $e){
            return response()->json([
                'succes' => false,
                'message' => 'terjadi kesalahan',
                'errors' => $e->getMessage(),
            ],500);
        }
    }
     public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'nama_liga' => 'required',
            'negara' => 'required',
        ]);
        
        if($validate->fails()){
            return response()->json([
                'success'=>false,
                'messege'=>'validasi gagal',
                'errors' => $validate->errors(),
            ], 422);
        }

        try {
            $liga = new Liga;
            $liga->nama_liga = $request->nama_liga;
            $liga->negara = $request->negara;
            $liga->save();
            return response()->json([
                'success'=>true,
                'messege'=>'Data liga berhasil dibuat',
                'data' => $liga,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success'=>false,
                'messege'=>'Terjadi kesalahan',
                'data' => $e->getMessage(),
            ], 500);
        }
    }
    public function destroy($id){
        try{
            $liga = Liga::findOrFail($id);
            return response()->json([
                'success'=>true,
                'messege'=>'Data'.$liga->nama_liga. 'berhasil dihapus',
            ], 200);
        } catch (\Exception $e){
            return response()->json([
                'success'=>false,
                'messege'=>'Data tidak ada',
                'errors' => $e->getMessage(),
            ], 404);
        }
    }

}


