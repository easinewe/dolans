<?php
/**
 * Template Name: Map
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
 ?>
 
 <?php get_header(); ?>
<style type="text/css">
#menu-item-141{
   font-size: 44px !important;
}
</style>
menu-item-141
<div id="map_container">
<div id='map'></div>
</div>
mapboxgl.accessToken = 'pk.eyJ1IjoiZWFtb25uZml0em1hdXJpY2UiLCJhIjoiY2ltaWFvZnR2MDA4ZHZha2dkbTZnamJsbyJ9.5cP3Ce37wdWpq5JVbdRK-w';
var map = new mapboxgl.Map({
    container: 'map', // container id
    style: 'mapbox://styles/eamonnfitzmaurice/cinbszrto0000acniqqaxoi81', //stylesheet location
    center: [-7.557089, 54.096714], // starting position
    zoom: 14 // starting zoom
});


map.on('load', function () {
    map.addSource("markers", {
        "type": "geojson",
        "data": {
            "type": "FeatureCollection",
            "features": [{
                "type": "Feature",
                "geometry": {
                    "type": "Point",
                    "coordinates": [-7.557089, 54.096714]
                },
                "properties": {
                    "description": "Dolan Family Reunion<br/>Sunday, August 21, 2016<br/>Slieve Russell Hotel in Ballyconnell",
                    "marker-symbol": "monument"
                }
            }, 
			<?php
    $args = array(
      'post_type' => 'relative',
	  'numberposts' => -1
    );
    $relatives = new WP_Query( $args );
    
  // If we are in a loop we can get the post ID easily

	if( $relatives->have_posts() ) {
      while( $relatives->have_posts() ) {
        $relatives->the_post();
        ?>
          
              <? $first_name = get_post_meta( get_the_ID(), 'relative_name_first', true ); ?>
              <? $last_name = get_post_meta( get_the_ID(), 'relative_name_last', true ); ?>
              <? $location = get_post_meta( get_the_ID(), 'relative_location_city', true ); ?>
              <? $latitude = get_post_meta( get_the_ID(), 'relative_location_lat', true ); ?>
              <? $longitude = get_post_meta( get_the_ID(), 'relative_location_long', true ); ?>

            {
              "type": "Feature",
              "geometry": {
                "type": "Point",
                "coordinates": [<?php is_numeric($longitude)? $longitude: 40.75; ?>, <?php is_numeric($latitude)? $latitude: -73.94; ?>]
              },
              "properties": {
                "description": "<h1><?php echo $first_name; ?> <?php echo $last_name; ?></h1><br/><?php echo $location; ?> ",
                "marker-symbol": "camera"
              }
            },
        <?php
      }
    }
  ?>
			{
                "type": "Feature",
                "geometry": {
                    "type": "Point",
                    "coordinates": [-122.414, 37.776]
                },
                "properties": {
                    "title": "Mapbox SF",
                    "marker-symbol": "camera"
                }
            }]
        },
		"cluster": true,
        "clusterMaxZoom": 14, // Max zoom to cluster points on
        "clusterRadius": 50 // Radius of each cluster when clustering points (defaults to 50)

				
    });

    

map.addLayer({
                "id": "markers",
                "type": "circle",
                "source": "markers",
                "paint": {
                    "circle-color": "red",
                    "circle-opacity": 0.75,
                    "circle-radius": 10 // Nice radius value
                }
            });
});


map.on('click', function (e) {
    // Use queryRenderedFeatures to get features at a click event's point
    // Use layer option to avoid getting results from other layers
    var features = map.queryRenderedFeatures(e.point, { layers: ['markers'] });
	// if there are features within the given radius of the click event,
    // fly to the location of the click event
    if (features.length) {
		
		//reduce size of map and show background
		//reduceMap();

		// Get coordinates from the symbol and center the map on those coordinates
		map.flyTo({center: features[0].geometry.coordinates});

		
		
    }
});



</script>


<script>
// When a click event occurs near a marker icon, open a popup at the location of
// the feature, with description HTML from its properties.
map.on('click', function (e) {
    var features = map.queryRenderedFeatures(e.point, { layers: ['markers'] });

    if (!features.length) {
        return;
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
    var features = map.queryRenderedFeatures(e.point, { layers: ['markers'] });
    map.getCanvas().style.cursor = (features.length) ? 'pointer' : '';
});
</script>

<script>
/*document.getElementById("map_container").onclick=function(){
	var map_element = document.getElementsByClassName("mapboxgl-canvas-container");
	var main_map = document.getElementById('map');
	var map_container = document.getElementById('map_container');
	if( map_container.classList.contains('reduced') ){
		map_container.classList.remove("reduced");
		main_map.classList.remove("reduced");
	}else{
	  map_container.className += ' reduced';
	  main_map.className += ' reduced';	
	}
};*/
</script>

<script>
function reduceMap(){
	
	//var w = window.innerWidth * 0.5;
	//var h = window.innerHeight * 0.5;
	var map_container = document.getElementById('map_container');
	
	if( map_container.classList.contains('clipped') ){
		map_container.classList.remove("clipped");
		//main_map.classList.remove("clipped");
	}else{
	  //map_container.style.clip = "circle(200px at 720px 440px)";
	  map_container.className += ' clipped';
	  //main_map.className += ' reduced';	
	}
}
</script>