<li class="py-3 flex items-center">
    <div class="flex-1 flex flex-col gap-2">
        <div>
            {{ $member->name }}
        </div>
        @if(count($member->job) > 0)
            <div class="flex flex-wrap gap-2">
                @foreach($member->job as $j)
                    <flux:badge size="sm">{{ $j }}</flux:badge>
                @endforeach
            </div>
        @endif
    </div>
</li>