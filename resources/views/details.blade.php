@extends('layouts.mainlayout')

<head>
    <title>Book Details</title>
</head>
@section('content')

<div class="container-fluid">
    <h1>Book Details</h1>
    <div class='row'>
        <div class='col-lg-3'>
            <p>Title: {{$book->title}}</p>
        </div>
        <div class='col-lg-2'>
            <p>Author: {{$book->author}}</p>
        </div>
        <div class='col-lg-1'>
            <p>Pages: {{$book->pages}}</p>
        </div>
        <div class='col-lg-6'>
            <p>Description: {{$book->description}}</p>
        </div>
    </div>
</div>
@endsection