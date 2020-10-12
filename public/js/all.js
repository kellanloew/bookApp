$(document).ready(function() {
    
    ////////////////////
    //HOME PAGE
    ////////////////////
    
    //MAKE BOOK LIST SORTABLE
    $("#book-list").sortable({  
        //call back function for when a drag event is begun
        start: function(e, ui) {
            // creates a temporary attribute on the element with the old index
            $(this).attr('data-previndex', ui.item.index());
        },
        //call back function for when a drag event results in an updated position and is finished
        update: function(event, ui) {  

            //previous index of moved book, fetched from data-previndex attribute 
            var oldIndex = parseInt($(this).attr('data-previndex')) + 1;

            // new index of moved book because the ui.item is the node
            var newIndex = ui.item.index() + 1;

            $.ajax({
                url: "/dragdrop",
                data: {
                    oldIndex: oldIndex,
                    newIndex:newIndex,
                },
                dataType: "json",
                error: function(jqXHR, status, exception) {
                    alert("Something went wrong: " + exception);
                }
            });
        }  
    });

    //handler for sorting the book titles alphabetically 
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

    ////////////////////
    //SEARCH PAGE
    ////////////////////

    //handler for submitting form to add a book to list
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