<?php

    if (!empty($_GET['latitude']) && !empty($_GET['longitude']) &&
        !empty($_GET['time']) && !empty($_GET['satellites']) &&
        !empty($_GET['speedOTG']) && !empty($_GET['course'])) {

        function getParameter($par, $default = null){
            if (isset($_GET[$par]) && strlen($_GET[$par])) return $_GET[$par];
            elseif (isset($_POST[$par]) && strlen($_POST[$par])) 
                return $_POST[$par];
            else return $default;
        }

        $file = 'file.txt';
        $lat = getParameter("latitude");
        $lon = getParameter("longitude");
        $time = getParameter("time");
        $sat = getParameter("satellites");
        $speed = getParameter("speedOTG");
        $course = getParameter("course");
        $person = $lat.",".$lon.",".$time.",".$sat.",".$speed.",".$course."\n";
        
        echo "
            DATA:\n
            Latitude: ".$lat."\n
            Longitude: ".$lon."\n
            Time: ".$time."\n
            Satellites: ".$sat."\n
            Speed OTG: ".$speed."\n
            Course: ".$course;

        if (!file_put_contents($file, $person, FILE_APPEND | LOCK_EX))
            echo "\n\t Error saving Data\n";
        else echo "\n\t Data Save\n";
    }
    else {

?>

<!DOCTYPE html>
<html>
    
<head>

    <!-- Load Jquery -->

    <script language="JavaScript" type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.10.1/jquery.min.js" ></script>

    <!-- Load Google Maps Api -->

    <!-- IMPORTANT: change the API v3 key -->

    <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBpoCsCMwRop9Rc-Dj40ag74Blhn0q2P8A&sensor=false"></script>

    <!-- Initialize Map and markers -->

    <script type="text/javascript">
        var myCenter=new google.maps.LatLng(41.669578,-0.907495);
        var marker;
        var map;
        var mapProp;

        function initialize()
        {
            mapProp = {
              center:myCenter,
              zoom:15,
              mapTypeId:google.maps.MapTypeId.ROADMAP
              };
            mark();
            setInterval('mark()',120000);
        }
        var words;
        function mark()
        {

            // posisiones
            // 0: time
            // 1: latitude
            // 2: N or S
            // 3: longitude
            // 4: E o W

            map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
            var file = "file.txt";
            $.get(file, function(txt) { 
                var lines = txt.split("\n");
                console.log(lines.length);
                for (var i=0;i<lines.length;i++){
                    console.log(lines);
                    words=lines[i].split(",");
                    if ((words[1]!="")&&(words[2]!="")){

                        var gradoLatitude = words[1].substr(0, 2);
                        var gradoLongitude = words[3].substr(0, 3);
                        console.log(gradoLatitude, gradoLongitude);

                        var latitude = words[1].substr(2);
                        var longitude = words[3].substr(3);
                        
                        var resulLat = parseFloat(latitude/60) + parseInt(gradoLatitude);
                        var resulLon = parseFloat(longitude/60) + parseInt(gradoLongitude);

                        if(words[2] == "S"){
                            // latitude negativa
                            resulLat = '-'+resulLat;
                            resulLat = parseFloat(resulLat);
                        }
                        if(words[4] == "W"){
                            // longitude negativa
                            resulLon = '-'+resulLon;
                            resulLon = parseFloat(resulLon);
                        }
                        console.log(resulLat, resulLon);

                        marker=new google.maps.Marker({
                              position:new google.maps.LatLng(resulLat,resulLon),
                              });
                        marker.setMap(map);
                        map.setCenter(new google.maps.LatLng(resulLat,resulLon));
                        document.getElementById('time').innerHTML=words[0];
                        document.getElementById('lat').innerHTML=resulLat;
                        document.getElementById('lon').innerHTML=resulLon;
                    }
                }
                marker.setAnimation(google.maps.Animation.BOUNCE);
            });

        }

        google.maps.event.addDomListener(window, 'load', initialize);
    </script>


    <style>
        body{
            padding: 0;
            margin: 0;
            font-size: 16px;
            font-family: Arial, Helvetica, sans-serif;
            background: #ecf0f1;
        }
        #superior{
            border: none;
            padding: 10px;
            background-color: #fff;
        }
        table{
            border: none;
        }
        hr{
            color: #666;
        }
    </style>
</head>
<!-- '. date("Y M d - H:m") .' -->
<body>
    <?php
        echo '    

        <!-- Draw information table and Google Maps div -->

        <div>
            <center><br />
                <b> SIM908 GPS position DEMO </b><hr><br /><br />
                <div id="superior" style="width:800px;">
                    <table style="width:100%">
                        <tr>
                            <td>Time</td>
                            <td>Latitude</td>
                            <td>Longitude</td>
                        </tr>
                        <tr>
                            <td id="time"></td>
                            <td id="lat"></td>
                            <td id="lon"></td>
                        </tr>
                </table>
                </div>
                <br /><br />
                <div id="googleMap" style="width:100%;height:700px;"></div>
            </center>
        </div>';
    ?>
</body>
</html>

<?php } ?>
  
