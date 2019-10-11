<?php
/**
 * Template Name: ClusterMap
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
 ?>
 
 <?php get_header(); ?>

  <?php save_map_cluster_data(); ?> 

<div id='map'></div>

<script>
L.mapbox.accessToken = 'pk.eyJ1IjoiZWFtb25uZml0em1hdXJpY2UiLCJhIjoiY2ltaWFvZnR2MDA4ZHZha2dkbTZnamJsbyJ9.5cP3Ce37wdWpq5JVbdRK-w';
    //var map = L.mapbox.map('map', 'mapbox.streets')
      //  .setView([54.121346, -7.676251], 5);

    //var markers = new L.MarkerClusterGroup();
	
	
	
	
	var map = L.mapbox.map('map', 'mapbox.world-light', {
		maxZoom: 11,
		minZoom: 3
	});

	map.setView([54.121346, -7.676251], 3);

	
	var markers = new L.MarkerClusterGroup({
      // turn off the polygon option
      polygonOptions: {
        fillColor: '#3887be',
        color: '#3887be',
        weight: 2,
        opacity: 0.0,//make it invisible
        fillOpacity: 0.0
      },
	  spiderfyOnMaxZoom: true
    });


    for (var i = 0; i < addressPoints.length; i++) {
        var a = addressPoints[i];
        var title = a[2];
		var dod = a[4];
		var pic = a[5];
		var marker_color = ( (dod)? '000000' : 'd71338' ); //are they deceased
		var marker_symbol = ( (dod)? 'religious-christian' : '' ); //are they deceased
        var marker = L.marker(new L.LatLng(a[0], a[1]), {
            icon: L.mapbox.marker.icon({'marker-color': marker_color, 'marker-symbol': marker_symbol}),
			title: title
        });
        marker.bindPopup(title);
        markers.addLayer(marker);
    }

    map.addLayer(markers);
	
	
	
	/*L.mapbox.featureLayer({
		// this feature is in the GeoJSON format: see geojson.org
		// for the full specification
		type: 'Feature',
		geometry: {
			type: 'Point',
			// coordinates here are in longitude, latitude order because
			// x, y is the standard for GeoJSON and many formats
			coordinates: [
			   -7.557417,
			   54.097438 
			]
		},
		properties: {
			title: 'Slieve Russel Hotel',
			description: 'Ballyconnell, Co. Cavan, Ireland',
			// one can customize markers by adding simplestyle properties
			// https://www.mapbox.com/guides/an-open-platform/#simplestyle
			'marker-size': 'small',
			'marker-color': '#d71338',
			'marker-symbol': 'star'
		}
	}).addTo(map);*/

</script>