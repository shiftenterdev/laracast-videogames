<div wire:init="loadTopMovies" class="popular-games text-sm grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 xl:grid-cols-6 gap-12 border-b border-gray-800 pb-16">
    @forelse ($topMovies as $movie)
        <x-movie-card :movie="$movie"/>
    @empty
        @foreach (range(1, 12) as $movie)
            <div class="game mt-8">
                <div class="relative inline-block">
                    <div class="bg-gray-800 w-44 h-56"></div>
                </div>
                <div class="block text-transparent text-lg bg-gray-700 rounded leading-tight mt-4">Title goes here</div>
                <div class="text-transparent bg-gray-700 rounded inline-block mt-3">PS4, PC, Switch</div>
            </div>
        @endforeach
    @endforelse
</div> <!-- end popular-games -->

@push('scripts')
    @include('_rating', [
        'event' => 'gameWithRatingAdded'
    ])
@endpush
