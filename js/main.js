/**
 * Created by Samuel on 17/11/2017.
 */
$.get("../html/template_page.php", function (data) {
    $("body").append(data);
});
