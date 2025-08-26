let queryStringObject = {};

$(document).ready(function () {

    // Trigger filter refresh on filter change or sort dropdown
    $(document).on('change', '.filterAjax, .getsort', function () {
        RefreshFilters();
    });

    // Trigger filter refresh on price range button
    $(document).on('click', '#pricesort', function () {
        RefreshFilters();
    });
});

function RefreshFilters() {
    queryStringObject = {};

    // Collect all checked filters
    $(".filterAjax:checked").each(function () {
        const rawName = $(this).attr("name"); // e.g. fabric[], brand[], color[]
        if (!rawName) return;

        const name = rawName.replace(/\[\]$/, ''); // remove [] from name
        if (!queryStringObject[name]) {
            queryStringObject[name] = [];
        }

        queryStringObject[name].push($(this).val());
    });

    // Manual price range inputs (optional)
    const minprice = parseInt($('#from_range').val()) || 0;
    const maxprice = parseInt($('#to_range').val()) || 0;
    if (minprice !== 0 || maxprice !== 0) {
        queryStringObject["price_range"] = [`${minprice}-${maxprice}`];
    }

    // Sorting dropdown
    const sortValue = $('.getsort').val();
    const sortName = $('.getsort').attr('name');
    if (sortName && sortValue) {
        queryStringObject[sortName] = [sortValue];
    }

    // Now call filter function
    filterProduct(queryStringObject);
}

function filterProduct(queryParams) {
    $('body').css({ 'overflow': 'hidden' });

    const searchParams = new URLSearchParams(window.location.search);
    let baseQuery = searchParams.has('q') ? "?q=" + encodeURIComponent(searchParams.get('q')) : "";

    // Build query string
    let queryString = baseQuery;
    for (let key in queryParams) {
        if (queryParams[key].length > 0) {
            queryString += (queryString === "" ? "?" : "&") + key + "=" + encodeURIComponent(queryParams[key].join("~"));
        }
    }

    // Construct clean browser URL (without json=true)
    let newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + queryString;
    newurl = newurl.replace("&undefined=undefined", "").replace("?&", "?");

    // Update browser history
    if (history.pushState) {
        history.pushState({ path: newurl }, '', newurl);
    }

    // Prepare AJAX URL (with json=true)
    const ajaxUrl = newurl + (newurl.includes("?") ? "&json=true" : "?json=true");

    // AJAX request
    $.ajax({
        url: ajaxUrl,
        type: 'GET',
        dataType: 'json',
        success: function (resp) {
            $("#appendProducts").html(resp.view);
            document.body.style.overflow = 'auto';
        },
        error: function () {
            console.error("Failed to fetch filtered products.");
            document.body.style.overflow = 'auto';
        }
    });
}
