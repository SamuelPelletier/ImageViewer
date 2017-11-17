var pagination = 22

var test = 100;

var nbrPage = Math.trunc(test / pagination)
if (test % pagination > 0) {
    nbrPage++
}
function parse_query_string(query) {
    var vars = query.split("&");
    var query_string = {};
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split("=");
        // If first entry with this name
        if (typeof query_string[pair[0]] === "undefined") {
            query_string[pair[0]] = decodeURIComponent(pair[1]);
            // If second entry with this name
        } else if (typeof query_string[pair[0]] === "string") {
            var arr = [query_string[pair[0]], decodeURIComponent(pair[1])];
            query_string[pair[0]] = arr;
            // If third or later entry with this name
        } else {
            query_string[pair[0]].push(decodeURIComponent(pair[1]));
        }
    }
    return query_string;
}

var result = parse_query_string(document.URL.split("?").pop())
if (result.page <= 1 || result.page === undefined) {
    //videos = videos.slice(0, pagination)
    result.page = 1
} else {
    //videos = videos.slice((result.page - 1) * pagination, result.page * pagination)
}

var pagePrev = (result.page - 1 <= 0) ? 1 : result.page - 1
var pageNext = (result.page + 1 > nbrPage) ? nbrPage : result.page + 1

$('.container-fluid').append('<ul style="margin-left: calc(50% - 34px * ' + (nbrPage + 2) + '/2)" class="pagination"></ul>')
$('.pagination').append('<li><a href="?page=' + pagePrev + '" class="prev">&laquo</a></li>')
for (i = 1; i < nbrPage + 1; i++) {
    var active = (result.page == i ) ? 'active' : ''
    if (i === 1 && result.page === undefined) {
        active = 'active'
    }
    $('.pagination').append('<li><a class="page ' + active + '" href="?page=' + i + '">' + i + '</a></li>')
}
$('.pagination').append('  <li><a href="?page=' + pageNext + '" class="next">&raquo;</a></li>')