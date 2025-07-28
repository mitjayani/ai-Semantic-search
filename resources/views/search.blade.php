<!DOCTYPE html>
<html>

<head>
    <title>Semantic Search</title>
</head>

<body>
    <h2>Semantic Search</h2>
    <form method="GET" action="{{ route('search.submit') }}">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search for a category..." />
        <button type="submit">Search</button>
    </form>
    @if($noResults ?? false)
    <p>No results found for "{{ $query }}"</p>
    @endif
    @if(isset($results))
    <h3>Results:</h3>
    @forelse($results as $item)
    <div>
        <strong>{{ $item['category']->main_category }} → {{ $item['category']->sub_category }}</strong><br>
        Service: {{ $item['category']->service }}<br>
        Score: {{ number_format($item['score'], 4) }}
    </div>
    <hr>
    @empty
    <p>No results found.</p>
    @endforelse
    @endif
</body>

</html>