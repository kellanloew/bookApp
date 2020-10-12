@extends('layouts.mainlayout')

<head>
    <title>Reading List - Book Details</title>
</head>
@section('content')

<div class="container-fluid">
    <h1>Book Details</h1>
    <div class='row'>
        <div class='col-lg-2'>
            <p>Title: {{$book->title}}</p>
            <img src="<?php echo $book->thumbnail ?>" alt="">
        </div>
        <div class='col-lg-2'>
            <p>Author: {{$book->author}}</p>
        </div>
        <div class='col-lg-2'>
            <p>Pages: {{$book->pages}}</p>
        </div>
        <div class='col-lg-6'>
            <p>Description: {{$book->description}}</p>
        </div>
    </div>
</div>
@endsection
