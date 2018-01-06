/**
 * Created by Samuel on 17/11/2017.
 */

var imageList;

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
    var intervalID;
    var name = path.split("/").pop();
    var onlyPath = path.substring(0,path.length - name.length)
    getImageList()
    $("body").append("<div class='viewer'><div class='cross'></div><div class='container-img'><img  class='imgViewer' src='"+path+"'><div class='control'><div class='full-back'></div><div class='back'></div><div class='start'></div><div class='pause'></div><div class='next'></div><div class='full-next'></div></div></div></div>")
    $("body").css("overflow","hidden")

    $(".cross").click(function(){
        $("body").css("overflow","visible")
        $(".viewer").remove()
    })

    $(".full-back").click(function(){
        var firstName = imageList[0]
        $(".imgViewer").attr("src",onlyPath.concat(firstName))
        name = firstName
    })

    $(".back").click(function(){
        var predName = imageList[getPred(name)]
        $(".imgViewer").attr("src",onlyPath.concat(predName))
        name = predName
    })

    $(".start").click(function(){
        console.log(intervalID)
        if(intervalID == undefined) {
            intervalID = setInterval(function () {
                var predName = imageList[getNext(name)]
                $(".imgViewer").attr("src", onlyPath.concat(predName))
                name = predName
            }, 3500);
        }
    })

    $(".pause").click(function(){
        clearInterval(intervalID); // useless ?
        intervalID = undefined
    })

    $(".next").click(function(){
        var nextName = imageList[getNext(name)]
        $(".imgViewer").attr("src",onlyPath.concat(nextName))
        name = nextName
    })

    $(".full-next").click(function(){
        var lastName = imageList[imageList.length-1]
        $(".imgViewer").attr("src",onlyPath.concat(lastName))
        name = lastName
    })

    $("img, .control").mouseover(function(){
        $(".control").show()
    })
    $("img, .control").mouseout(function(){
        $(".control").hide()
    })
}

function getImageList(){
    var json = document.getElementById('list').textContent;
    obj = JSON.parse(json);
    imageList = $.map(obj, function(el) { return el });
}

function getPred($imgName){
    var index = imageList.indexOf($imgName)
    if(index == 0){
        return imageList.length - 1
    }
    return index-1;
}

function getNext($imgName){
    var index = imageList.indexOf($imgName)
    if(index == imageList.length - 1){
        return 0
    }
    return index+1;
}

function goTo(path){
    $(".container-img > img").remove()
    $(".container-img").prepend("<img src='"+path+"'>")
}