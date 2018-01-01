/**
 * Created by Samuel on 17/11/2017.
 */
$(document).ready(function () {
    $.get("../html/template_page.php", function (data) {
        $("body").append(data);
    });
})
function createPagination(pagination, nbrItems, page) {

    var nbrPage = Math.trunc(nbrItems / pagination)
    if (nbrItems % pagination > 0) {
        nbrPage++
    }
    var pagePrev = (page - 1 <= 0) ? 1 : page - 1
    var pageNext = (page + 1 > nbrPage) ? nbrPage : page + 1

    $('.container-fluid').append('<ul style="margin-left: calc(50% - 34px * ' + (nbrPage + 2) + '/2)" class="pagination"></ul>')
    $('.pagination').append('<li><a href="?page=' + pagePrev + '" class="prev">&laquo</a></li>')
    for (i = 1; i <= nbrPage; i++) {
        var active = (page == i ) ? 'active' : ''
        $('.pagination').append('<li><a class="page ' + active + '" href="?page=' + i + '">' + i + '</a></li>')
    }
    $('.pagination').append('  <li><a href="?page=' + pageNext + '" class="next">&raquo;</a></li>')
}

function viewer(path){
    $("body").append("<div class='viewer'><div class='cross'></div><div class='container-img'><img src='"+path+"'><div class='control'><div class='full-back'></div><div class='back'></div><div class='start'></div><div class='pause'></div><div class='next'></div><div class='full-next'></div></div></div></div>")
    $("body").css("overflow","hidden")

    $(".cross").click(function(){
        $("body").css("overflow","visible")
        $(".viewer").remove()
    })

    $(".full-back").click(function(){
        console.log("full back")
    })

    $(".back").click(function(){
        console.log("back")
    })

    $(".start").click(function(){
        console.log("start")
    })

    $(".pause").click(function(){
        console.log("pause")
    })

    $(".next").click(function(){
        console.log("next")
    })

    $(".full-next").click(function(){
        console.log("full next")
    })
}

function goTo(path){
    $(".container-img > img").remove()
    $(".container-img").prepend("<img src='"+path+"'>")
}