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

    .mb-4 {
        margin-bottom: 1rem;
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

    ol.first {
        margin-left: -2.3rem;
    }

    ol li {
        list-style-type: none;
        margin-top: .3rem;
        margin-bottom: .3rem;
    }

    .mr-1 {
        margin-right: .25rem;
    }
</style>

<div id="footer">
    <div class="page-number"></div>
</div>

<h1>{{ __('messages.agenda') }}</h1>

<table class="table mb-4">
    <tr>
        <th>{{ __('messages.committee') }}:</th>
        <td>{{ $committee }}</td>
    </tr>
    <tr>
        <th>{{ __('messages.date') }}:</th>
        <td>{{ $meeting->date }}, {{ $meeting->time }}</td>
    </tr>
    <tr>
        <th>{{ __('messages.address') }}:</th>
        <td>{{ $meeting->address }}</td>
    </tr>
    <tr>
        <th>{{ __('messages.room') }}:</th>
        <td>{{ $meeting->room }}</td>
    </tr>
    <tr>
        <th>{{ __('messages.meetingChairs') }}:</th>
        <td>{{ implode(', ', json_decode($meeting->meeting_chairs)) }}</td>
    </tr>
    <tr>
        <th>{{ __('messages.minuteTakers') }}:</th>
        <td>{{ implode(', ', json_decode($meeting->minute_takers)) }}</td>
    </tr>
</table>

<ol class="first">
    @foreach($agenda as $index => $item)
        @include('components.agenda-item-pdf', ['item' => $item, 'index' => $index, 'preIndex' => ''])
    @endforeach
</ol>
