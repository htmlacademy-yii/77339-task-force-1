window.onload = function () {
    const map = document.getElementById('map');

    if (!map) {
        return;
    }

    const latitude = document.getElementById('latitude').value;
    const longitude = document.getElementById('longitude').value;

    ymaps.ready(init);
    function init(){
        var myMap = new ymaps.Map("map", {
            center: [longitude, latitude],
            zoom: 15
        }, {
                searchControlProvider: 'yandex#search'
            }),
            myPlacemark = new ymaps.Placemark([longitude, latitude], {
                // Чтобы балун и хинт открывались на метке, необходимо задать ей определенные свойства.
                balloonContentHeader: "Локация задания",
                balloonContentBody: "Адрес указан <b>автором</b> задания",
                balloonContentFooter: "TaskForce",
                hintContent: "Локация"
            });
        myMap.geoObjects.add(myPlacemark);
    }
}