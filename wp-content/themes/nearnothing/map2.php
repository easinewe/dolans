<?php
/**
 * Template Name: Map2
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
 ?>
 
 <?php get_header(); ?>




		
  <?php save_map_data(); ?>

<div id="map_container">
<div id='map'></div>
</div>

<script>
//this may be necessary to get around cross domain restrictions
document.domain = 'dolansofcavan.com';



mapboxgl.accessToken = 'pk.eyJ1IjoiZWFtb25uZml0em1hdXJpY2UiLCJhIjoiY2ltaWFvZnR2MDA4ZHZha2dkbTZnamJsbyJ9.5cP3Ce37wdWpq5JVbdRK-w';
var map = new mapboxgl.Map({
    container: 'map', // container id
    style: 'mapbox://styles/eamonnfitzmaurice/cinbszrto0000acniqqaxoi81', //stylesheet location
    center: [-7.557089, 54.096714], // starting position
    zoom: 5 // starting zoom
});


map.on('load', function(){
	// Add a new source from our GeoJSON data and set the
    // 'cluster' option to true.
    map.addSource("famlocations", {
        type: "geojson",
        // Point to GeoJSON data. This example visualizes all M1.0+ earthquakes
        // from 12/22/15 to 1/21/16 as logged by USGS' Earthquake hazards program.
        data: "../wp-content/themes/nearnothing/mapfamdata.geojson",
        //data: "http://www.eamonnfitzmaurice.com/earthquakes.geojson",
		cluster: false,
        clusterMaxZoom: 14, // Max zoom to cluster points on
        clusterRadius: 50 // Radius of each cluster when clustering points (defaults to 50)
    });


    // Use the earthquakes source to create five layers:
    // One for non-clustered markers, three for each cluster category,
    // and one for cluster labels.
    map.addLayer({
        "id": "non-cluster-markers",
        "type": "symbol",
        "source": "famlocations",
        "layout": {
            "icon-image": "marker-15"
        }
    });
	
});


map.on('click', function (e) {
    // Use queryRenderedFeatures to get features at a click event's point
    // Use layer option to avoid getting results from other layers
    var features = map.queryRenderedFeatures(e.point, { layers: ['non-cluster-markers'] });
    // if there are features within the given radius of the click event,
    // fly to the location of the click event
    if (features.length) {
        // Get coordinates from the symbol and center the map on those coordinates
        map.flyTo({center: features[0].geometry.coordinates});
    }
	
	var feature = features[0];

    // Populate the popup and set its coordinates
    // based on the feature found.
    var popup = new mapboxgl.Popup()
        .setLngLat(feature.geometry.coordinates)
        .setHTML(feature.properties.description)
        .addTo(map);
	
});




// Use the same approach as above to indicate that the symbols are clickable
// by changing the cursor style to 'pointer'.
map.on('mousemove', function (e) {
    var features = map.queryRenderedFeatures(e.point, { layers: ['non-cluster-markers'] });
    map.getCanvas().style.cursor = features.length ? 'pointer' : '';
});

</script>