@extends('layouts.mainlayout')
<head>
    <title>Search</title>
    <link href="http://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">  
    <script src="http://code.jquery.com/jquery-1.10.2.js"></script>  
    <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script> 
</head>
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <form action="" method="POST">
                {{ csrf_field() }}
                <h2>Search for a book</h2>
                <input name="keyword" />
            </form>
        </div>
        
        @if(isset($foundBooks) && count($foundBooks) > 0)
        <p>Showing {{ count($foundBooks) }} results for "{{ $term }}"</p>
        <table>
            <tr>
                <th></th>
                <th>Title</th>
                <th>Author</th>
                <th>Description</th>
            </tr>
            @foreach($foundBooks as $book)
            <tr>
                <td>
                    <form action="" method="post" class="add-book">
                        {{ csrf_field() }}
                        <input style='display:none;' name="chosenISBN" value='<?php echo $book->ISBN ?>'>
                        <input style='display:none;' name="title" value='<?php echo $book->title ?>'>
                        <input style='display:none;' name="author" value='<?php echo $book->author ?>'>
                        <button class='btn btn-info'>Add to reading list</button>
                    </form>
                </td>
                <td>
                    <p>{{$book->title}}</p>
                </td>
                <td>
                    <p>{{$book->author}}</p>
                </td>
                <td>
                    <p>{{$book->description}}</p>
                </td>
            </tr>
            @endforeach
        </table>
        @elseif(isset($term))
        <p>There are no results for "{{ $term }}".</p>
        @endif
    </div>
    <div class='alert alert-success'>That book was added successfully!</div>
    <div class='alert alert-warning'>That book already exists.</div>
    <div class='alert alert-danger'>Something went wrong while trying to add that book.</div>
</div>

@endsection

<script>
$(document ).ready(function() {

    $('.add-book').submit(function(e){
        e.preventDefault();
        var form = $(this);
        
        $.ajax({
            url: "/add",
            data: form.serialize(),
            dataType: "json",
            success: function(response) {
                if(response.success == 200){
                    $('.alert-success').show()
                    setTimeout(function(){ $('.alert-success').hide()}, 5000);
                }
                else if(response.success == 100){
                    $('.alert-warning').show()
                    setTimeout(function(){ $('.alert-warning').hide()}, 5000);
                }
                else{
                    $('.alert-danger').show()
                    setTimeout(function(){ $('.alert-danger').hide()}, 5000);
                }
            },
            error: function(jqXHR, status, exception) {
                alert("Something went wrong: " + exception);
            }
        });
    });
});
</script>