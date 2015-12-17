/**
 * Created by Blade on 17.12.2015.
 */

function getLocation()
{
    // try to get the location form the Browser
    if (!navigator.geolocation || !navigator.geolocation.getCurrentPosition(showPosition))
    // browser doesn't support geolocation or is not permitted to do.
    {
        // get location by ipinfo.io
        $.get("http://ipinfo.io", function (response) {
            $("#address").html("Du befindest dich in: " + response.city + ", " + response.region);
            $("#location").html(response.loc);
            $("#details").html(JSON.stringify(response, null, 4));
        }, "jsonp");
    }
}

function showPosition(position) {
    console.log(position);
}