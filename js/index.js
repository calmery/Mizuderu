var data_source = $('#map').attr('data-source');
var position = JSON.parse(data_source);

var map,
// index 3 (marker 3) not exist
    markers = ['no', 'ok', 'go', 'go']

var m = document.getElementById('map')

map = new google.maps.Map(m, {
    center: new google.maps.LatLng(32.7858659, 130.7633434),
    zoom: 9,
    mapTypeId: google.maps.MapTypeId.ROADMAP
})

m.style.width = window.innerWidth + 'px'
m.style.height = window.innerHeight - (document.getElementById('tools').clientHeight) - 65 + 'px'

// Set Data
//var position = <?php echo $json; ?>

//        console.log(position) // => [{Data},{Data}...,{}]

//    var kmlUrl = "http://www.google.com/maps/d/u/0/kml?mid=16leszIlJkRUaq0o6cV9YY-cu3HM";
//    var kmlLayer = new google.maps.KmlLayer(kmlUrl);
//    kmlLayer.setMap(map);

var data
for (var i = 0; i < position.length - 1; i++) {
    data = position[i]['locate'].split(/,/)
    post_time = position[i]['time'];
//        console.log(position[i].flg)
    var myMarker = new google.maps.Marker({
        position: new google.maps.LatLng(data[0], data[1]),
        map: map,
        icon: markers[position[i].flg] + '.png',
    })
    attachMessage(myMarker, post_time, markers[position[i].flg]);
}

document.getElementById('small').addEventListener('click', function () {
    if (map.zoom > 0) map.setZoom(--map.zoom)
})

document.getElementById('big').addEventListener('click', function () {
    map.setZoom(++map.zoom)
})

var infoWindows = [];

function attachMessage(marker, post_time, flg) {
    google.maps.event.addListener(marker, 'click', function (event) {

        var t = new Date(post_time * 1000);

        var year = t.getFullYear();
        var month = t.getMonth() + 1;
        var hours = t.getHours();
        var date = t.getDate();
        // Minutes part from the timestamp
        var minutes = "0" + t.getMinutes();
        // Seconds part from the timestamp
        var seconds = "0" + t.getSeconds();

        // Will display time in 10:30:23 format
        var formattedTime = year + "年" + month + "月" + date + "日" + hours + '時' + minutes.substr(-2) + '分' + seconds.substr(-2) + "秒";

        var flg_str = "";
        if (flg == "no") {
            flg_str = '<img src="no.png" > 水が出ない';
        } else if (flg == "ok") {
            flg_str = '<img src="ok.png" > 水は出る';
        } else if (flg == "go") {
            flg_str = '<img src="go.png" > 水の提供可能';
        }
        new google.maps.Geocoder().geocode({
            latLng: marker.getPosition()
        }, function (result, status) {
            if (status == google.maps.GeocoderStatus.OK) {

                closeAllInfoWindows();

                var ifw = new google.maps.InfoWindow({
                    content: formattedTime + "<br>" + flg_str + "<br>" + result[0].formatted_address
                });

                ifw.open(marker.getMap(), marker);

                infoWindows.push(ifw);
            }
        });
    });
}

function closeAllInfoWindows() {
    for (var i = 0; i < infoWindows.length; i++) {
        infoWindows[i].close();
    }
}