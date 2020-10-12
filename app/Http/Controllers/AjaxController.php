<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models;
use App\Models\Book;

class AjaxController extends Controller
{

    //change order of books in list by drag-and-drop
    public function DragDrop(Request $request){
        $oldPosition = $request->input("oldIndex");
        $newPosition = $request->input("newIndex");

        $books = Book::all()->sortBy("position");

        //loop through all books in reading list, updating position of those that should be
        foreach($books as $book){

            // set the extremes for the range of books whose order is to be changed, and set amount to change their position by.
            // The calculations depend on whether the new positon of moved book is greater or less than original position
            if($oldPosition > $newPosition){
                $topThresh = $oldPosition;
                $bottomThresh = $newPosition;
                $bumpOthersThisMuch = 1;
            }
            else{
                $topThresh = $newPosition;
                $bottomThresh = $oldPosition;
                $bumpOthersThisMuch = -1;
            }

            if($book->position < $bottomThresh) continue;
            elseif($book->position > $topThresh) break;
            elseif($book->position == $oldPosition) $change = $newPosition;
            else $change = $book->position + $bumpOthersThisMuch;

            //update position in DB
            $updateBook = Book::where('ISBN', '=', $book->ISBN)->first();
            $updateBook->position = $change;
            $updateBook->update(["position" => $updateBook->position]);
        }
        
        return response()->json(['success'=>200]);
    }

    //sorts list of books based on title alphabetically
    public function Sort(Request $request){
        $books = Book::all()->sortBy("title");
        $currentPosition = 0;
        //loop through all books in reading list
        foreach($books as $book){
            //update position in DB to match current position
            $currentPosition ++;
            $updateBook = Book::where('ISBN', '=', $book->ISBN)->first();
            //if the current book is not in the correct position, change its position
            if($updateBook->position != $currentPosition){
                $updateBook->position = $currentPosition;
                $updateBook->update(["position" => $updateBook->position]);
            }
            else continue;
        }
        return response()->json(['success'=>200]);
    }
}
