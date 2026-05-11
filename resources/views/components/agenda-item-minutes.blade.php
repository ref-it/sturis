@foreach($agendaItems as $item)
    @if(!$item['agendaItemsAsChildren'])
        //{{ __('itemReportingPerson') }}, {{ __('messages.itemEstimatedTime') }}, {{ __('messages.itemActualTime')  }}, {{ __('messages.itemGoals') }}//
            
        // Check if item is internal and mark internal part if true

            {{-- Inhalte --}}

        // end internal
    @endif

    @if($item['children'])
        @include('components.agenda-item', ['agendaItems' => $item['children']])
    @endif
@endforeach
