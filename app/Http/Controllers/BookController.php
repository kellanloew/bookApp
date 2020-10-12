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
    
    /**
     * Handles a request for a search for a specific keyword
     */
    public function search(Request $request)
    {
        try{
            $originalKeyword = $request->input('keyword');
            //remove non-alphanumeric characters
            $keyword = preg_replace("/[^a-zA-Z0-9]/", "+", $originalKeyword);

            //if the keyword is blank, don't conduct the search
            if($keyword == "") return view("search", ["foundBooks" => array(), "term" => $keyword]);
            
            //read the JSON contents from the google books api
            $response = file_get_contents('https://www.googleapis.com/books/v1/volumes?q=' . $keyword."&maxResults=20");
            //decode JSON to array
            $response = json_decode($response, true);
            $returnArray = [];
            //loop through all results. Don't add more than 10 books to the list to return to client
            for($i = 0; $i < 10; $i++){
                $deets = new BookView();

                //is there another book in the array?
                if(!isset($response["items"][$i])) break;
                
                $deets->title = $response["items"][$i]["volumeInfo"]['title'];
                $deets->description = isset($response["items"][$i]["volumeInfo"]['description']) ? $response["items"][$i]["volumeInfo"]['description'] : "No description";
                
                //skip this volume if there is no ISBN 13 number for this book
                if(!isset($response["items"][$i]["volumeInfo"]["industryIdentifiers"]) || $response["items"][$i]["volumeInfo"]["industryIdentifiers"][0]['type'] != "ISBN_13"){
                    $i ++;
                    continue;
                }
                $deets->ISBN = $response["items"][$i]["volumeInfo"]["industryIdentifiers"][0]['identifier'];
                $deets->author = isset($response["items"][$i]["volumeInfo"]['authors'][0]) ? $response["items"][$i]["volumeInfo"]['authors'][0] : 'No author';
                //add this book object to the array to return
                $returnArray[] = $deets;
            }
            return view("search", ["foundBooks" => $returnArray, "term" => $originalKeyword]);
        }
        catch(\Exception $e){
            //return response()->view('errors.500', ['e' => "An error occured conducting that search with Google Books."], 500);
            return response()->view('errors.500', ['e' => $e->getMessage()], 500);
        }
    }

    /**
     * Adds a book selected by user based on ISBN
     */
    public function AddBook(Request $request){
        try{
            //check if the bok already exists
            $existing = Book::where('ISBN', '=', $request->input('chosenISBN'))->first();
            if($existing != null){
                return response()->json(['success'=>100, "message" => "That book already exists."]);
            }
            //if not, add this book to the DB
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

     /**
     * Shows details for a book, based on GET parameter of ISBN number
     */
    public function ShowDetails(Request $request){
        try{
            //read the JSON contents from the google books api
            $googleResponse = file_get_contents('https://www.googleapis.com/books/v1/volumes?q=isbn:' . $request->input('ISBN'));
            $googleResponse = json_decode($googleResponse, true);
            //assign book attributes to the new book object
            $details = new Book();
            $details->title = $googleResponse["items"][0]["volumeInfo"]['title'];
            $details->description = isset($googleResponse["items"][0]["volumeInfo"]['description']) ? $googleResponse["items"][0]["volumeInfo"]['description'] : "No description";
            $details->author = $googleResponse["items"][0]["volumeInfo"]['authors'][0];
            $details->pages = $googleResponse["items"][0]["volumeInfo"]["pageCount"];
            isset($googleResponse["items"][0]["volumeInfo"]["imageLinks"]['thumbnail']) ? $details->thumbnail = $googleResponse["items"][0]["volumeInfo"]["imageLinks"]['thumbnail'] : "";
            return view("details", ["book" => $details]);
        }
        catch(\Exception $e){
            //return a custom error page
            return response()->view('errors.500', ['e' => "An error occurred trying to get details for this book from the Google Books API."], 500);
        }
    }
}

