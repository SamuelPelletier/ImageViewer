/**
 * Created by Samuel on 17/11/2017.
 */

var imageList;
var flagLoad = false;

$( window ).on( "load", function() {
    var historyTraversal = event.persisted || ( typeof window.performance != "undefined" && window.performance.navigation.type === 2 );
  if ( historyTraversal ) {
    // Handle page restore.
    window.location.reload();
  }
    setTimeout(function(){
        if(flagLoad == false){
            $("body").append('<div class="loader"><div class="loader-inner"><div class="loader-line-wrap"><div class="loader-line"></div></div><div class="loader-line-wrap"><div class="loader-line"></div></div><div class="loader-line-wrap"><div class="loader-line"></div></div><div class="loader-line-wrap"><div class="loader-line"></div></div><div class="loader-line-wrap"><div class="loader-line"></div></div></div></div>');
        }
    }, 100); //wait 20 ms
});


$(document).ready(function () {
    var url = "/html/template_page.php";

    $.get(url, function (data) {
        $(".loader").fadeOut("slow");
        $("body").append(data);
        manageTag();
        if('search' in $_GET()){
            var searchData = $_GET()['search']
            searchData =  decodeURI(searchData);
            searchData = searchData.substr(1)
            searchData = searchData.substring(0, searchData.length-1)
            $("#search").val(searchData)
        }
        $('#search').keypress(function(e) {
            var keycode = (e.keyCode ? e.keyCode : e.which);
            if (keycode == '13') {
                search();
            }
        });
        flagLoad = true;

        $("#search").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".list li").filter(function() {
              $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
          });

        reloadListFavorite();

        var tags = getHidenTagsCookie();
        $.each($('.list-item'),function(){
            if($.inArray( $(this).find('.about-tag').text().slice(0,-1),tags) != -1){
                $(this).find('.show-tag').show();
                $(this).find('.hide-tag').hide();
                $(this).find('.hand').addClass('visible')
            }else{
                $(this).find('.show-tag').hide();
                $(this).find('.hide-tag').show();
                $(this).find('.hand').removeClass('visible')
            }
        })
    });

})

function search(){
    var param = $_GET();
    if('number' in param){
        delete param['number'];
    }
    var pathname = window.location.pathname == "/about" ||  window.location.pathname == "/about/" ? "/" : window.location.pathname;
    if('search' in param || Object.keys(param).length > 0){
        if(param['page'] > 1){
            param['page'] = 1
        }
        param['search'] = "'"+$("#search").val()+"'";
        newUrl = window.location.origin + pathname + '?' + Object.keys(param).map(key => key + '=' + param[key]).join('&')
    }else{
        newUrl = window.location.origin + pathname +'?'+"search='"+$("#search").val()+"'"
    }
    window.location = newUrl;
}

function createPagination(pagination, nbrItems, page) {

    var url = new URL(document.URL);
    var safe = url.searchParams.get('safe');
    var page = url.searchParams.get('page') != null ? parseInt(url.searchParams.get('page')) : 1;
    var param = $_GET();
    delete param['page'];
    var safeUrl =  '&'+Object.keys(param).map(key => key + '=' + param[key]).join('&');
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

    $('.container-fluid').append('<div class="pagination-block"><ul class="pagination"></ul></div>')
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
    var intervalID = undefined;
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
        clearInterval(intervalID);
        intervalID = undefined;
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
                $(".imgViewer").fadeTo(1000,0.1);
                setTimeout(function () {
                    $(".imgViewer").attr("src", onlyPath.concat(predName)).fadeTo(1000,1)
                }, 1000);
                name = predName
            },  $(".input-time").val()*1000);
        }
    })

    $(".start").hover(function () {
        if($(".time").length == 0){
            $(".start").after("<div class='time'></div>")
            $(".time").hide()
            $(".time").append("<input class='input-time' min='2' max='99' value='5'></input>")
            $(".time").append("<label class='time-second'>s</label>")
        }
        $(".time").fadeIn("slow")

        $(".time").hover(function () {
            $(".time-second").remove()
            $(".input-time").prop('type', 'number');
            $(".input-time").css('text-align','right')
            $(".input-time").css('width','36px')
        }, function(){
            if($(".time-second").length == 0){
                $(".time").append("<label class='time-second'>s</label>")
            }
            if($(".input-time").val() == ''){
                $(".input-time").val(5)
            }
            $(".input-time").css('width','28px')
            $(".input-time").css('text-align','center')
            $(".input-time").prop('type', 'text');
            $(".input-time").css('text-align','center')
            $(".time").fadeOut("slow")
        })
        
    })

    

    $(".pause").click(function () {
        $(".pause").hide()
        $(".start").show()
        clearInterval(intervalID);
        intervalID = undefined;
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

    $("img").mousemove(function (e) {
        if (ready == true) {
            ready = false
            $(".control").fadeIn("slow", function () {
                ready = true
            })
        }
    })

    $("img").mouseout(function (e) {
        if (e.relatedTarget && !["control", "start", "pause", "back", "next", "full-back", "full-next","time","input-time","time-second"].includes(e.relatedTarget.className)) {
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

function $_GET(param) {
	var vars = {};
	window.location.href.replace( location.hash, '' ).replace( 
		/[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
		function( m, key, value ) { // callback
			vars[key] = value !== undefined ? value : '';
		}
	);

	if ( param ) {
		return vars[param] ? vars[param] : null;	
	}
	return vars;
}

function manageTag(){
    var path = "/php/add_tag.php?name="+$("h1").text()
    var listTag = []
    $.each($('.tag'),function(key,value){
        listTag.push($(value).text().slice(0,-1))
    })
    
    $('.tag, .about-tag').click(function(){
        $("#search").val($(this).text().slice(0,-1))
        search()
    })

    $('.add-tag').hide()
    $('.valid-tag').hide()
    $('.remove').hide()
    $(".dropdown").on("click", ".modify-tag", function() {
        $('.modify-tag').hide("slow")
        $('.add-tag').show("slow")
        $('.valid-tag').show("slow")
        $('.remove').show()
    });
    $(".dropdown").on("click", ".valid-tag", function() {
        $('.remove').hide()
        $('.add-tag').hide("slow")
        $('.valid-tag').hide("slow")
        $('.modify-tag').show("slow")
        $(".dropdown").removeClass("open");
        if(listTag.length > 0){
            path = path + "&tags=";
            $.each(listTag,function(key,value){
                if(key == listTag.length-1){
                    path = path + value
                }else{
                    path = path + value + ","
                }
            })
            window.open(path)
            $(".help-us").html("Thank you !")
        }
    });
        // Dropdown
    $(".dropdown").on("click", ".add-tag", function() {
        
        if ( $(".dropdown").hasClass("open") ) {
            $(".dropdown").removeClass("open");
        } else {
            $(".dropdown").addClass("open");
        }
    });

    // Add Tags
    $(".dropdown").on("click", ".dropdown-menu > li", function() {
        if ( !$(this).hasClass("added") ) {
            $(this).addClass("added");
            listTag.push($(this).text())
            $(".tag-area").append('<div class="tag">' + $(this).text() + '<span class="remove">×</span></div>');
        }
    });

    // Remove Tags
    $(".tag-area").on("click", ".tag > span", function() {
        $(this).parent().remove();
        listTag.splice( listTag.indexOf($(this).parent().text().slice(0,-1)), 1 );
        var objectText = $(this).parent().text().slice(0,-1);
        
        $(".dropdown-menu > li:contains('" + objectText + "')").removeClass("added");
    });
  
}

function setFavoriteCookie(cvalue) {
    $.cookie.json = true;
    $.cookie("FavoriteList", cvalue, { expires : 365, path: '/'});
}

function getFavoriteCookie() {
    $.cookie.json = true;
    return $.cookie("FavoriteList")
}

function addFavorite(id, name){
    var pref = {}
    if(getFavoriteCookie() !== undefined){
        pref = getFavoriteCookie()
    }
    pref[id] = name;
    setFavoriteCookie(pref)
    reloadListFavorite();
    $(".pulse-div-add").hide();
    $(".pulse-div-remove").show();
}

function removeFavorite(id){
    var pref = {}
    if(getFavoriteCookie() !== undefined){
        pref = getFavoriteCookie()
    }
    delete pref[id]
    setFavoriteCookie(pref)
    reloadListFavorite();
    $(".pulse-div-remove").hide();
    $(".pulse-div-add").show();
}

function chooseFavorite(id){
    newUrl = window.location.origin + "/" +'?'+"number="+id
    window.location = newUrl;
}

function reloadListFavorite(){
    var dropdown = $(".dropdown-favorite-select");
    dropdown.empty()
    dropdown.append('<option value="" disabled selected>Your favorite..</option>');
    data = getFavoriteCookie();
    if(data === undefined){
        data = {}
    }
    $.each(data, function(index, value){
        dropdown.append($("<option />").val(index).text(value));
    });
    if($_GET()['number'] in data){
        $(".pulse-div-add").hide()
    }else{
        $(".pulse-div-remove").hide()
    }
}

function setHidenTagsCookie(cvalue) {
    $.cookie.json = true;
    $.cookie("HidenTags", cvalue, { expires : 365, path: '/'});
}

function getHidenTagsCookie() {
    $.cookie.json = true;
    return $.cookie("HidenTags")
}

function addHidenTags(name,myThis){
    var tags = []
    if(getHidenTagsCookie() !== undefined){
        tags = getHidenTagsCookie()
    }
    tags.push(name);
    setHidenTagsCookie(tags)
    myThis.next().find('.hand').removeClass('visible');
    myThis.hide();
    myThis.next().show();
    myThis.next().find('.hand').addClass('visible');
}

function removeHidenTags(name,myThis){
    var tags = []
    if(getHidenTagsCookie() !== undefined){
        tags = getHidenTagsCookie()
    }
    tags = jQuery.grep(tags, function(value) {
        return value != name;
      });
    setHidenTagsCookie(tags)
    myThis.prev().find('.hand').addClass('visible');
    myThis.hide();
    myThis.prev().show();
    myThis.prev().find('.hand').removeClass('visible');
}