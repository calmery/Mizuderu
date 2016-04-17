document.getElementById('small').addEventListener('click', function () {
    if (map.zoom > 0) map.setZoom(--map.zoom)
})

document.getElementById('big').addEventListener('click', function () {
    map.setZoom(++map.zoom)
})
var map,
    markers = ['no', 'ok', 'go'],
    marker = 0 // Selected marker
if (!navigator.geolocation)
    document.getElementById('now').style.display = 'none'
var m = document.getElementById('map')

map = new google.maps.Map(m, {
    center: new google.maps.LatLng(32.7858659, 130.7633434),
    zoom: 9,
    mapTypeId: google.maps.MapTypeId.ROADMAP
})
m.style.width = window.innerWidth + 'px'
m.style.height = window.innerHeight - (document.getElementById('post').clientHeight) - 80 + 'px';
var elem = document.getElementById('time'),
    n = new Date()
// Create time now
//     n    = new Date()
//
// // Create time now
// var month   = n.getMonth() + 1,
//     hours   = n.getHours(),
//     day     = n.getDate(),
//     minutes = n.getMinutes()
//
// month   = month.toString().length > 1 ? month : '0' + month
// hours   = hours.toString().length > 1 ? hours : '0' + hours
// day     = day.toString().length > 1 ? day : '0' + day
// minutes = minutes.toString().length > 1 ? minutes : '0' + minutes
elem.value = ''+ Math.round(Date.now()/1000);//'16' + month + hours + minutes
console.log(elem.value);
var nowPosition
map.addListener('click', function (e) {
    alert('位置を変更しました．間違いがなければ "投稿" ボタンをクリックしてください．')
    var latlng = e.latLng
    // Get status
    marker = Number(document.getElementById('flg').value)
    // console.log( 'set position : ', e )
    if (nowPosition) nowPosition.setMap(null)
    document.getElementById('locate').value = latlng.lat() + ',' + latlng.lng()
    nowPosition = new google.maps.Marker({
        position: latlng,
        map: map,
        icon: markers[marker] + '.png'
    })
})

function now() {
    navigator.geolocation.getCurrentPosition(function (position) {
            var data = position.coords
            var lat = data.latitude,
                lng = data.longitude
            document.getElementById('locate').value = lat + ',' + lng
            var latlng = new google.maps.LatLng(lat, lng),
                flg
            marker = document.getElementById('flg').value
            if (nowPosition) nowPosition.setMap(null)
            nowPosition = new google.maps.Marker({
                position: latlng,
                map: map,
                icon: markers[marker] + '.png'
            })
            alert('間違いがなければ "投稿" ボタンをクリックしてください．')
        },
        function (error) {
            var err_msg
            switch (error.code) {
                case 1:
                    errMsg = "位置情報の利用が許可されていません．設定から位置情報の使用を許可してください．"
                    break
                case 2:
                    errMsg = "デバイスの位置が判定できません．"
                    break
                case 3:
                    errMsg = "タイムアウトしました．"
                    break
            }
            alert("位置情報の取得に失敗しました．" + errMsg)
        }
    )
}

function updateValue() {
    if (!nowPosition) return
    var n1 = nowPosition.position.lat(),
        n2 = nowPosition.position.lng()
    marker = Number(document.getElementById('flg').value)
    nowPosition.setMap(null)
    nowPosition = new google.maps.Marker({
        position: new google.maps.LatLng(n1, n2),
        map: map,
        icon: markers[marker] + '.png'
    })
    return true
}