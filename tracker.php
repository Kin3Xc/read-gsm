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

    <script src="http://maps.googleapis.com/maps/api/js?key=your_key&sensor=false"></script>

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
            // setInterval('mark()',10000);
            mark();
        }

        function mark()
        {
            map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
            var file = "file.txt";
            $.get(file, function(txt) { 
                var lines = txt.split("\n");
                for (var i=0;i<lines.length;i++){
                    console.log(lines[i]);
                    var words=lines[i].split(",");
                    console.log(words);
                    if ((words[0]!="")&&(words[1]!=""))
                    {
                        marker=new google.maps.Marker({
                              position:new google.maps.LatLng(words[0],words[1]),
                              });
                        marker.setMap(map);
                        map.setCenter(new google.maps.LatLng(words[0],words[1]));
                        document.getElementById('time').innerHTML=words[0] || "";
                        document.getElementById('lat').innerHTML=words[1] || "";
                        document.getElementById('long').innerHTML=words[2] || "";
                    }
                }
                marker.setAnimation(google.maps.Animation.BOUNCE);
            });

        }

        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
</head>
<!-- '. date("Y M d - H:m") .' -->
<body>
    <?php
        echo '    

        <!-- Draw information table and Google Maps div -->

        <div>
            <center><br />
                <b> SIM908 GPS position DEMO </b><br /><br />
                <div id="superior" style="width:800px;border:1px solid">
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
                <div id="googleMap" style="width:800px;height:700px;"></div>
            </center>
        </div>';
    ?>
</body>
</html>

<?php } ?>
  
