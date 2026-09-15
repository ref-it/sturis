<div class="p-6 sm:px-8 space-y-8">
    <div class="space-y-4">
        <flux:heading size="xl">{{ __('messages.addCommittee') }}</flux:heading>
    </div>
    <div class="grid gap-8">
        <div class="space-y-8">

            <flux:fieldset>
                <legend class="flex">
                    <div class="flex-1">{{ __('messages.general') }}</div>
                </legend>
                <div class="p-4 space-y-4">
                    <div class="grid sm:grid-cols-2 lg:grid-cols-[1fr_1fr_1fr_auto] gap-4">
                        <flux:field>
                            <flux:label>{{ __('messages.token') }}</flux:label>
                            <flux:input wire:model="token" />
                            <flux:error name="token" />
                        </flux:field>
                        <flux:field>
                            <flux:label>{{ __('messages.name') }}</flux:label>
                            <flux:input wire:model="name" />
                            <flux:error name="name" />
                        </flux:field>
                        <flux:field>
                            <flux:label>{{ __('messages.shortName') }}</flux:label>
                            <flux:input wire:model="shortName" />
                            <flux:error name="shortName" />
                        </flux:field>
                        <flux:field>
                            <flux:label>{{ __('messages.active') }}</flux:label>
                            <div class="block">
                                <flux:switch wire:model="isActive" />
                            </div>
                        </flux:field>
                    </div>
                    <flux:field>
                        <flux:label>{{ __('messages.minutesStructure') }}</flux:label>
                        <flux:textarea wire:model="minutesStructure" class="h-[15rem] lg:h-[20rem] font-mono" />
                        <flux:error name="minutesStructure" />
                    </flux:field>
                </div>
            </flux:fieldset>

            <flux:fieldset>
                <legend class="flex">
                    <div class="flex-1">{{ __('messages.meetingDate') }}</div>
                </legend>
                <div class="p-4 grid sm:grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label>{{ __('messages.defaultWeekday') }}</flux:label>
                        <flux:select variant="listbox" wire:model="defaultWeekday">
                            @foreach($weekdays as $wd)
                                <flux:select.option value="{{ $wd['id'] }}">{{ $wd['name'] }}</flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:error name="defaultWeekday" />
                    </flux:field>
                    <flux:field>
                        <flux:label>{{ __('messages.defaultTime') }}</flux:label>
                        <flux:input type="time" wire:model="defaultTime" />
                        <flux:error name="defaultTime" />
                    </flux:field>
                </div>
            </flux:fieldset>
        </div>
        <div class="space-y-8">
            <flux:fieldset>
                <legend class="flex">
                    <div class="flex-1">{{ __('messages.meetingPlace') }}</div>
                </legend>
                <div class="p-4 space-y-4">
                    <div class="grid sm:grid-cols-2 gap-4">
                        <flux:field>
                            <flux:label>{{ __('messages.defaultAddress') }}</flux:label>
                            <flux:input wire:model="defaultAddress" />
                            <flux:error name="defaultAddress" />
                        </flux:field>
                        <flux:field>
                            <flux:label>{{ __('messages.defaultRoom') }}</flux:label>
                            <flux:input wire:model="defaultRoom" />
                            <flux:error name="defaultRoom" />
                        </flux:field>
                    </div>
                    <flux:field>
                        <flux:label>{{ __('messages.map') }}</flux:label>
                        <div wire:ignore id="map" class="z-10 h-[16rem]"></div>
                        <flux:description>{!! config('app.map.nominatim.attribution') !!}</flux:description>
                    </flux:field>
                </div>
            </flux:fieldset>
        </div>
    </div>
    <div class="py-6 -mx-8 -mb-6 mt-auto px-8 flex items-center justify-end gap-x-4 border-t border-zinc-200 dark:border-zinc-900 bg-zinc-100 dark:bg-zinc-800">
        <flux:button icon="ban" wire:navigate href="{{ route('committees') }}">{{ __('messages.cancel') }}</flux:button>
        <flux:button variant="primary" icon="save" wire:click="$js.save">{{ __('messages.save') }}</flux:button>
    </div>
</div>

@script
<script>
    var map = new maplibregl.Map({
        container: 'map',
        style: "{{ config('app.map.tiles') }}",
        center: [{{ $defaultLongitude }}, {{ $defaultLatitude }}],
        zoom: 13,
    });
    map.addControl(new maplibregl.NavigationControl());

    let marker = new maplibregl.Marker({
        color: '#0069a8',
        draggable: true,
    });
    marker.setLngLat([{{ $defaultLongitude }}, {{ $defaultLatitude }}]);
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
                    features.push(point);
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
        $wire.defaultLatitude = lngLat.lat.toFixed(6);
        $wire.defaultLongitude = lngLat.lng.toFixed(6);
        $wire.save();
    })
</script>
@endscript
