// Определение активного пункта главного меню
function setActiveMenuItem() {
    jQuery("#main-navigation").find(".nav-item").each(function (itemKey, itemValue) {
        linkHref = jQuery(itemValue).find(".nav-link").attr("href");
        if (linkHref == window.location.pathname) {
            jQuery(itemValue).addClass("active");
        } else {
            jQuery(itemValue).removeClass("active");
        }
    });
}

jQuery(document).ready(function () {
    setActiveMenuItem();
});

jQuery("[data-toggle=confirmation]").confirmation({
    rootSelector: "[data-toggle=confirmation]",
    container: "body"
});
