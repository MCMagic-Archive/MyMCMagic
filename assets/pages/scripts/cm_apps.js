$(document).ready(function(){
    //how much items per page to show
    var show_per_page = 5;
    //getting the amount of elements inside content div
    var number_of_items = $('#cm').children().size();
    //calculate the number of pages we are going to have
    var number_of_pages = Math.ceil(number_of_items / show_per_page);
    //set the value of our hidden input fields
    $('#cm_current_page').val(0);
    $('#cm_show_per_page').val(show_per_page);
    $('#cm_number_of_pages').val(number_of_pages);
    //now when we got all we need for the navigation let's make it '
    /*
    what are we going to have in the navigation?
        - link to previous page
        - links to specific pages
        - link to next page
    */
    var navigation_html = '<li class="disabled" id="cm_previous"><a href="javascript:cm_previous()"><i class="material-icons">Prev</i></a></li>';
    var current_link = 0;
    var maxPages = 7;
    var isHidden = false;
    while (number_of_pages > current_link) {
        var hidden = (number_of_pages > 10) && (current_link >= maxPages) && (current_link < (number_of_pages - 1));
        if (number_of_pages > 10) {
            if (current_link == 1) {
                navigation_html += '<li class="disabled" style="display:none;" id="cm_low"><a>...</a></li>';
            }
            if (hidden && !isHidden && (current_link == (number_of_pages - 2))) {
                isHidden = true;
                navigation_html += '<li class="disabled" style="display:inline;" id="cm_high"><a>...</a></li>';
            }
        }
        navigation_html += '<li class="waves-effect cm" longdesc="' + current_link + '"' + (hidden ? 'style="display:none;"' : '') + '><a href="javascript:cm_go_to_page(' + current_link + ')">' + (current_link + 1) + '</a></li>';
        current_link++;
    }
    navigation_html += '<li class="waves-effect" id="cm_next"><a href="javascript:cm_next()"><i >Next</i></a></li>';
    $('#cm_page_navigation').html(navigation_html);
    //add active class to the first page link
    $('#cm_page_navigation .waves-effect:first').addClass('active');
    //hide all the elements inside content div
    $('#cm').children().css('display', 'none');
    //and show the first n (show_per_page) elements
    $('#cm').children().slice(0, show_per_page).css('display', 'table-row');
});

function cm_previous() {
    new_page = parseInt($('#cm_current_page').val()) - 1;
    number_of_pages = parseInt($('#cm_number_of_pages').val());
    if (new_page >= 0) {
        cm_go_to_page(new_page);
    }
    /*
        //if there is an item before the current active link run the function
        if($('.active').prev('.waves-effect').length==true){
            go_to_page(new_page);
        }*/
}

function cm_next() {
    new_page = parseInt($('#cm_current_page').val()) + 1;
    number_of_pages = parseInt($('#cm_number_of_pages').val());
    if (new_page <= (number_of_pages - 1)) {
        cm_go_to_page(new_page);
    }
    /*
        //if there is an item after the current active link run the function
        if($('.active').next('.waves-effect').length==true){
            go_to_page(new_page);
        }*/
}

function cm_go_to_page(page_num) {
    //get the number of items shown per page
    var show_per_page = parseInt($('#cm_show_per_page').val());
    //get the element number where to start the slice from
    start_from = page_num * show_per_page;
    //get the element number where to end the slice
    end_on = start_from + show_per_page;
    //hide all children elements of content div, get specific items and show them
    $('#cm').children().css('display', 'none').slice(start_from, end_on).css('display', 'table-row');
    /*get the page link that has longdesc attribute of the current page and add active class to it
    and remove that class from previously active page link*/
    $('.cm[longdesc=' + page_num + ']').addClass('active').siblings('.active').removeClass('active');
    //update the current page input field
    $('#cm_current_page').val(page_num);
    number_of_pages = parseInt($('#cm_number_of_pages').val());
    if (page_num >= (number_of_pages - 1)) {
        $('#cm_next').removeClass('waves-effect').addClass('disabled');
    }
    if (page_num < (number_of_pages - 1)) {
        $('#cm_next').addClass('waves-effect').removeClass('disabled');
    }
    if (page_num <= 0) {
        $('#cm_previous').removeClass('waves-effect').addClass('disabled');
    }
    if (page_num > 0) {
        $('#cm_previous').addClass('waves-effect').removeClass('disabled');
    }
    if (page_num < 4 && $('#cm_low').css("display") == "inline") {
        $('#cm_low').css("display", "none");
    }
    if (page_num >= 4 && $('#cm_low').css("display") == "none") {
        $('#cm_low').css("display", "inline");
    }
    if (page_num >= (number_of_pages - 4) && $('#cm_high').css("display") == "inline") {
        $('#cm_high').css("display", "none");
    }
    if (page_num < (number_of_pages - 4) && $('#cm_high').css("display") == "none") {
        $('#cm_high').css("display", "inline");
    }
    if (page_num >= 4 && page_num < (number_of_pages - 4)) {
        for (var i = 1; i < number_of_pages; i++) {
            if (i < (page_num - 2) || i > (page_num + 2) && i != (number_of_pages - 1)) {
                $('.cm[longdesc=' + i + ']').css("display", "none");
            } else {
                $('.cm[longdesc=' + i + ']').css("display", "inline");
            }
        }
    } else if (page_num >= (number_of_pages - 4)) {
        for (var i = 1; i < number_of_pages; i++) {
            if (i < (number_of_pages - 7) && i != 0) {
                $('.cm[longdesc=' + i + ']').css("display", "none");
            } else {
                $('.cm[longdesc=' + i + ']').css("display", "inline");
            }
        }
    } else {
        for (var i = 1; i < number_of_pages; i++) {
            if (i > 6 && i != (number_of_pages - 1)) {
                $('.cm[longdesc=' + i + ']').css("display", "none");
            } else {
                $('.cm[longdesc=' + i + ']').css("display", "inline");
            }
        }
    }
    $('#page_input').val(page_num + 1);
    /*
    //Going to 5
    if (page_num >= 5) {
        $('.cm[longdesc=' + (page_num - 3) + ']').css("display", "none");
        $('.cm[longdesc=' + (page_num + 2) + ']').css("display", "inline");
    }*/
}