<?php use Cake\Core\Configure; ?>
<?php $longtitude = !empty($value['longtitude']) ? $value['longtitude'] : Configure::read('GoogleMap.DefaultCoordinate.Longtitude'); ?>
<?php $latitude = !empty($value['latitude']) ? $value['latitude'] : Configure::read('GoogleMap.DefaultCoordinate.Latitude'); ?>
<input class="form-control" type="text" id="suggest_<?php echo $field; ?>" name="suggest_<?php echo $field; ?>" />
<input type="hidden" id="longtitude_<?php echo $field; ?>" name="longtitude_<?php echo $field; ?>" value="<?php echo $longtitude; ?>" />
<input type="hidden" id="latitude_<?php echo $field; ?>" name="latitude_<?php echo $field; ?>" value="<?php echo $latitude; ?>" />
<style type="text/css">
    #map_<?php echo $field; ?> { height: 450px; }
</style>
<div id="map_<?php echo $field; ?>"></div>
<script type="text/javascript">
    var map_<?php echo $field; ?>;
    var autocomplete;
    var geocoder;
    var marker_<?php echo $field; ?> = null;
    function initMap() {
        geocoder = new google.maps.Geocoder();
        autocomplete = new google.maps.places.Autocomplete(document.getElementById('suggest_<?php echo $field; ?>'), {});
        autocomplete.addListener('place_changed', function () {
            var place = autocomplete.getPlace();
            updateMap_<?php echo $field; ?>(place.geometry.location);
        });
        setTimeout(function () {
            updateMap_<?php echo $field; ?>({
                lat: <?php echo $latitude; ?>,
                lng: <?php echo $longtitude; ?>,
            });
        }, 1000);
    }

    function updateMap_<?php echo $field; ?>(center) {
        map_<?php echo $field; ?> = new google.maps.Map(document.getElementById('map_<?php echo $field; ?>'), {
            center: center,
            zoom: 18,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        google.maps.event.addListener(map_<?php echo $field; ?>, "click", function (event) {
            updateMarker_<?php echo $field; ?>(map_<?php echo $field; ?>, {
                lat: event.latLng.lat(),
                lng: event.latLng.lng(),
            });
        });
        updateMarker_<?php echo $field; ?>(map_<?php echo $field; ?>, map_<?php echo $field; ?>.center);
    }

    function updateMarker_<?php echo $field; ?>(map, location) {
        if (marker_<?php echo $field; ?>) {
            marker_<?php echo $field; ?>.setMap(null);
        }
        marker_<?php echo $field; ?> = null;
        marker_<?php echo $field; ?> = new google.maps.Marker({
            map: map,
            draggable: true,
            position: location
        });
    }
    $("#suggest_<?php echo $field; ?>").parents('form').submit(function () {
        if (marker_<?php echo $field; ?>) {
            var coor = marker_<?php echo $field; ?>.getPosition();
            $('#longtitude_<?php echo $field; ?>').val(coor.lng());
            $('#latitude_<?php echo $field; ?>').val(coor.lat());
        } else {
            showAlert("<?php echo __('Please choose coordinate for Google Map'); ?>");
            return false;
        }
    });
</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=<?php echo Configure::read('GoogleMap.ApiKey'); ?>&callback=initMap&libraries=places">
</script>

