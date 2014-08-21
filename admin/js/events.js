$("#start-datetime").keyup(function() {
    var endDate = Date.parse($("#start-datetime").val()).add(3).hours().toString("yyyy-MM-ddTHH:mm:ss");
    var onlineEndDate = Date.parse($("#start-datetime").val()).add(-1).days().toString("yyyy-MM-ddTHH:mm:ss");
    $("#end-datetime").val(endDate);
    $("#online-end-datetime").val(onlineEndDate);
<<<<<<< HEAD
=======
});

$("#form-event-tags, #form-event-chair, #form-add-event-attendees, #form-delete-event-attendees").chosen({
    placeholder_text_single: " ",
    placeholder_text_multiple: " ",
    display_disabled_options: false,
    search_contains: true,
>>>>>>> FETCH_HEAD
});