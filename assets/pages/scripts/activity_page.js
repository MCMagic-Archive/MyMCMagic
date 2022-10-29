$(document).ready(function(){
    //how much items per page to show
    var show_per_page = 10;
    //getting the amount of elements inside content div
    var number_of_items = $('#activity').children().size();
    //calculate the number of pages we are going to have
    var number_of_pages = Math.ceil(number_of_items/show_per_page);
    //set the value of our hidden input fields
    $('#activity_current_page').val(0);
    $('#activity_show_per_page').val(show_per_page);
    $('#activity_number_of_pages').val(number_of_pages);
    //now when we got all we need for the navigation let's make it '
    /*
    what are we going to have in the navigation?
        - link to previous page
        - links to specific pages
        - link to next page
    */
    var navigation_html = '<li class="disabled" id="activity_previous"><a href="javascript:activity_previous()"><i class="material-icons">Prev</i></a></li>';
    var current_link = 0;
    while(number_of_pages > current_link){
        navigation_html += '<li class="waves-effect activity" longdesc="' + current_link +'"><a href="javascript:activity_go_to_page(' + current_link +')">'+ (current_link + 1) +'</a></li>';
        current_link++;
    }
    navigation_html += '<li class="waves-effect" id="activity_next"><a href="javascript:activity_next()"><i >Next</i></a></li>';
    $('#activity_page_navigation').html(navigation_html);
    //add active class to the first page link
    $('#activity_page_navigation .waves-effect:first').addClass('active');
    //hide all the elements inside content div
    $('#activity').children().css('display', 'none');
    //and show the first n (show_per_page) elements
    $('#activity').children().slice(0, show_per_page).css('display', 'table-row');
});

function activity_previous(){
    new_page = parseInt($('#activity_current_page').val()) - 1;
    number_of_pages = parseInt($('#activity_number_of_pages').val());
    if(new_page >= 0){
        activity_go_to_page(new_page);
    }/*
    //if there is an item before the current active link run the function
    if($('.active').prev('.waves-effect').length==true){
        go_to_page(new_page);
    }*/
}

function activity_next(){
    new_page = parseInt($('#activity_current_page').val()) + 1;
    number_of_pages = parseInt($('#activity_number_of_pages').val());
    if(new_page <= (number_of_pages-1)){
        activity_go_to_page(new_page);
    }/*
    //if there is an item after the current active link run the function
    if($('.active').next('.waves-effect').length==true){
        go_to_page(new_page);
    }*/
}
function activity_go_to_page(page_num){
    //get the number of items shown per page
    var show_per_page = parseInt($('#activity_show_per_page').val());
    //get the element number where to start the slice from
    start_from = page_num * show_per_page;
    //get the element number where to end the slice
    end_on = start_from + show_per_page;
    //hide all children elements of content div, get specific items and show them
    $('#activity').children().css('display', 'none').slice(start_from, end_on).css('display', 'table-row');
    /*get the page link that has longdesc attribute of the current page and add active class to it
    and remove that class from previously active page link*/
    $('.activity[longdesc=' + page_num +']').addClass('active').siblings('.activity.active').removeClass('active');
    //update the current page input field
    $('#activity_current_page').val(page_num);
    number_of_pages = parseInt($('#activity_number_of_pages').val());
    if(page_num >= (number_of_pages-1)){
        $('#activity_next').removeClass('waves-effect').addClass('disabled');
    }
    if(page_num < (number_of_pages-1)){
        $('#activity_next').addClass('waves-effect').removeClass('disabled');
    }
    if(page_num <= 0){
        $('#activity_previous').removeClass('waves-effect').addClass('disabled');
    }
    if(page_num > 0){
        $('#activity_previous').addClass('waves-effect').removeClass('disabled');
    }
}