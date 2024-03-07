
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