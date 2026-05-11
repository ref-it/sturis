<style>
    body {
        font-family: sans-serif;
        font-size: 11pt;
        padding-left: 3rem;
        padding-right: 3rem;
        padding-top: 1rem;
        padding-bottom: 1rem;
        word-wrap: break-word;
        hyphens: auto;
    }

    h1 {
        margin-top: 0;
        font-size: 15pt;
    }

    h2 {
        font-size: 13pt;
        margin-top: 2rem;
    }
    
    hr {
        border: none;
        border-bottom: 1px solid black;
    }

    th {
        text-align: left;
        vertical-align: top;
        width: 8rem;
        padding-left: 0;
    }

    .no-padding {
        padding: 0;
    }

    .signing-field {
        height: 5rem;
        width: 11.8rem;
        border-bottom: 1px solid black;
        margin-right: 1rem;
    }

    .table {
        border-collapse: collapse;
        width: 100%;
    }

    .table th,
    .table td {
        border-top: 1px solid #ddd;
        padding: .5rem .75rem;
    }

    .table tr th:first-of-type,
    .table tr td:first-of-type {
        padding-left: 0;
    }

    .table tr td:last-of-type {
        padding-right: 0;
    }

    .table tr:last-of-type {
        border-bottom: 1px solid #ddd;
    }

    .float-left {
        float: left;
    }

    .text-small {
        font-size: 9pt;
    }

    .mt-4 {
        margin-top: 1rem;
    }

    .mb-8 {
        margin-bottom: 2rem;
    }

    .-mb-2 {
        margin-bottom: -.5rem;
    }

    p {
        margin: 0;
        margin-bottom: .5rem;
    }

    p:last-of-type {
        margin-bottom: 0;
    }

    hr {
        border-color: #ddd;
    }

    #footer {
        width: calc(100% - 6rem);
        position: fixed;
        bottom: 1rem;
        border-top: 1px solid #ddd;
        padding-top: .5rem;
    }

    .page-number:before {
        float: right;
        content: counter(page);
    }

    .w-number {
        min-width: .5cm;
        max-width: .5cm !important;
    }
</style>

<div id="footer">
    <div class="page-number"></div>
</div>

<h1>{{ __('messages.resolutions') }}</h1>

<table class="table mb-8">
    <tr>
        <th class="w-number">{{ __('messages.committee') }}</th>
        <td>{{ $committee }}</td>
    </tr>
    @if($term)
        <tr>
            <th>{{ __('messages.term') }}</th>
            <td>{{ $term }}</td>
        </tr>
    @endif
    @if($meeting)
        <tr>
            <th>{{ __('messages.meeting') }}</th>
            <td>
                @php
                    $index = 0;
                @endphp
                @foreach($meetings as $m)@if(in_array($m->id, $meeting))@if($index > 0), @endif{{ $m->date }}@php $index++; @endphp@endif@endforeach
            </td>
        </tr>
    @endif
    @if($type)
        <tr>
            <th>{{ __('messages.type') }}</th>
            <td>
                @php
                    $index = 0;
                @endphp
                @foreach($types as $t)@if(in_array($t['id'], $type))@if($index > 0), @endif{{ $t['title'] }}@php $index++; @endphp@endif@endforeach
            </td>
        </tr>
    @endif
</table>

<table class="table">
    <thead>
        <tr>
            <th class="w-number">{{ __('messages.number') }}</th>
            <th>{{ __('messages.text') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($resolutions as $r)
            <tr>
                <td><b>{{ $r->number }}</b></td>
                <td>
                    <p>{{ $r->text }}</p>
                    <hr />
                    <p>
                        <b>{{ __('messages.yes') }}:</b> {{ $r->yes }},&nbsp;
                        <b>{{ __('messages.no') }}:</b> {{ $r->no }},&nbsp;
                        <b>{{ __('messages.abstention') }}:</b> {{ $r->abstention }},&nbsp;
                        <b>{{ __('messages.result') }}:</b> {{ $r->result }}
                    </p>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
