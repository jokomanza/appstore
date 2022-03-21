@if (is_array($data))
    @foreach ($data as $key => $value)
        @if (is_array($value))
            <p>{{ str_repeat('&emsp;&emsp;', $n) }}<strong>{{ $key }}</strong> : </p>
            @include('base.reports.components.recursive', ['data' => $value, 'n' => $n + 1])
        @elseif(is_object($value))
            <p>{{ str_repeat('&emsp;&emsp;', $n) }}<strong>{{ $key }}</strong> : </p>
            @include('base.reports.components.recursive', ['data' => (array) $value, 'n' => $n + 1])
        @else
            <p>{{ str_repeat("&emsp;&nbsp;", $n) }}<strong>{{ $key }}</strong>
                :
                {{ $value }}</p>
        @endif
    @endforeach
@endif
