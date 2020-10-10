@extends('layouts.mainlayout')

<head>
    <title>Home</title>
    <link href="http://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">  
    <script src="http://code.jquery.com/jquery-1.10.2.js"></script>  
    <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script> 
</head>
@section('content')
<div class="container-fluid">
    <div class='row'>
        <h1>Your currently selected books</h1>
        <p>You may drag and drop any of the titles to reorder the list. Clicking the red "x" button will remove that book from the list.</p>
    </div>
    <div class="row">
        <div class="col-lg-12" id='book-list'>
        @if(isset($books))
            @foreach($books as $book)
                <div class='row' style='border: 1px solid;' id='<?php echo $book->position ?>' ISBN='<?php echo $book->ISBN ?>'>
                    <div>
                        <form action="" method='POST'>
                            {{ csrf_field() }}
                            <button style='color:red;' class='delete'>&#10005;</button>
                            <input style='display:none;' name="ISBN" value='<?php echo $book->ISBN ?>'>
                        </form>
                    </div>
                    <div class='col-lg-2 my-handle'>
                        <a href='/details/?ISBN=<?php echo $book->ISBN ?>' target='_blank'>{{$book->title}}</a>
                    </div>
                    <div class='col-lg-2'>
                        <p>{{$book->author}}</p>
                    </div>
                </div>
            @endforeach
        @endif
        </div>
    </div>
    <div class='row'>
        <button class='btn btn-sm btn-info' id="sort-title">Sort by title</button>
        <a class='btn btn-sm btn-dark' href="/search" target="_blank">Search for a new book to add</a>
    </div>
</div>
@endsection
</html>

<script type='text/javascript'>


$(document ).ready(function() {
    
    //MAKE BOOK LIST SORTABLE
    $("#book-list").sortable({  
        start: function(e, ui) {
            // creates a temporary attribute on the element with the old index
            $(this).attr('data-previndex', ui.item.index());
        },
        update: function(event, ui) {  

            // ui.item.sortable is the model but it is not updated until after update
            var oldIndex = parseInt($(this).attr('data-previndex')) + 1;
            var replaced = ui.item.prev();
            if (replaced.length == 0) {
                replaced = ui.item.next();
            }
            //var oldISBN = replaced.attr("ISBN");

            // new Index because the ui.item is the node and the visual element has been reordered
            var newIndex = ui.item.index() + 1;

            // console.log({
            //         oldIndex: oldIndex,
            //         newIndex:newIndex,
            //     });

            $.ajax({
                url: "/dragdrop",
                data: {
                    oldIndex: oldIndex,
                    newIndex:newIndex,
                },
                dataType: "json",
                success: function(response) {
                    console.log(response);
                },
                error: function(jqXHR, status, exception) {
                    alert("Something went wrong: " + exception);
                }
            });
        }  
    });

    $('#sort-title').on("click", function(){
        $.ajax({
            url: "/sort",
            data: {},
            dataType: "json",
            success: function(response) {
                if(response.success == 200){
                    location.reload();
                }
                else{
                    alert("Something went wrong in the sorting process.")
                }
            },
            error: function(jqXHR, status, exception) {
                alert("Something went wrong: " + exception);
            }
        });
    });
})

</script>