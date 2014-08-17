$("#start-datetime").keyup(function() {
    var endDate = Date.parse($("#start-datetime").val()).add(3).hours().toString("yyyy-MM-ddTHH:mm:ss");
    var onlineEndDate = Date.parse($("#start-datetime").val()).add(-1).days().toString("yyyy-MM-ddTHH:mm:ss");
    $("#end-datetime").val(endDate);
    $("#online-end-datetime").val(onlineEndDate);
});