"use strict";

$().ready(function () {

    $('.alert').delay(8000).fadeOut();

    $('.delete-event').on('click', function () {
        if (!confirm('Вы действительно хотите удалить событие?')) {
            event.preventDefault();
        }
    });

    $('.delete-branch').on('click', function () {
        if (!confirm('Вы действительно хотите удалить филиал?')) {
            event.preventDefault();
        }
    });

    $('.filter-menu div label').on('click', function () {
        setTimeout(function () {
            var category = '';
            var obj = $('.filter-menu div input');

            var arrayCat = [].slice.call(obj);

            arrayCat.forEach(function (v, k) {
                if (category) {
                    if (v.checked === true) {
                        category += ',' + k;
                    }
                } else {
                    if (v.checked === true) {
                        category += k;
                    }
                }
            });

            if (category) {

                var lang = getParamFromHref('lang');

                $.ajax({
                    dataType: 'json',
                    type: 'GET',
                    url: '/events/filter?lang=' + lang + '&categories=' + category,

                    error: function (request, error) {
                        console.log(" Can't get objects because: " + error);
                    },
                    success: function (data) {

                        Map.myMap.geoObjects.removeAll();

                        $('.events-content .list .row.event').detach();

                        data.forEach(function (v, k) {

                            Map.addObjectsOnMap(v);

                            v.name = (v.name) ? v.name : 'Мероприятие';

                            var guest = '';
                            if (v.guest) {
                                guest += `
                                <div class="row">
                                    <div class="12u$(medium)">G: ` + v.guest + `</div>
                                </div>
                            `;
                            }

                            var image = '';
                            if (v.image) {
                                image += `
                               <a href="/events?id=` + v.id + `" style="border: none;">
                                  <img class="mr-3" src="` + v.image + `">
                               </a>
                            `
                            }
                            ;

                            var address = '';
                            if (v.city) {
                                address = v.country + ', ' + v.city;
                            } else {
                                address = v.country;
                            }

                            var html = `
                            <div class="row event">
                                <div class="3u 12u$(medium)" style="width: 150px;">
                                    ` + image + `
                                </div>
                                <div class="9u 12u$(medium)">
                                    <div class="row types-event-title">
                                        <div class="9u 12u$(medium)">
                                            <h4>
                                                <a href="/events?id=` + v.id + `">` + v.name + `</a>
                                            </h4>
                                        </div>
                                        <div class="3u 12u$(medium) types-events-date">
                                            <span>` + v.begin_date + `</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="12u$(medium)">A: ` + address + `</div>
                                    </div>
                                    <div class="row">
                                        <div class="12u$(medium)">O: ` + v.organizer + `</div>
                                    </div>`
                                + guest +
                                `</div>
                            </div>
                        `;

                            $('.events-content .list').append(html);

                        });

                    }
                });
            } else {
                $('.events-content .list .row.event').detach();
                Map.myMap.geoObjects.removeAll();
            }

        }, 200);
    });

    const Map = {
        selEventsContent: '.events-content',
        selMap: '#map',
        selHeader: '#header',
        myMap: '',
        selEvConList: '.events-content .list',
        selFilterMenu: '.filter-menu',
        defaultRun: function () {

            Map.align();

            Map.init();

            Map.getObjects();

            Map.modifiedEventsPage();

            Map.events();

            Map.openContent();

        },
        eventsAddAndEditPageRun: function () {
            $("#street, #house, #block").keyup(function (event) {
                Map.getAddress();
                Map.getMapsPoint(event);
            });

            $("#country, #city").change(function (e) {
                Map.getAddress();
                Map.getMapsPoint(e);
            });

            var coords = [55.76, 37.64];
            var coordX = $('#coord_x').val();
            var coordY = $('#coord_y').val();
            if (coordX !== '' && coordY !== '') {
                coords = [coordX, coordY];
            }

            Map.myMap = new ymaps.Map("eventsAddMap", {
                center: coords,
                zoom: 12,
                controls: ['geolocationControl']
            });

            let myPlacemark = new ymaps.Placemark(coords, {}, {
                preset: "islands#dotIcon",
                iconColor: '#f56a6a'
            });
            Map.myMap.geoObjects.add(myPlacemark);
        },
        init: function () {

            Map.myMap = new ymaps.Map("map", {
                center: [55.76, 37.64],
                zoom: 5,
                controls: ['geolocationControl']
            });

        },
        align: function () {
            let wHeight = $(window).height();
            let wWidth = $(window).width();
            let mapHeight = wHeight - 125;
            let mapWidth = (wWidth - 166) / 2;
            $(Map.selMap).css('height', mapHeight);
            $(Map.selMap).css('width', mapWidth);
        },
        modifiedEventsPage: function () {
            let width = ($(window).width() - 156) + 'px';
            $(Map.selHeader).css('position', 'fixed');
            $(Map.selHeader).css('z-index', '1000');
            $(Map.selHeader).css('background', 'white');
            $(Map.selHeader).css('width', width);
            $(Map.selEventsContent).css('margin-top', '125px');
            $(Map.selEventsContent).css('width', '100%');
        },
        openContent: function () {
            $(Map.selEventsContent).css('display', '');
        },
        events: function () {
            $('#sidebar .toggle').on('click', function () {
                if ($(Map.selMap).css('display') !== 'none') {

                    let width = ($(window).width() - (156 + 315)) + 'px';
                    $(Map.selFilterMenu).css('width', width);
                    $(Map.selHeader).css('width', width);

                    $(Map.selMap).css('display', 'none');
                    $(Map.selEvConList).attr('class', '12u$(medium) list');
                    $(Map.selEvConList).css('width', '100%');
                } else {
                    let width = ($(window).width() - 156) + 'px';
                    $(Map.selFilterMenu).css('width', width);
                    $(Map.selHeader).css('width', width);
                    $(Map.selMap).css('display', '');
                    $(Map.selEvConList).attr('class', '8u 12u$(medium) list');
                    $(Map.selEvConList).css('width', '50%');
                }
            });
        },
        getObjects: function () {

            var lang = getParamFromHref('lang');

            $.ajax({
                dataType: 'json',
                type: 'POST',
                cache: false,
                url: '/events/get-objects?lang=' + lang,

                error: function (request, error) {
                    console.log(" Can't get objects because: " + error);
                },
                success: function (data) {

                    data.forEach(function (v, k) {

                        Map.addObjectsOnMap(v);

                    });
                }
            });
        },
        addObjectsOnMap: function (v) {
            var guest = '';
            if (v.guest) {
                guest = '<p>G: ' + v.guest + '</p>';
            }

            var myPlacemark = new ymaps.Placemark([v.coord_x, v.coord_y], {
                balloonContent: `
                                <div class="row maps-events-card">
                                    <div class="col-4">
                                        <a href="/events?id=` + v.id + `">
                                            <img src="` + v.image + `" width="50" height="50">
                                        </a>
                                    </div>
                                    <div class="col-8">
                                        <a href="/events?id=` + v.id + `"><strong>` + v.name + `</strong></a>
                                        <p>O: ` + v.organizer + `</p>
                                        ` + guest + `
                                    </div>
                                </div>
                            `,
            }, {
                preset: "islands#dotIcon",
                iconColor: '#f56a6a'
            });
            Map.myMap.geoObjects.add(myPlacemark);
        },
        getMapsPoint: function (event) {
            var address = $('#address').val();
            ymaps.ready(function () {
                ymaps.geocode(address).then(
                    function (res) {
                        let coords = res.geoObjects.get(0).geometry.getCoordinates();
                        $('#coord_x').val(coords[0]);
                        $('#coord_y').val(coords[1]);
                        Map.myMap.geoObjects.removeAll();
                        Map.myMap.setCenter(coords, 12, {checkZoomRange: false});
                        let myPlacemark = new ymaps.Placemark([coords[0], coords[1]], {}, {});
                        Map.myMap.geoObjects.add(myPlacemark);
                    }
                );
            });
        },
        getAddress: function () {

            let countryId = $("#country").val();
            let selCountry = "#country option[value='" + countryId + "']";
            let country = $(selCountry).text();

            let cityId = $("#city").val();
            let selCity = "#city option[value='" + cityId + "']";
            let city = $(selCity).text();

            let street = $("#street").val();
            let home = $("#house").val();
            let block = $("#block").val();
            let address = country + ', ' + city + ', ' + street + ', ' + home + '/' + block;
            $('#address').val(address);
        }
    };

    ymaps.ready(function () {

        let href = window.location.href;
        if (href.indexOf('events/add') > -1 || href.indexOf('admin/events/add') > -1
            || href.indexOf('personal/edit-event') > -1 || href.indexOf('admin/events/edit') > -1) {
            Map.eventsAddAndEditPageRun();
        } else if (href.indexOf('events') > -1) {
            Map.defaultRun();
        }

        $("#country, #city").change(function (e) {
            if (e.target.id === 'country') {
                loadCities();
            }
            if ($(this).val() === 'another') {
                addNewCity();
            }
        });
    });

});

function changeData(obj, table) {
    $(obj).animate({
        opacity: "toggle"
    }, 300, "linear", function () {
        $(obj).after("<div>Сохранено</div>");
    });

    let data = {
        str: $(obj).val(),
        id: $(obj).parent().parent().attr('data-id'),
        column: $(obj).attr('name'),
        table: table,
    };

    $.ajax({
        dataType: 'json',
        type: 'POST',
        url: '/admin/main/change-data',
        data: data,
        success: function () {
            return true;
        }
    });
}

function getParamFromHref(param) {
    var href = location.href;
    var array = [];

    if (href.indexOf('?') > -1) {

        var query = href.split('?')[1];

        // getting params if only 1 has
        if (query.indexOf('&') === -1) {

            let a = query.split('=');
            array[a[0]] = a[1];

        } else {

            var aQuery = query.split('&');
            aQuery.forEach(function (v, k) {
                let a = aQuery[k].split('=');
                array[a[0]] = a[1];
            });

        }

    } else {
        if (param !== 'lang') {
            alert("In href don't have any params");
            return false;
        }
        console.log($('html').attr('lang'));
    }

    if (array[param] !== undefined) {
        return array[param];
    } else {
        alert("Param " + param + " not find!");
        return false;
    }
}

function addNewCity() {
    var newCity = prompt(LANG.please_write_city);
    var selCity = 'select#city';
    if (newCity) {
        if (existDublicateCity(newCity)) {
            alert(LANG.city_already_exists);
            $(selCity).val('');
            return false;
        }
        $('option[class="new-city"]').detach();
        $(selCity).append(`<option class="new-city" value="new_` + newCity + `">` + newCity + `</option>`);
        $(selCity).val(`new_` + newCity);
    } else {
        $(selCity).val('');
    }
}

function loadCities() {

    $('select#city').html('');
    var countryId = $('select#country').val();

    var lang = (location.href.indexOf('admin') > -1) ? 'ru' : getParamFromHref('lang');

    $.ajax({
        dataType: 'json',
        type: 'GET',
        url: '/events/get-cities?id=' + countryId + '&lang=' + lang,

        success: function (data) {
            var html = '';
            data.forEach(function (v, k) {
                html += `<option value="` + v.id + `">` + v.name + `</option>`;
            });
            html += `<option value=""></option>`;
            html += `<option value="another">....` + LANG.another + `....</option>`;

            $('select#city').html(html);
        }
    })
}

function existDublicateCity(newCity) {
    var obj = $('select#city option');

    for (var i = 0; i < obj.length; i++) {
        var cityInOption = $(obj[i]).text();
        if (cityInOption.toLowerCase() === newCity.toLowerCase()) {
            return true;
        }
    }

    return false;
}
