<!DOCTYPE html>
<html>
<head>
    <title>Mapa Interactivo</title>
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC-rQn8dSdWqbDn2VmrzNFyApMBS1crarw&callback=initMap" async defer></script> -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC-rQn8dSdWqbDn2VmrzNFyApMBS1crarw&callback=initMap&libraries=geometry" async defer></script>
    <script>
let map;
let markers = []; // Arreglo para guardar los marcadores

function initMap() {
      // Estilo personalizado "Atlas Sencillo"
      var atlasSencilloStyle = [
        {elementType: 'geometry', stylers: [{color: '#f5f5f5'}]},
        {elementType: 'labels.icon', stylers: [{visibility: 'off'}]},
        {elementType: 'labels.text.fill', stylers: [{color: '#616161'}]},
        {elementType: 'labels.text.stroke', stylers: [{color: '#f5f5f5'}]},
        {featureType: 'administrative.land_parcel', elementType: 'labels.text.fill', stylers: [{color: '#bdbdbd'}]},
        {featureType: 'poi', elementType: 'geometry', stylers: [{color: '#eeeeee'}]},
        {featureType: 'poi', elementType: 'labels.text.fill', stylers: [{color: '#757575'}]},
        {featureType: 'poi.park', elementType: 'geometry', stylers: [{color: '#e5e5e5'}]},
        {featureType: 'poi.park', elementType: 'labels.text.fill', stylers: [{color: '#9e9e9e'}]},
        {featureType: 'road', elementType: 'geometry', stylers: [{color: '#ffffff'}]},
        {featureType: 'road.arterial', elementType: 'labels.text.fill', stylers: [{color: '#757575'}]},
        {featureType: 'road.highway', elementType: 'geometry', stylers: [{color: '#dadada'}]},
        {featureType: 'road.highway', elementType: 'labels.text.fill', stylers: [{color: '#616161'}]},
        {featureType: 'road.local', elementType: 'labels.text.fill', stylers: [{color: '#9e9e9e'}]},
        {featureType: 'transit.line', elementType: 'geometry', stylers: [{color: '#e5e5e5'}]},
        {featureType: 'transit.station', elementType: 'geometry', stylers: [{color: '#eeeeee'}]},
        {featureType: 'water', elementType: 'geometry', stylers: [{color: '#c9c9c9'}]},
        {featureType: 'water', elementType: 'labels.text.fill', stylers: [{color: '#9e9e9e'}]}
    ];
    // Opciones del mapa
    var mapOptions = {
                zoom: 14, // Ajusta el nivel de zoom según tus necesidades
                center: {lat: -28.053193, lng: -56.021368}, // Coordenadas de ejemplo
                disableDefaultUI: true, // Opcional: deshabilita la UI por defecto para un mapa más limpio
                zoomControl: true, // Opcional: habilita el control de zoom si es necesario
                styles: atlasSencilloStyle // Aplica el estilo personalizado al mapa
            };
    map = new google.maps.Map(document.getElementById('map'), mapOptions);

    map.addListener('click', function(e) {
        // Intenta encontrar y eliminar un marcador cercano. Si no encuentra, añade uno nuevo.
        if (!tryRemoveMarkerNear(e.latLng)) {
            addMarker(e.latLng);
        }
    });
}

function addMarker(location) {
    const marker = new google.maps.Marker({
        position: location,
        map: map,
        draggable: true, // Hace el marcador arrastrable
    });
    markers.push(marker); // Guarda el marcador en el arreglo
    // aca se puede hacer una llamada ajax para guardar la nueva posicion
    // Datos para enviar
    let data = {
        lat: location.lat(),
        lng: location.lng(),
        description: 'Descripción del marcador' // Aquí puedes añadir una lógica para obtener una descripción
    };
    // Enviar datos al servidor
    fetch('guardarMarcador.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `lat=${data.lat}&lng=${data.lng}&description=${encodeURIComponent(data.description)}`
    })
    .then(response => response.text())
    .then(data => {
        console.log('Success:', data);
    })
    .catch((error) => {
        console.error('Error:', error);
    });

    new google.maps.InfoWindow({
        content: 'Coordenadas: ' + location.lat() + ', ' + location.lng(),
        position: location
    }).open(map);
        // Evento que se dispara cuando se hace clic en el marcador
        marker.addListener('click', function() {
        const color = prompt("Ingrese el código de color HEX para el marcador:", "#FFFFFF");
        if (color) {
            marker.setIcon(getMarkerIcon(color));
        }
    });
    // Evento que se dispara cuando termina el arrastre del marcador
    marker.addListener('dragend', function(event) {
        // Aquí puedes actualizar la posición del marcador en tu lista o realizar otras acciones
        // Por ejemplo, actualizar la posición en el arreglo de marcadores:
        const index = markers.indexOf(marker);
        if (index !== -1) {
            markers[index].setPosition(event.latLng);
        }

        // Opcional: Mostrar las nuevas coordenadas o realizar alguna acción
        console.log('Nuevo lugar: ', event.latLng.lat(), event.latLng.lng());
        //aca se puede hacer una llamada ajax para guardar la nueva posicion

    });

}

function tryRemoveMarkerNear(location) {
    // Define un umbral de distancia para considerar "cerca" (en metros)
    const NEAR_THRESHOLD = 20; // Ajusta este valor según necesites

    // Encuentra el índice del marcador más cercano dentro del umbral
    const index = markers.findIndex(marker => {
        const markerPos = marker.getPosition();
        const distance = google.maps.geometry.spherical.computeDistanceBetween(location, markerPos);
        return distance < NEAR_THRESHOLD;
    });

    // Si se encontró un marcador cercano, elimínalo
    if (index !== -1) {
        markers[index].setMap(null); // Elimina el marcador del mapa
        markers.splice(index, 1); // Elimina el marcador del arreglo
        return true; // Retorna verdadero indicando que se eliminó un marcador
        // aca se puede hacer una llamada ajax para eliminar la posicion
    }

    return false; // Retorna falso si no se encontró ningún marcador cercano para eliminar
}
function getMarkerIcon(color) {
    return {
        path: google.maps.SymbolPath.CIRCLE,
        scale: 10,
        fillColor: color,
        fillOpacity: 0.8,
        strokeWeight: 3,
        strokeColor: "red",
    };
}

</script>
</head>
<body>
    <div id="map" style="width: 100%; height: 500px;"></div>
</body>
</html>