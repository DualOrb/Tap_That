

// Load the Google map
let map;

async function initMap() {
    const { Map } = await google.maps.importLibrary("maps");
    const { AdvancedMarkerElement, PinElement } = await google.maps.importLibrary("marker");

    let map;
    map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: -34.397, lng: 150.644 },
        zoom: 18,
        mapTypeId: 'satellite',
        disableDefaultUI: true,
        mapId: "7a10c56b-38dd-4860-9d81-be9f02bf98c7"
    });

    let infoWindow = new google.maps.InfoWindow();
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                };

                map.setCenter(pos);
            },
            () => {
                handleLocationError(true, infoWindow, map);
            },
        );
    } else {
        // Browser doesn't support Geolocation
        handleLocationError(false, infoWindow, map);
    }

    // Drop marker at current location
    async function updateCurrentLocation(map){
        if(!navigator.geolocation) { return }
        let marker;
        let isFirst = true

        // Continuously updates where the "Current Location" marker is
        while (true) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    };
                    const pinBackground = new PinElement({
                        background: "#FBBC04"
                    })
                    if(isFirst) {
                        marker = new AdvancedMarkerElement ({
                            position: pos,
                            map,
                            content: pinBackground.element,
                        });
                        isFirst = false
                    } else {
                        marker.position = pos
                    }

                })
            await new Promise(r => setTimeout(r, 5000));
        }
    }

    // Do not await, since we want a "lazy loop"
    updateCurrentLocation(map)

}

function handleLocationError(browserHasGeolocation, infoWindow, map) {
    infoWindow.setPosition(map.getCenter());
    infoWindow.setContent(
        browserHasGeolocation
            ? "Error: The Geolocation service failed."
            : "Error: Your browser doesn't support geolocation.",
    );
    infoWindow.open(map);
}

initMap();


// Controls related to creating new map elements
$(document).ready( function() {
    document.getElementById("create-new-pin-button").addEventListener("click", createNewPin, false);
});
function createNewPin(){

    let data = {
        "pin_type": $("#select-pin-type").val(),
        "pin_name": $("#pin-name").val(),
        "pin_lat": $("#pin-lat").val(),
        "pin_lon": $("#pin-lon").val(),
        "pin_altitude": $("#pin-alt").val(),
        "color": $("#pin-color").val(),
        "info": {
            "pin_desc": $("#pin-desc").val(),
            "tree_health": $("#select-tree-health").val(),
            "num_taps": $("#num_taps").val()
        }
    }

    $.ajax({
        url: "php/pins/add-pin.php",
        type: "POST",
        data: data,
        success: function(data){
            alert(data);
        }
    });

}