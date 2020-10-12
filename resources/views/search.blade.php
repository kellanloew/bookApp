@extends('layouts.mainlayout')
<head>
    <title>Reading List - Search</title>
    <link href="http://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">  
    <script src="http://code.jquery.com/jquery-1.10.2.js"></script> 

</head>
@section('content')

<div class="container-fluid">
    <h1>Search for a book</h1>
    <div class="row">
        <div class="col-lg-12">
            <form action="" method="POST">
                {{ csrf_field() }}
                <p>After entering a search term, press enter.</p>
                <input name="keyword" />
            </form>
        </div>
    </div>
    <div class="row">
        <div class='alert alert-success'>That book was added successfully!</div>
        <div class='alert alert-warning'>That book already exists.</div>
        <div class='alert alert-danger'>Something went wrong while trying to add that book.</div>
    </div>
    <div class="row row-padding">
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
</div>

@endsection