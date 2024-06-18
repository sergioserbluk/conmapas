<!-- quiero insertar un mapa de google maps, para luego marcar puntos con el click del mouse, estos puntos los quiero guardar en una base de datos -->
<!-- AIzaSyC-rQn8dSdWqbDn2VmrzNFyApMBS1crarw -->
<!DOCTYPE html>
<html>
<head>
    <title>Mapa Interactivo</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC-rQn8dSdWqbDn2VmrzNFyApMBS1crarw&callback=initMap" async defer></script>
    
    <script>
let map;
let markers = []; // Arreglo para guardar los marcadores

function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: -28.053193, lng: -56.021368},
        zoom: 14
    });

    map.addListener('click', function(e) {
        const marker = new google.maps.Marker({
            position: e.latLng,
            map: map,
        });
        markers.push(marker); // Guarda el marcador en el arreglo

        new google.maps.InfoWindow({
            content: 'Coordenadas: ' + e.latLng.lat() + ', ' + e.latLng.lng(),
            position: e.latLng
        }).open(map);
    });

    
}
</script>
</head>
<body>
    <div id="map" style="width: 100%; height: 500px;"></div>
</body>
</html>