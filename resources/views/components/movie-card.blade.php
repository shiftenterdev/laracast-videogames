<div class="game mt-8">
    <div class="relative inline-block">
        <a href="{{ route('movie.show', $movie['id']) }}">
            <img src="{{ $movie['primaryImage']['url'] }}" alt="game cover" class="hover:opacity-75 transition ease-in-out duration-150">
        </a>
        @if (isset($movie['ratingsSummary']))
            <div id="{{ $movie['id'] }}" class="absolute bottom-0 right-0 w-16 h-16 bg-gray-800 rounded-full" style="right: -20px; bottom: -20px">
            </div>

            @push('scripts')
                @include('_rating', [
                    'slug' => $movie['id'],
                    'rating' => $movie['ratingsSummary']['aggregateRating'],
                    'event' => null,
                ])
            @endpush
        @endif
    </div>
    <a href="{{ route('games.show', $movie['id']) }}" class="block text-base font-semibold leading-tight hover:text-gray-400 mt-8">{{ $movie['titleText']['text'] }}</a>
    <div class="text-gray-400 mt-1">
        {{ $movie['releaseYear']['year'] }}
    </div>
</div>
