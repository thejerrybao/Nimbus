$(document).ready(function() {

    $("#search-roster-form > #search-words").keyup(function() {
        $.post("search.php", { 
            search_type : "roster",
            search_words :  $("#search-roster-form > #search-words").val(), 
            search_category : $("#search-roster-form > #search-category option:selected").val()
        },
        function(roster) {
            $("tbody#search-roster-result").empty().append(roster);
        });
    });

    $("#search-roster-form > #search-category").change(function() {
        $.post("search.php", {
            search_type : "roster",
            search_words :  $("#search-roster-form > #search-words").val(), 
            search_category : $("#search-roster-form > #search-category option:selected").val()
        },
        function(roster) {
            $("tbody#search-roster-result").empty().append(roster);
        });
    });
});
