
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



function onPinChange() {
    let selected = document.getElementById("select-pin-type").value;
    let color_selector = document.getElementById("pin-color")

    // Unhide hidden elements
    togglePinTypeElements(true);

    // On true, shows all elements required for a tap type input.
    // False, hides non-required elements
    function togglePinTypeElements(toggle) {
        if(toggle) {
            $("#select-tree-health").show();
            $("#tree-health-label").show();
            document.getElementById("select-tree-health").value = "healthy"

            document.getElementById("num-taps").value = 1
            $("#num-taps").show();
            $("#num-taps-label").show();
            $("#num-taps-range-output").show();
        } else {
            // Tree Health
            document.getElementById("select-tree-health").value = ""
            $("#select-tree-health").hide();
            $("#tree-health-label").hide();
            $("#num-taps-range-output").hide();

            // Num Taps
            document.getElementById("num-taps").value = null
            $("#num-taps").hide();
            $("#num-taps-label").hide();
        }
    }

    // Change the color selected
    switch(selected) {
        case "shack":
            color_selector.value = "#298022";
            togglePinTypeElements(false)
            break;
        case "post":
            color_selector.value = "#631a1a";
            togglePinTypeElements(false)
            break;
        case "tap":
            color_selector.value = "#FFC300";
            break;
        case "collection":
            color_selector.value = "#308fc2";
            togglePinTypeElements(false)
            break;
        case undefined:
            color_selector.value = "#ff0008";
            togglePinTypeElements(false)
            break;
    }
}

function updatePositionCoordinates() {
    if(!navigator.geolocation) { return }
    navigator.geolocation.getCurrentPosition(
        (position) => {
            document.getElementById("pin-lat").value = position.coords.latitude
            document.getElementById("pin-lon").value = position.coords.longitude
            document.getElementById("pin-alt").value = position.coords.altitude
        }
    )
}

$(document).ready(function (event) {
    document.getElementById("select-pin-type").addEventListener("change", onPinChange, false)
    document.getElementById("locate-button").addEventListener("click", updatePositionCoordinates, false)

})