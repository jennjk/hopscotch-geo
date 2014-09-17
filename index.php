<?php require "ajax_scripts.php";
$pois_array = getAllPOIS();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="images/favicon.png">



  <!-- Bootstrap core CSS -->
  <link href="css/bootstrap.css" rel="stylesheet">
  <link href="css/main.css" rel="stylesheet">
  
</head>

<body>
 <style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }

    </style>

  <div class="container">

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <h1>Geo mapge!</h1>
      <input type="text" id="marker_text" value="" placeholder="Insert your status here"/>
      <button onclick="SaveMyMarker();">Save my marker</button>
    </div>
       

    <!-- here is the map embed -->   
    <div class="col-md-8 col-xs-12" id="mapholder">

    </div>



  </div> <!-- /container -->

  <footer role="contentinfo" class="navbar-inverse ">
    <div class="container">

    </div>
  </footer>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true"></script>
    <script type="text/javascript">
var map;
var marker;
var current_lat, current_lng;


function initialize() {
  var mapOptions = {
    zoom: 16,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);

  // Try HTML5 geolocation
  if(navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position){
      // This are the coordinates taken from the browser
      var pos = new google.maps.LatLng(position.coords.latitude,
                                       position.coords.longitude);

      current_lat = position.coords.latitude;
      current_lng = position.coords.longitude;
      
      var infowindow = new google.maps.InfoWindow({
        map: map,
        position: pos,
        content: 'HERE IS THE TEXT YOU PUT IN THE POP-UP'
      });

      map.setCenter(pos);
    }, function() {
      handleNoGeolocation(true);
    });
  } else {
    // Browser doesn't support Geolocation
    handleNoGeolocation(false);
  }


  

  //Dog Marker
  var dogMarkerImage = {
    url:'images/marker_dog.png',
    size: new google.maps.Size(48, 35)
  };



// POINTS FROM MY DB
<?php foreach($pois_array as $poi):?>
var IMM = new google.maps.LatLng(<?php echo $poi['lat'];?>, <?php echo $poi['lng'];?>);
 marker = new google.maps.Marker({
    map:map,
    draggable:false,
    animation: google.maps.Animation.DROP,
    position: IMM,
    icon:  dogMarkerImage
  });

  google.maps.event.addListener(marker, 'click', toggleBounce);


<?php endforeach;?>

}



// In case geolocation is not working this will let you know
function handleNoGeolocation(errorFlag) {
  if (errorFlag) {
    var content = 'Error: The Geolocation service failed.';
  } else {
    var content = 'Error: Your browser doesn\'t support geolocation.';
  }

  var options = {
    map: map,
    position: new google.maps.LatLng(-34.906456, -56.186262), //Por default la IMM
    content: content
  };

  var infowindow = new google.maps.InfoWindow(options);
  map.setCenter(options.position);

}


// Bouncing effect for marker
function toggleBounce() {

  if (marker.getAnimation() != null) {
    marker.setAnimation(null);
  } else {
    marker.setAnimation(google.maps.Animation.BOUNCE);
  }
}



// IMPORTANT! Ajax function to save points inside the database
function SaveMyMarker(){
  jQuery.ajax({
        type:'POST',
        url: 'http://localhost/geomap/ajax_scripts.php',
        dataType: 'json',
        
        data: {
            "action"       : "saveMarker",
            "lat"         : current_lat,
            "lng"        : current_lng,
            "marker_text" : jQuery('#marker_text').val()
        },
        success: function(data) {
            alert('This marker should be saved');
        }
    });





}



//load map
google.maps.event.addDomListener(window, 'load', initialize);
    </script>

    <div id="content-pane">
      <div id="inputs">
       
        
      </div>
      <div id="outputDiv"></div>
    </div>
  <div id="map-canvas"></div>
  </body>
  </html>
