$(document).ready(function() {

    $("#search-users-form > #search-words").keyup(function() {
        $.post("search.php", { 
            search_type : "users",
            search_words :  $("#search-users-form > #search-words").val(), 
            search_category : $("#search-users-form > #search-category option:selected").val()
        },
        function(users) {
            $("tbody#search-users-result").empty().append(users);
        });
    });

    $("#search-users-form > #search-category").change(function() {
        $.post("search.php", {
            search_type : "users",
            search_words :  $("#search-users-form > #search-words").val(), 
            search_category : $("#search-users-form > #search-category option:selected").val()
        },
        function(users) {
            $("tbody#search-users-result").empty().append(users);
        });
    });
});
