@if((isset($item->children) && count($item->children) > 0) || $item->editable || $item->agendaItemsAsChildren)
    <li>
        <b class="mr-1">{{ $preIndex . $index + 1 }}</b> {{ $item->title }}<br />
        @if($item->editable && $item->contentExists)
            {{ $item->expected_duration }} min &ndash; {{ implode(', ', isset($item->people) ? $item->people : []) }}
            @if(isset($item->goals) && count($item->goals) > 0)
                &ndash; {{ implode(', ', isset($item->goals) ? $item->goals : []) }}
            @endif
            @if($item->guest)
                &ndash; {{ __('messages.guest') }}
            @endif
            @if($item->internal)
                &ndash; {{ __('messages.internal') }}
            @endif
        @endif
        @if(isset($item->children) && count($item->children) > 0)
            <ol>
                @foreach($item->children as $i => $childItem)
                    @include('components.agenda-item-pdf', ['item' => $childItem, 'index' => $i, 'preIndex' => $preIndex  . $index + 1 . '.'])
                @endforeach
            </ol>
        @endif
    </li>
@else
    <li><b class="mr-1">{{ $preIndex . $index + 1 }}</b> {{ $item->title }}</li>
@endif
