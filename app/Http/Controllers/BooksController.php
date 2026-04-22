<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BooksController extends Controller
{
    public function index()
    {
        try {
            $books = Book::all();
            return response()->json([
                'status' => true,
                'message' => 'Data buku berhasil diambil',
                'data' => $books
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $book = Book::find($id);
            if ($book) {
                return response()->json([
                    'status' => true,
                    'message' => 'Data buku berhasil diambil',
                    'data' => $book
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan',
                    'data' => null
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'author' => 'required',
                'year' => 'required|integer|min:1900',
                'stock' => 'required|integer|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal: ' . $validator->errors()->first(),
                    'data' => null
                ], 400);
            }

            $book = Book::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Buku berhasil ditambahkan',
                'data' => $book
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $book = Book::find($id);
            if (!$book) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan',
                    'data' => null
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'author' => 'required',
                'year' => 'required|integer|min:1900',
                'stock' => 'required|integer|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal: ' . $validator->errors()->first(),
                    'data' => null
                ], 400);
            }

            $book->update($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Buku berhasil diperbarui',
                'data' => $book
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $book = Book::find($id);
            if (!$book) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan',
                    'data' => null
                ], 404);
            }

            $book->delete();
            return response()->json([
                'status' => true,
                'message' => 'Buku berhasil dihapus',
                'data' => null
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}
