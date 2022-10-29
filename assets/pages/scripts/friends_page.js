$(document).ready(function(){
    //how much items per page to show
    var show_per_page = 7;
    //getting the amount of elements inside content div
    var number_of_items = $('#friends').children().size();
    //calculate the number of pages we are going to have
    var number_of_pages = Math.ceil(number_of_items/show_per_page);
    //set the value of our hidden input fields
    $('#friends_current_page').val(0);
    $('#friends_show_per_page').val(show_per_page);
    $('#friends_number_of_pages').val(number_of_pages);
    //now when we got all we need for the navigation let's make it '
    /*
    what are we going to have in the navigation?
        - link to previous page
        - links to specific pages
        - link to next page
    */
    var navigation_html = '<li class="disabled" id="friends_previous"><a href="javascript:friends_previous()"><i class="material-icons">Prev</i></a></li>';
    var current_link = 0;
    while(number_of_pages > current_link){
        navigation_html += '<li class="waves-effect friends" longdesc="' + current_link +'"><a href="javascript:friends_go_to_page(' + current_link +')">'+ (current_link + 1) +'</a></li>';
        current_link++;
    }
    navigation_html += '<li class="waves-effect" id="friends_next"><a href="javascript:friends_next()"><i >Next</i></a></li>';
    $('#friends_page_navigation').html(navigation_html);
    //add active class to the first page link
    $('#friends_page_navigation .waves-effect:first').addClass('active');
    //hide all the elements inside content div
    $('#friends').children().css('display', 'none');
    //and show the first n (show_per_page) elements
    $('#friends').children().slice(0, show_per_page).css('display', 'table-row');
});

function friends_previous(){
    new_page = parseInt($('#friends_current_page').val()) - 1;
    number_of_pages = parseInt($('#friends_number_of_pages').val());
    if(new_page >= 0){
        friends_go_to_page(new_page);
    }/*
    //if there is an item before the current active link run the function
    if($('.active').prev('.waves-effect').length==true){
        go_to_page(new_page);
    }*/
}

function friends_next(){
    new_page = parseInt($('#friends_current_page').val()) + 1;
    number_of_pages = parseInt($('#friends_number_of_pages').val());
    if(new_page <= (number_of_pages-1)){
        friends_go_to_page(new_page);
    }/*
    //if there is an item after the current active link run the function
    if($('.active').next('.waves-effect').length==true){
        go_to_page(new_page);
    }*/
}
function friends_go_to_page(page_num){
    //get the number of items shown per page
    var show_per_page = parseInt($('#friends_show_per_page').val());
    //get the element number where to start the slice from
    start_from = page_num * show_per_page;
    //get the element number where to end the slice
    end_on = start_from + show_per_page;
    //hide all children elements of content div, get specific items and show them
    $('#friends').children().css('display', 'none').slice(start_from, end_on).css('display', 'table-row');
    /*get the page link that has longdesc attribute of the current page and add active class to it
    and remove that class from previously active page link*/
    $('.friends[longdesc=' + page_num +']').addClass('active').siblings('.active').removeClass('active');
    //update the current page input field
    $('#friends_current_page').val(page_num);
    number_of_pages = parseInt($('#friends_number_of_pages').val());
    if(page_num >= (number_of_pages-1)){
        $('#friends_next').removeClass('waves-effect').addClass('disabled');
    }
    if(page_num < (number_of_pages-1)){
        $('#friends_next').addClass('waves-effect').removeClass('disabled');
    }
    if(page_num <= 0){
        $('#friends_previous').removeClass('waves-effect').addClass('disabled');
    }
    if(page_num > 0){
        $('#friends_previous').addClass('waves-effect').removeClass('disabled');
    }
}