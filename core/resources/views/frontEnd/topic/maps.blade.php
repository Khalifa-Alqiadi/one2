@if(config('smartend.google_maps_key') !="")
    @if(count($Topic->maps) >0)
        <div class="row mb-4">
            <div class="col-lg-12">
                <h3 class="mb-3">{{ __('frontend.locationMap') }}</h3>
                <div id="google-map" class="mb-3"></div>
            </div>
        </div>
        @foreach($Topic->maps->slice(0,1) as $map)
            <?php
            $MapCenter = $map->longitude . "," . $map->latitude;
            ?>
        @endforeach
        <?php
        $map_title_var = "title_" . @Helper::currentLanguage()->code;
        $map_details_var = "details_" . @Helper::currentLanguage()->code;
        ?>
        @push('after-scripts')
            <script type="text/javascript"
                    src="//maps.google.com/maps/api/js?key={{ config('smartend.google_maps_key') }}&language={{@Helper::currentLanguage()->code}}&callback=Function.prototype"></script>

            <script type="text/javascript">
                // var iconURLPrefix = 'http://maps.google.com/mapfiles/ms/icons/';
                var iconURLPrefix = "{{ asset('assets/dashboard/images/')."/" }}";
                var icons = [
                    iconURLPrefix + 'marker_0.png',
                    iconURLPrefix + 'marker_1.png',
                    iconURLPrefix + 'marker_2.png',
                    iconURLPrefix + 'marker_3.png',
                    iconURLPrefix + 'marker_4.png',
                    iconURLPrefix + 'marker_5.png',
                    iconURLPrefix + 'marker_6.png'
                ]

                var locations = [
                        @foreach($Topic->maps as $map)
                    ['<?php echo "<strong>" . $map->$map_title_var . "</strong>" . "<br>" . $map->$map_details_var; ?>', <?php echo $map->longitude; ?>, <?php echo $map->latitude; ?>, <?php echo $map->id; ?>, <?php echo $map->icon; ?>],
                    @endforeach
                ];

                var map = new google.maps.Map(document.getElementById('google-map'), {
                    zoom: 6,
                    draggable: false,
                    scrollwheel: false,
                    center: new google.maps.LatLng(<?php echo $MapCenter; ?>),
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                });

                var infowindow = new google.maps.InfoWindow();

                var marker, i;

                for (i = 0; i < locations.length; i++) {
                    marker = new google.maps.Marker({
                        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                        icon: icons[locations[i][4]],
                        map: map
                    });

                    google.maps.event.addListener(marker, 'click', (function (marker, i) {
                        return function () {
                            infowindow.setContent(locations[i][0]);
                            infowindow.open(map, marker);
                        }
                    })(marker, i));
                }
            </script>
        @endpush
    @endif
@endif
