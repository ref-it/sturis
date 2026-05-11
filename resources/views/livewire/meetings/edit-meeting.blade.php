<div class="p-6 sm:px-8 sm:pb-8 space-y-8">
    <div class="space-y-4">
        <flux:heading size="xl">{{ __('messages.editMeeting') }}</flux:heading>
    </div>
    <div class="space-y-6">
        <div class="grid md:grid-cols-2 xl:grid-cols-[1fr_29rem] gap-6">
            <div class="space-y-6">
                <div class="grid sm:grid-cols-2 gap-6">
                    <flux:field>
                        <flux:label>{{ __('messages.date') }}</flux:label>
                        <flux:input type="date" wire:model="date" />
                        <flux:error name="date" />
                    </flux:field>
                    <flux:field>
                        <flux:label>{{ __('messages.time') }}</flux:label>
                        <flux:input type="time" wire:model="time" />
                        <flux:error name="time" />
                    </flux:field>
                </div>
                <flux:field>
                    <flux:label>{{ __('messages.meetingChairs') }}</flux:label>
                    <flux:pillbox wire:model="meetingChairs" multiple searchable placeholder="{{ __('messages.selectPeople') }}">
                        @foreach($members as $m)
                            <flux:pillbox.option>{{ $m->name }}</flux:pillbox.option>
                        @endforeach
                    </flux:pillbox>
                    <flux:error name="meetingChairs" />
                </flux:field>
                <flux:field>
                    <flux:label>{{ __('messages.minuteTakers') }}</flux:label>
                    <flux:pillbox wire:model="minuteTakers" multiple searchable placeholder="{{ __('messages.selectPeople') }}">
                        @foreach($members as $m)
                            <flux:pillbox.option>{{ $m->name }}</flux:pillbox.option>
                        @endforeach
                    </flux:pillbox>
                    <flux:error name="minuteTakers" />
                </flux:field>
                <div class="grid sm:grid-cols-2 gap-6">
                    <flux:field>
                        <flux:label>{{ __('messages.address') }}</flux:label>
                        <flux:input wire:model="address" />
                        <flux:error name="address" />
                    </flux:field>
                    <flux:field>
                        <flux:label>{{ __('messages.room') }}</flux:label>
                        <flux:input wire:model="room" />
                        <flux:error name="room" />
                    </flux:field>
                </div>
            </div>
            <div>
                <flux:field>
                    <flux:label>{{ __('messages.map') }}</flux:label>
                    <div id="map" class="z-10 h-[15rem] md:h-[20.25rem]"></div>
                    <flux:description>{!! config('app.map.nominatim.attribution') !!}</flux:description>
                </flux:field>
            </div>
        </div>
        <div class="flex gap-2 justify-end">
            <flux:button icon="ban" wire:navigate href="{{ route('meetings', $committee) }}">{{ __('messages.cancel') }}</flux:button>
            <flux:button variant="primary" icon="save" wire:click="$js.save">{{ __('messages.save') }}</flux:button>
        </div>
    </div>
</div>

@script
<script>
    var map = new maplibregl.Map({
        container: 'map',
        style: "{{ config('app.map.tiles') }}",
        center: [{{ $longitude }}, {{ $latitude }}],
        zoom: 13,
    });
    map.addControl(new maplibregl.NavigationControl())
    map.addControl(new maplibregl.GeolocateControl({
        positionOptions: { enableHighAccuracy: true },
        trackUserLocation: true,
    }));

    let marker = new maplibregl.Marker({
        color: '#0069a8',
        draggable: true,
    });
    marker.setLngLat([{{ $longitude }}, {{ $latitude }}]);
    marker.addTo(map);

    const geocoderApi = {
        forwardGeocode: async (config) => {
            const features = [];
            try {
                const request =
            `{{ config('app.map.nominatim.search_url') }}?q=${
                config.query
            }&format=geojson&polygon_geojson=1&addressdetails=1`;
                const response = await fetch(request);
                const geojson = await response.json();
                for (const feature of geojson.features) {
                    const center = [
                        feature.bbox[0] +
                    (feature.bbox[2] - feature.bbox[0]) / 2,
                        feature.bbox[1] +
                    (feature.bbox[3] - feature.bbox[1]) / 2
                    ];
                    const point = {
                        type: 'Feature',
                        geometry: {
                            type: 'Point',
                            coordinates: center
                        },
                        place_name: feature.properties.display_name,
                        properties: feature.properties,
                        text: feature.properties.display_name,
                        place_type: ['place'],
                        center
                    };
                    if (features.length < 1) {
                        features.push(point);
                    }
                }
            } catch (e) {
                console.error(`Failed to forwardGeocode with error: ${e}`);
            }

            if (features.length > 0) {
                marker.setLngLat(features[0].geometry.coordinates);
            }

            return {
                features
            };
        }
    };
    map.addControl(
        new MaplibreGeocoder(geocoderApi, {
            maplibregl
        }), 'top-left'
    );

    $js('save', () => {
        const lngLat = marker.getLngLat();
        $wire.latitude = lngLat.lat.toFixed(6);
        $wire.longitude = lngLat.lng.toFixed(6);
        $wire.save();
    })
</script>
@endscript
