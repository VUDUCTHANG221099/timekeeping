$(document).ready(function () {
    function search() {
        $("#keyword").on("keyup", function (event) {
            event.preventDefault();
            var keys = $(this).val().toLowerCase();

            $(".search").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(keys) > -1);
            });
        });
    }
    function searchOnTable() {
        $("#keywords").on("keyup", function (event) {
            event.preventDefault();
            var keys = $(this).val().toLowerCase();
            $("tr").filter(function () {
                $("thead").html($("thead").html());

                $(this).toggle($(this).text().toLowerCase().indexOf(keys) > -1);
            });
        });
    }
    search();
    searchOnTable();
});
