<div class="p-6 sm:px-8 sm:pb-8 space-y-8">
    <div class="flex flex-col sm:flex-row gap-4">
        <div class="space-y-4 flex-1">
            <flux:heading size="xl">{{ __('messages.agenda') }}</flux:heading>
        </div>
        <div class="flex gap-2">
            <flux:button
                variant="primary"
                icon="file-text"
                wire:click="saveAsPdf"
            >
                PDF
            </flux:button>
            <flux:button
                variant="primary"
                icon="mail"
            >
                {{ __('messages.invite') }}
            </flux:button>
            <flux:button
                variant="primary"
                icon="file-input"
            >
                {{ __('messages.createMinutes') }}
            </flux:button>
            <flux:button
                variant="primary"
                icon="pencil"
                wire:navigate
                href="{{ route('meeting.edit', ['committee' => $committee, 'meeting' => $meeting->id]) }}"
            >
                {{ __('messages.editMeeting') }}
            </flux:button>
        </div>
    </div>
    <div class="grid md:grid-cols-[1fr_23rem] gap-8">
        <div class="row-start-1 md:col-start-2 space-y-6">
            <flux:fieldset>
                <legend class="flex">
                    <div class="flex-1">{{ __('messages.meeting') }}</div>
                    <div class="-my-1 -mr-2">
                        <flux:tooltip content="{{ __('messages.saveEvent') }}">
                            <flux:button
                                variant="ghost"
                                size="sm"
                                icon="calendar-arrow-down"
                                wire:click="saveEvent"
                            />
                        </flux:tooltip>
                    </div>
                </legend>
                <div class="p-4 flex flex-wrap gap-5">
                    <div class="flex items-center gap-2">
                        <flux:icon.calendar-days class="size-4" aria-hidden="true" />
                        <span class="sr-only">{{ __('messages.date') }}: </span>
                        <span>{{ $meeting->date }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <flux:icon.clock class="size-4" aria-hidden="true" />
                        <span class="sr-only">{{ __('messages.time') }}: </span>
                        <span>{{ date('H:i', strtotime($meeting->time)) }}</span>
                    </div>
                </div>
                <div class="p-4 space-y-2">
                    <div class="flex items-center gap-2">
                        <flux:icon.building-2 class="size-4" aria-hidden="true" />
                        <span class="sr-only">{{ __('messages.address') }}: </span>
                        <span>{{ $meeting->address }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <flux:icon.door-closed class="size-4" aria-hidden="true" />
                        <span class="sr-only">{{ __('messages.room') }}: </span>
                        <span>{{ $meeting->room }}</span>
                    </div>
                </div>
                <div wire:ignore id="map" class="h-[16rem] rounded-t-none! border-none"></div>
            </flux:fieldset>

            <flux:fieldset>
                <legend class="flex">
                    <div class="flex-1">{{ __('messages.chairsAndMinuteTakers') }}</div>
                </legend>
                <div class="p-4 space-y-2">
                    <div class="flex items-center gap-2">
                        <flux:icon.user-star class="size-4" aria-hidden="true" />
                        <span class="sr-only">{{ __('messages.meetingChairs') }}: </span>
                        <span>{{ implode(', ', json_decode($meeting->meeting_chairs)) }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <flux:icon.user-pen class="size-4" aria-hidden="true" />
                        <span class="sr-only">{{ __('messages.minuteTakers') }}: </span>
                        <span>{{ implode(', ', json_decode($meeting->minute_takers)) }}</span>
                    </div>
                </div>
            </flux:fieldset>
        </div>
        <div class="grid space-y-6">
            @if(count($minutesStructure) > 0)
                <ol class="space-y-6 md:col-start-1 md:row-start-1">
                    @foreach($minutesStructure as $index => $item)
                        @include('components.agenda-item', ['item' => $item, 'index' => $index, 'preIndex' => ''])
                    @endforeach
                </ol>
            @else
                <div class="md:col-start-1 md:row-start-1">
                    <flux:callout variant="warning" icon="info" heading="{{ __('messages.noMinutesStructureSpecified') }}" inline />
                </div>
            @endif
        </div>
    </div>
</div>

@script
<script>
    var map = new maplibregl.Map({
        container: 'map',
        style: "{{ config('app.map.tiles') }}",
        center: [{{ $meeting->longitude }}, {{ $meeting->latitude }}],
        zoom: 13,
    });
    map.addControl(new maplibregl.NavigationControl())
    map.addControl(new maplibregl.GeolocateControl({
        positionOptions: { enableHighAccuracy: true },
        trackUserLocation: true,
    }));

    let marker = new maplibregl.Marker({
        color: '#0069a8',
    });
    marker.setLngLat([{{ $meeting->longitude }}, {{ $meeting->latitude }}]);
    marker.addTo(map);
</script>
@endscript
