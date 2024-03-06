// Load the Google map
let map;

async function initMap() {
    const { Map } = await google.maps.importLibrary("maps");

    let map;
    map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: -34.397, lng: 150.644 },
        zoom: 18,
        mapTypeId: 'satellite',
        disableDefaultUI: true,
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

        let pos;
        navigator.geolocation.getCurrentPosition(
        (position) => {
            pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude,
            }}
        );

        let marker = new google.maps.Marker({
            position: pos,
            map,
        });

        // while (true) {
        //     navigator.geolocation.getCurrentPosition(
        //         (position) => {
        //             pos = {
        //                 lat: position.coords.latitude,
        //                 lng: position.coords.longitude,
        //             };
        //             marker.position = pos
        //         })
        // }
    }

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

// Phone support
addEventListener("resize", (event) => {
    if(window.innerWidth < 490) {
        document.getElementById("pins").style.display = "none";
        document.getElementById("work-area").style.display = "none";
    } else {
        document.getElementById("pins").style.display = "block";
        document.getElementById("work-area").style.display = "block";
    }

})