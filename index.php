<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: auth/login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/styles/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/styles/navbar.css">
    <link rel="stylesheet" href="assets/styles/index.css">
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script type="module" src="assets/js/index.js" defer></script>
    <script type="module" src="assets/js/maps.js" defer></script>

    <title>User Dashboard</title>
</head>
<body style="padding: 0px;">
<?php
require_once("partials/navbar.php")
?>
<div id="main">
    <div id="pins">
<!--        Populate pins in collapseable cards here-->
        <div id="accordion">
            <?php
                require_once("php/pins/get-all-pins.php");
            ?>
        </div>

    </div>
    <div id="mapbox">
        <div id="map"></div>
        <script>(g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})
            ({key: "", v: "weekly"});</script>
    </div>

    <div id="work-area">
        <div class="form-heading">
            <h1>Create map element</h1>
            <label for="select-element-type">Select Element Type</label>
            <select class="form-control" id="select-element-type">
                <option value="pin">Pin</option>
                <option value="line">Line</option>
            </select>
        </div>
        <!--Create Pin Form-->
        <form id="pin-create-form">
            <hr>

            <div id="pin-data-box">
                <div class="form-group form-row">
                    <input class="form-control" type="text" name="pin_name" id="pin-name" placeholder="Name (optional)">
                </div>
                <div class="form-group form-row">
                    <label for="select-element-type">Pin Type</label>
                    <select class="form-control" id="select-pin-type">
                        <option value="shack">Sugar Shack</option>
                        <option value="post">Post</option>
                        <option value="collection">Collection</option>
                        <option selected value="tap">Tap</option>
                    </select>
                </div>
                <div class="form-group form-row">
                    <label for="pin-color">Pin Color</label>
                    <input class="form-control" type="color" value="#FFC300" name="pin_colour" id="pin-color">
                </div>
                <div class="form-group form-row">
                    <input id="pin-lat" class="form-control" type="text" name="pox_x" placeholder="Latitude">
                    <input id="pin-lon" class="form-control" type="text" name="pos_y" placeholder="Longitude">
                    <input id="pin-alt" class="form-control" type="text" name="pos_altitude" placeholder="Altitude(m)">
                </div>
                <div class="form-group form-row">
                    <label for="select-element-type">Tree Health</label>
                    <select class="form-control" id="select-tree-health">
                        <option value="healthy">Healthy</option>
                        <option value="medium">Average</option>
                        <option value="poor">Poor</option>
                    </select>
                </div>
                <div class="form-group form-row">
                    <label for="num_taps">Number of Taps</label>
                    <input class="form-control" type="range" name="num_taps" id="num_taps" min="0" max="5" value="1" step="1" placeholder="Latitude" oninput="this.nextElementSibling.value = this.value">
                    <output>1</output>
                </div>

                <hr>
                <div class="form-group form-row">
                    <textarea id="pin-desc" class="form-control" name="desc" placeholder="Description of the pin" cols="50" rows="3" res></textarea>
                </div>

            </div>


        </form>

        <button id="create-new-pin-button" class="btn btn-primary">Create</button>


    </div>
</div>
</body>
</html>