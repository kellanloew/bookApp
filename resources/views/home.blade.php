@extends('layouts.mainlayout')

<head>
    <title>Reading List - Home</title>
    <link href="http://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">  
    <script src="http://code.jquery.com/jquery-1.10.2.js"></script>  
    <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script> 
</head>
@section('content')
<body id='home-body'>
<div class="container-fluid">
    <h1>Your Personalized Book List</h1>
    @if(count($books) > 0)
    <p>You may drag and drop any of the titles to reorder the list. Clicking the red "x" button will remove that book from the list.</p>
    @endif
    <div class="row">
        <div class="col-lg-12" id='book-list'>
        @if(isset($books))
            @foreach($books as $book)
                <div class='row row-padding' style='border: 1px solid;' id='<?php echo $book->position ?>' ISBN='<?php echo $book->ISBN ?>'>
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
    <div class='row row-padding'>
        @if(count($books) > 0)
        <button class='btn btn-sm btn-info' id="sort-title">Sort by title</button>
        @endif
        <a class='btn btn-sm btn-dark' href="/search" target="_blank">Search for a new book to add</a>
    </div>
</div>
</body>
@endsection
</html>
