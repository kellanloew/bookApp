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
use Redirect;

class BookController extends BaseController
{
    public function home(){
        return view("search");
    }
    
    public function search(Request $request)
    {
        try{
            $originalKeyword = $request->input('keyword');
            $keyword = preg_replace("/[^a-zA-Z0-9]/", "+", $originalKeyword);

            //if the keyword is blank, don't conduct the search
            if($keyword == "") return view("search", ["foundBooks" => array(), "term" => $keyword]);
            
            $response = file_get_contents('https://www.googleapis.com/books/v1/volumes?q=' . $keyword);
            $response = json_decode($response, true);
            $returnArray = [];
            for($i = 0; $i < 10; $i++){
                $deets = new BookView();
                $deets->title = $response["items"][$i]["volumeInfo"]['title'];
                $deets->description = isset($response["items"][$i]["volumeInfo"]['description']) ? $response["items"][$i]["volumeInfo"]['description'] : "No description";
                if(!isset($response["items"][$i]["volumeInfo"]["industryIdentifiers"])){
                    continue;
                }
                $deets->ISBN = $response["items"][$i]["volumeInfo"]["industryIdentifiers"][0]['identifier'];
                $deets->author = isset($response["items"][$i]["volumeInfo"]['authors'][0]) ? $response["items"][$i]["volumeInfo"]['authors'][0] : 'No author';
                $returnArray[] = $deets;
            }
            return view("search", ["foundBooks" => $returnArray, "term" => $originalKeyword]);
        }
        catch(\Exception $e){
            //return response()->view('errors.500', ['e' => "An error occured conducting that search with Google Books."], 500);
            return response()->view('errors.500', ['e' => $e->getMessage()], 500);
        }
    }

    public function AddBook(Request $request){
        try{
            $existing = Book::where('ISBN', '=', $request->input('chosenISBN'))->first();
            if($existing != null){
                return response()->json(['success'=>100, "message" => "That book already exists."]);
            }
            else{
                $book = new Book();
                $book->ISBN = $request->input('chosenISBN');
                $book->author = $request->input('author');
                $book->title = $request->input('title');
                $book->position = Book::max('position') + 1;
                $book = Book::updateOrCreate(['ISBN' => $book->ISBN, 'author' => $book->author, 'title' => $book->title, 'position' => $book->position]);
                return response()->json(['success'=>200]);
            }
        }
        catch(\Exception $e){
            return response()->json(['success'=>500, "message" => $e->getMessage()]);
        }
    }

    public function ShowDetails(Request $request){
        try{
            $googleResponse = file_get_contents('https://www.googleapis.com/books/v1/volumes?q=isbn:' . $request->input('ISBN'));
            $googleResponse = json_decode($googleResponse, true);
            $details = new Book();
            $details->title = $googleResponse["items"][0]["volumeInfo"]['title'];
            $details->description = isset($googleResponse["items"][0]["volumeInfo"]['description']) ? $googleResponse["items"][0]["volumeInfo"]['description'] : "No description";
            $details->author = $googleResponse["items"][0]["volumeInfo"]['authors'][0];
            $details->pages = $googleResponse["items"][0]["volumeInfo"]["pageCount"];
            return view("details", ["book" => $details]);
        }
        catch(\Exception $e){
            //return back()->withError('An error occurred trying to get details for this book from the Google Books API.');
            return response()->view('errors.500', ['e' => "An error occurred trying to get details for this book from the Google Books API."], 500);
        }
    }
}

