/**
 * @file
 * Attaches behaviors for the custom Google Maps.
 */

(function ($, Drupal) {
    
    const mapElement = 'my-map';

    /**
     * Initializes the map.
     */
    function init (geofield, title) {
        //console.log(geofield);
        var point = {lat: geofield.lat, lng: geofield.lon};

        var map = new google.maps.Map(document.getElementById(mapElement), {
            center: point,
            scrollwheel: false,
            zoom: 12
        });

        var infowindow = new google.maps.InfoWindow({
            content: title
        });

        var marker = new google.maps.Marker({
            position: point,
            map: map,
            title: title
        });
        marker.addListener('click', function() {
            infowindow.open(map, marker);
        });
    }

    Drupal.behaviors.customMapBehavior = {
        attach: function (context, settings) {
            $(context).find('#'+mapElement).once('customMapBehavior').each(function () {
                init(settings.geofield, settings.title);
            });
        }
    };

})(jQuery, Drupal);
