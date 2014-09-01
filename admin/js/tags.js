$('#mrptag_checked').change(function () {
        $(".mrp-options").toggle(this.checked);
});

$("#form-delete-tags").chosen({
    placeholder_text_single: " ",
    placeholder_text_multiple: " ",
    display_disabled_options: false,
    search_contains: true,
});