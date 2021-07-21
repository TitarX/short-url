jQuery(document).ready(function () {
    var date_input = jQuery('input[name="link-edit-disabled-date"]');
    var container = jQuery(".bootstrap-iso form").length > 0 ? jQuery(".bootstrap-iso form").parent() : "body";
    date_input.datepicker({
        format: "dd.mm.yyyy",
        container: container,
        todayHighlight: true,
        autoclose: true
    });
})
