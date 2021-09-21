jQuery(document).ready(function($) { 
    // Asynchronously Load the map API 
    var script = document.createElement('script');
    script.src = "//maps.googleapis.com/maps/api/js?sensor=false&callback=initialize";
    document.body.appendChild(script);
});

function initialize() {
    var map;
    var bounds = new google.maps.LatLngBounds();
    var mapOptions = {
        mapTypeId: 'roadmap'
    };
                    
    // Display a map on the page
    map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
    map.setTilt(45);
        
    // Multiple Markers
    var markers = [
        ['Marine Corps Recruit Depot (MCRD)', 32.740681, -117.197846],
        ['Point Loma Lighthouse / Cabrillo National Monument', 32.672033, -117.240948],
        ['Silver Strand State Beach', 32.634739, -117.142188],
        ['NASSCO', 32.699815, -117.154506],
        ['Hotel Del Coronado', 32.680768, -117.177962],
        ['32nd St. Naval Station', 32.684689, -117.130025],
        ['Cruise Ship Terminal', 32.717702, -117.175245],
        ['North Island', 32.697872, -117.204366],
        ['Harbor Island', 32.725550, -117.200498],
        ['Shelter Island', 32.7120242,-117.2296588],
        ['Coronado Bay Bridge', 32.687425, -117.155738],
        ['Naval Training Center', 32.738873, -117.213388],
        ['USS Midway Museum', 32.713745, -117.175140],
        ['Coast Guard Station', 32.727191, -117.182637],
        ['10th Ave. Marine Terminal', 32.697418, -117.151430],
        ['San Diego Convention Center', 32.707164, -117.162488],
        ['Naval Amphibious Base (NAB)', 32.675860, -117.159874],
        ['Seaport Village / High Rise Towers', 32.709554, -117.170876],
        ['G Street Mole', 32.7102853,-117.1742282],
        ['Flip', 32.706261, -117.236358],
        ['Spawar', 32.707773, -117.237431],
        ['Submarine Base', 32.688990, -117.240035],
        ['Bait Barges', 32.694390, -117.235856]
    ];
                        
    // Info Window Content
    var infoWindowContent = [
        ['<div class="clustered-hovercard-content"><div class="entity-title">Marine Corps Recruit Depot (MCRD)</div></div>'],
        ['<div class="clustered-hovercard-content"><div class="entity-title">Point Loma Lighthouse /<br>Cabrillo National Monument</div></div>'],
        ['<div class="clustered-hovercard-content"><div class="entity-title">Silver Strand State Beach</div></div>'],
        ['<div class="clustered-hovercard-content"><div class="entity-title">NASSCO</div></div>'],
        ['<div class="clustered-hovercard-content"><div class="entity-title">Hotel Del Coronado</div></div>'],
        ['<div class="clustered-hovercard-content"><div class="entity-title">32nd St. Naval Station</div></div>'],
        ['<div class="clustered-hovercard-content"><div class="entity-title">Cruise Ship Terminal</div></div>'],
        ['<div class="clustered-hovercard-content"><div class="entity-title">North Island</div></div>'],
        ['<div class="clustered-hovercard-content"><div class="entity-title">Harbor Island</div></div>'],
        ['<div class="clustered-hovercard-content"><div class="entity-title">Shelter Island</div></div>'],
        ['<div class="clustered-hovercard-content"><div class="entity-title">Coronado Bay Bridge</div></div>'],
        ['<div class="clustered-hovercard-content"><div class="entity-title">Naval Training Center</div></div>'],
        ['<div class="clustered-hovercard-content"><div class="entity-title">USS Midway Museum</div></div>'],
        ['<div class="clustered-hovercard-content"><div class="entity-title">Coast Guard Station</div></div>'],
        ['<div class="clustered-hovercard-content"><div class="entity-title">10th Ave. Marine Terminal</div></div>'],
        ['<div class="clustered-hovercard-content"><div class="entity-title">San Diego Convention Center</div></div>'],
        ['<div class="clustered-hovercard-content"><div class="entity-title">Naval Amphibious Base (NAB)</div></div>'],
        ['<div class="clustered-hovercard-content"><div class="entity-title">Seaport Village /<br> High Rise Towers</div></div>'],
        ['<div class="clustered-hovercard-content"><div class="entity-title">G Street Mole</div></div>'],
        ['<div class="clustered-hovercard-content"><div class="entity-title">Flip</div></div>'],
        ['<div class="clustered-hovercard-content"><div class="entity-title">Spawar</div></div>'],
        ['<div class="clustered-hovercard-content"><div class="entity-title">Submarine Base</div></div>'],
        ['<div class="clustered-hovercard-content"><div class="entity-title">Bait Barges</div></div>']

    ];
        
    // Display multiple markers on a map
    var infoWindow = new google.maps.InfoWindow(), marker, i;
    
    // Loop through our array of markers & place each one on the map  
    for( i = 0; i < markers.length; i++ ) {
        var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
        bounds.extend(position);
        marker = new google.maps.Marker({
            position: position,
            map: map,
            title: markers[i][0]
        });
        
        // Allow each marker to have an info window    
        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
                infoWindow.setContent(infoWindowContent[i][0]);
                infoWindow.open(map, marker);
            }
        })(marker, i));

        // Automatically center the map fitting all markers on the screen
        map.fitBounds(bounds);
    }

    // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
    var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
        this.setZoom(11);
        google.maps.event.removeListener(boundsListener);
    });
    
}