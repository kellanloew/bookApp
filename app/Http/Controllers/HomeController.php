<?php

namespace App\Http\Controllers;
use App\Models;
use App\Models\Book;
use App\Models\BookView;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class HomeController extends BaseController{
    public function home()
    {
        $currentBooks = Book::all()->sortBy("position");
        return view("home", ["books" => $currentBooks]);
    }

    //deletes a book in the current user's list
    public function delete(Request $request){
        $ISBN = $request->input('ISBN');
        $currentBook = new Book();
        $currentBook = Book::where('ISBN', $ISBN)->first();
        $books = Book::where('position', '>', $currentBook->position)->get();

        //update position of other books
        foreach($books as $resultingBook){
            $book = new Book();
            $book = Book::where('ISBN', '=', $resultingBook->ISBN)->first();
            $book->position --;
            $book->update(["position" => $book->position]);
        }
        Book::where('ISBN', $ISBN)->delete();
        return redirect('');
    }

}