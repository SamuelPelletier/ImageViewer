/**
 * Created by Samuel on 17/11/2017.
 */

var imageList;

$(document).ready(function () {
    var url;
    var websiteURL = document.URL;
    if (websiteURL.includes("import") || websiteURL.includes("about") || websiteURL.includes("upload")) {
        url = "../html/template_page.php";
    } else if (websiteURL.includes("all")) {
        url = "../../html/template_page.php";
    } else {
        url = "html/template_page.php";
    }

    $.get(url, function (data) {
        $("body").append(data);
    });
})

function createPagination(pagination, nbrItems, page) {

    var url = new URL(document.URL);
    var safe = url.searchParams.get('safe');
    var page = url.searchParams.get('page') != null ? parseInt(url.searchParams.get('page')) : 1;
    var safeUrl = "";
    if (safe == 'false') {
        safeUrl = "&safe=false"
    }
    var i = page - (page-1);
    if (page > 5) {
        i = page - 5
    }

    var nbrPage = Math.trunc(nbrItems / pagination)
    if (nbrItems % pagination > 0) {
        nbrPage++
    }
    var size;
    if (nbrPage < 10) {
        size = nbrPage / 2;
    } else if (page < 5) {
        size = page + 5 / 2
    } else {
        size = 10
    }

    var pagePrev = (page - 1 <= 0) ? 1 : page - 1
    var pageNext = (page + 1 > nbrPage) ? nbrPage : page + 1

    $('.container-fluid').append('<ul style="margin-left: calc(50% - 34px *' + size + ')" class="pagination"></ul>')
    $('.pagination').append('<li><a href="?page=' + pagePrev + safeUrl + '" class="prev">&laquo</a></li>')
    for (i; i <= nbrPage; i++) { // 5 - 1 - 5
        var active = (page == i ) ? 'active' : ''
        $('.pagination').append('<li><a class="page ' + active + '" href="?page=' + i + safeUrl + '">' + i + '</a></li>')
        if (i > page + 4) { // Max 11 pages
            break
        }
    }
    $('.pagination').append('  <li><a href="?page=' + pageNext + safeUrl + '" class="next">&raquo;</a></li>')
}

function viewer(path) {
    toggleFullscreen()
    var intervalID;
    var name = path.split("/").pop();
    var onlyPath = path.substring(0, path.length - name.length)
    getImageList()
    $("body").css("overflow", "hidden")
    $("body").append("<div class='viewer' style='display: none'><div class='fullscreen'></div><div class='cross'></div><div class='container-img'><img  class='imgViewer' src=\"" + path + "\"><div class='control'><div class='full-back'></div><div class='backImage'></div><div class='start'></div><div class='pause'></div><div class='nextImage'></div><div class='full-next'></div></div></div></div>")
    $(".viewer").fadeIn("slow")

    $(".pause").hide()

    $(".cross").click(function () {
        $(".pause").hide()
        $(".start").show()
        clearInterval(intervalID); // useless ?
        intervalID = undefined
        $(".viewer").fadeOut(function () {
            $(".viewer").remove()
            $("body").css("overflow", "visible")
        })
    })

    $(".fullscreen").click(function () {
        var docElm = document.documentElement;
        if (docElm.requestFullscreen) {
            docElm.requestFullscreen();
        }
        else if (docElm.mozRequestFullScreen) {
            docElm.mozRequestFullScreen();
        }
        else if (docElm.webkitRequestFullScreen) {
            docElm.webkitRequestFullScreen();
        }
    })

    $(".full-back").click(function () {
        var firstName = imageList[0]
        $(".imgViewer").attr("src", onlyPath.concat(firstName))
        name = firstName
    })

    $(".backImage").click(function () {
        var predName = imageList[getPred(name)]
        $(".imgViewer").attr("src", onlyPath.concat(predName))
        name = predName
    })

    $(".start").click(function () {
        if (intervalID == undefined) {
            $(".start").hide();
            $(".pause").show()
            intervalID = setInterval(function () {
                var predName = imageList[getNext(name)]
                $(".imgViewer").attr("src", onlyPath.concat(predName))
                name = predName
            }, 3500);
        }
    })

    $(".pause").click(function () {
        $(".pause").hide()
        $(".start").show()
        clearInterval(intervalID); // useless ?
        intervalID = undefined
    })

    $(".nextImage").click(function () {
        var nextName = imageList[getNext(name)]
        $(".imgViewer").attr("src", onlyPath.concat(nextName))
        name = nextName
    })

    $(".full-next").click(function () {
        var lastName = imageList[imageList.length - 1]
        $(".imgViewer").attr("src", onlyPath.concat(lastName))
        name = lastName
    })

    var ready = true;

    $("img").mouseover(function (e) {
        if (ready == true) {
            ready = false
            $(".control").fadeIn("slow", function () {
                ready = true
            })
        }
    })

    $("img").mouseout(function (e) {
        if (e.relatedTarget && !["control", "start", "pause", "back", "next", "full-back", "full-next"].includes(e.relatedTarget.className)) {
            if (ready == true) {
                ready = false
                $(".control").fadeOut("slow", function () {
                    ready = true
                })
            }
        }
    })

    $(document).keydown(function (e) {
        switch (e.keyCode) {
            case 39:
                var nextName = imageList[getNext(name)]
                $(".imgViewer").attr("src", onlyPath.concat(nextName))
                name = nextName
                break
            case 37:
                var predName = imageList[getPred(name)]
                $(".imgViewer").attr("src", onlyPath.concat(predName))
                name = predName
                break
            case 32:
                if (intervalID == undefined) {
                    $(".start").hide();
                    $(".pause").show()
                    intervalID = setInterval(function () {
                        var predName = imageList[getNext(name)]
                        $(".imgViewer").attr("src", onlyPath.concat(predName))
                        name = predName
                    }, 3500);
                } else {
                    $(".pause").hide()
                    $(".start").show()
                    clearInterval(intervalID); // useless ?
                    intervalID = undefined
                }
                break
        }
    });
}

function getImageList() {
    var json = document.getElementById('list').textContent;
    obj = JSON.parse(json);
    imageList = $.map(obj, function (el) {
        return el
    });
}

function getPred($imgName) {
    var index = imageList.indexOf($imgName)
    if (index == 0) {
        return imageList.length - 1
    }
    return index - 1;
}

function getNext($imgName) {
    var index = imageList.indexOf($imgName)
    if (index == imageList.length - 1) {
        return 0
    }
    return index + 1;
}

function goTo(path) {
    $(".container-img > img").remove()
    $(".container-img").prepend("<img src='" + path + "'>")
}

$(document).on('change', 'input:file', function (event) {
    var files = event.target.files;
    var link = files[0].name;
    $('.f-name').html('<p>' + link + '</p>');
    $('#name').val(link);
    $('.upload.btn').click(function () {
        $('.f-name p').addClass('upload');
        setTimeout(function () {
            $('.f-name p').addClass('done');
        }, 3000);
    });
});

function toggleFullscreen() {
    document.addEventListener("fullscreenchange", function () {
        if (document.fullscreen) {
            $(".fullscreen").hide()
        } else {
            $(".fullscreen").show()
        }
    }, false);

    document.addEventListener("mozfullscreenchange", function () {
        if (document.mozFullScreen) {
            $(".fullscreen").hide()
        } else {
            $(".fullscreen").show()
        }
    }, false);

    document.addEventListener("webkitfullscreenchange", function () {
        if (document.webkitIsFullScreen) {
            $(".fullscreen").hide()
        } else {
            $(".fullscreen").show()
        }
    }, false);
}
