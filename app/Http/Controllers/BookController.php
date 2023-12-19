<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Exception;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::all();
        return view('crud', compact('books'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required',
            'author' => 'required',
        ]);

        try{

            $book = Book::create($request->all());

        }catch(Exception $e){

            return response()->json(["error" => "Something went wrong!"]);
        }

        return response()->json(["success" => "Book data has been stored!", "data" => $book]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'author' => 'required',
        ]);
        try{

            $book = Book::findOrFail($id);
            $book->update($request->all());

        }catch(Exception $e){
            return response()->json(["error" => "Something went wrong!"]);
        }
        
        return response()->json(["success" => "Book data has been updated successfully!"]);
    }

    public function delete($id)
    {
        try{
            $book = Book::findOrFail($id);
            $book->delete();

        }catch(Exception $e){
            return response()->json(["error" => "Something went wrong!"]);
        }
        
        return response()->json(["success" => "Book data has been deleted successfully!"]);
    }
}
