<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Klub;
use Illuminate\Http\Request;
use Validator;
use Storage;

class KlubController extends Controller
{

    public function index()
    {
        $klub = Klub::latest()->get();
        return response()->json([
            'success' => true,
            'message' => 'Daftar Klub',
            'data' => $klub,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_klub' => 'required',
            'logo' => 'required|image|max:2048',
            'id_liga' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'data tidak valid',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // upload image
            $path = $request->file('logo')->store('public/logo');
            $klub = new Klub;
            $klub->nama_klub = $request->nama_klub;
            $klub->logo = $path;
            $klub->id_liga = $request->id_liga;
            $klub->save();
            return response()->json([
                'success' => true,
                'message' => 'data berhasil dibuat',
                'data' => $klub,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'terjadi kesalahan',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $klub = Klub::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'data klub berhasil dibuat',
                'data' => $klub,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'terjadi kesalahan',
                'errors' => $e->getMessage(),
            ], 500);
        }

    }

    public function update(Request $request, string $id)
    {
        $validate = Validator::make($request->all(), [
            'nama_klub' => 'required',
            'logo' => 'required',
            'id_klub' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'messege' => 'data tidak valid',
                'errors' => $validate->errors(),
            ], 422);
        }

        try {
            $klub = Klub::findOrFail($id);
            if ($request->hasFile('logo')) {
                // delete foto / logo lama
                Strorage::delete($klub->logo);
                $path = $request->file('logo')->store('public/logo');
                $klub->logo = $path;
            }
            $klub->nama_klub = $request->nama_klub;
            $klub->id_liga = $request->id_liga;
            $klub->save();

            return response()->json([
                'success' => true,
                'messege' => 'data berhasil di perbarui',
                'data' => $klub,
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'succes' => false,
                'message' => 'terjadi kesalahan',
                'errors' => $e->getMessage(),
            ], 500);

        }
    }

    public function destroy($id)
    {
         try {
            $klub = Klub::findOrFail($id);
            Storage::delete($klub->logo);
            $klub->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data ' . $klub->nama_klub . ' berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'data tidak ada',
                'errors' => $e->getMessage(),
            ], 404);
        }

    }
}
