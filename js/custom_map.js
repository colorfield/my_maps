/**
 * @file
 * Attaches behaviors for the custom Google Maps.
 */

(function ($, Drupal) {

    /**
     * Initializes the map.
     */
    function initMap (geofield) {
        //console.log(geofield);
        var map = new google.maps.Map(document.getElementById('my-map'), {
            center: {lat: geofield.lat, lng: geofield.lon},
            scrollwheel: false,
            zoom: 12
        });
    }

    Drupal.behaviors.customMapBehavior = {
        attach: function (context, settings) {
            initMap(settings.geofield);
        }
    };

})(jQuery, Drupal);
