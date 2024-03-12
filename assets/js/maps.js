

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

                    const glyphImg = document.createElement("img");
                    glyphImg.className = "current-location";

                    glyphImg.src =
                        "assets/icons/person-walking-solid.svg";

                    const glyphSvgPinElement = new PinElement({
                        glyph: glyphImg,
                        scale: 1.5,
                        background: "#2862de",
                        borderColor: "#2862de",
                    });


                    if(isFirst) {
                        let content = buildMarkerContent("person_walking",null,pos,map)
                        marker = new AdvancedMarkerElement ({
                            position: pos,
                            map,
                            content: glyphSvgPinElement.element,
                            title: "Current Location"
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
    return map;
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

// Adds all the pins on the page to the map, based on its data
async function addPagePinsToMap(map) {
    const { Map } = await google.maps.importLibrary("maps");
    const { AdvancedMarkerElement, PinElement } = await google.maps.importLibrary("marker");

    $.ajax({
        url: "php/pins/getAllUserPins.php",
        type: "GET",
        data: {},
        success: function(data){

        }
    }).done(function(data) {
        for(let i = 0; i < (JSON.parse(data).data.length); i++) {
            let pin_data = JSON.parse(data).data[i];
            const pinBackground = new PinElement({
                background: "#FBBC04",
                borderColor: "#FBBC04"
            })

            const pos = {
                lat: pin_data.pos_x,
                lng: pin_data.pos_y
            }

            let marker = new AdvancedMarkerElement ({
                position: pos,
                map,
                content: pinBackground.element,
            });

        }

    })

}



let mp = await initMap();

await addPagePinsToMap(mp);
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

async function buildMarkerContent(icon_string, pin_data, pos, map) {
    const { Map } = await google.maps.importLibrary("maps");
    const { AdvancedMarkerElement, PinElement } = await google.maps.importLibrary("marker");

    let content;

    content = document.createElement("div");

    content.classList.add("map-marker");
    content.innerHTML = `
        <div class="icon">
        <i aria-hidden="true" class="fa fa-icon fa-signs-post" title="Test"></i>
        <span class="fa-sr-only">Test</span>
    </div>
    <div class="details">
        <div class="price">1</div>
        <div class="address">1</div>
        <div class="features">
        <div>
            <i aria-hidden="true" class="fa fa-bed fa-lg bed" title="bedroom"></i>
            <span class="fa-sr-only">bedroom</span>
            <span>1</span>
        </div>
        <div>
            <i aria-hidden="true" class="fa fa-bath fa-lg bath" title="bathroom"></i>
            <span class="fa-sr-only">bathroom</span>
            <span>1</span>
        </div>
        <div>
            <i aria-hidden="true" class="fa fa-ruler fa-lg size" title="size"></i>
            <span class="fa-sr-only">size</span>
            <span>1 ft<sup>2</sup></span>
        </div>
        </div>
    </div>
        
        `;

    return content;
    // let marker = new AdvancedMarkerElement ({
    //     position: pos,
    //     map,
    //     content: content,
    // });

    // return marker;
}