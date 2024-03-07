
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

$(document).ready( function() {
    populatePins();
});

function populatePins() {
    console.log("getting");

    let pinData;
    $.ajax({
        url: "php/pins/get-all-pins.php",
        type: "GET",
        data: {}
    }).done(function(data) {
          pinData = data;
          console.log(data);
    })

    console.log(pinData);

}