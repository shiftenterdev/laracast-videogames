<?php

namespace App\Http\Api;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GamesApi
{
    private function authenticateAccessToken()
    {
        $accessTokenCacheKey = 'igdb_cache.access_token';

        $accessToken = cache()->get($accessTokenCacheKey, '');

        if ($accessToken) {
            return $accessToken;
        }

        try {
            $query = http_build_query([
                'client_id' => env('IGDB_CLIENT_ID'),
                'client_secret' => env('IGDB_ACCESS_TOKEN'),
                'grant_type' => 'client_credentials',
            ]);
            $response = Http::post(config('services.igdb.authentication_url').'?'.$query)
                ->throw()
                ->json();

            if (is_array($response) && isset($response['access_token']) && $response['expires_in']) {
                cache()->put(
                    $accessTokenCacheKey,
                    (string)$response['access_token'],
                    (int)$response['expires_in'] - 60
                );

                $accessToken = $response['access_token'];
            }
        } catch (\Exception) {
            throw new \Exception('Access Token could not be retrieved from Twitch.');
        }

        return (string) $accessToken;
    }

    public function getComingSoon()
    {
        $current = Carbon::now()->timestamp;

        // Coming Soon is also not very accurate without the popularity field anymore :(
        $comingSoonUnformatted = Http::withHeaders([
            'Client-ID' => env('IGDB_CLIENT_ID'),
            'Authorization' => 'Bearer '.$this->authenticateAccessToken()
        ])
            ->withBody(
                "fields name, cover.url, first_release_date, platforms.abbreviation, rating, rating_count, summary, slug;
                    where platforms = (48,49,130,6)
                    & (first_release_date >= {$current}
                    );
                    sort first_release_date asc;
                    limit 4;
                ", "text/plain"
            )->post(config('services.igdb.endpoint'))
            ->json();

        return $comingSoonUnformatted;
    }

    public function mostAnticipated()
    {
        $current = Carbon::now()->timestamp;
        $afterFourMonths = Carbon::now()->addMonths(4)->timestamp;

        // Most Anticipated is not very accurate without the popularity field anymore :(
        $mostAnticipatedUnformatted = Http::withHeaders([
            'Client-ID' => env('IGDB_CLIENT_ID'),
            'Authorization' => 'Bearer '.$this->authenticateAccessToken()
        ])
            ->withBody(
                "fields name, cover.url, first_release_date, total_rating_count, platforms.abbreviation, rating, rating_count, summary, slug;
                    where platforms = (48,49,130,6)
                    & (first_release_date >= {$current}
                    & first_release_date < {$afterFourMonths}
                    );
                    sort total_rating_count desc;
                    limit 4;", "text/plain"
            )->post(config('services.igdb.endpoint'))
            ->json();

        return $mostAnticipatedUnformatted;
    }

    public function recentlyReviewed()
    {
        $before = Carbon::now()->subMonths(2)->timestamp;
        $current = Carbon::now()->timestamp;

        return Http::withHeaders([
            'Client-ID' => env('IGDB_CLIENT_ID'),
            'Authorization' => 'Bearer '.$this->authenticateAccessToken()
        ])
            ->withBody(
                "fields name, cover.url, first_release_date, total_rating_count, platforms.abbreviation, rating, rating_count, summary, slug;
                    where platforms = (48,49,130,6)
                    & (first_release_date >= {$before}
                    & first_release_date < {$current}
                    & rating_count > 5);
                    sort total_rating_count desc;
                    limit 3;
                ", "text/plain"
            )->post(config('services.igdb.endpoint'))
            ->json();
    }

    public function popularGames()
    {
        $before = Carbon::now()->subMonths(2)->timestamp;
        $after = Carbon::now()->addMonths(2)->timestamp;

        return cache()->remember('popular-games', 7, function () use ($before, $after) {
            // sleep(3);
            return Http::withHeaders([
                'Client-ID' => env('IGDB_CLIENT_ID'),
                'Authorization' => 'Bearer '.$this->authenticateAccessToken()
            ])
                ->withBody(
                    "fields name, cover.url, first_release_date, total_rating_count, platforms.abbreviation, rating, slug;
                    where platforms = (48,49,130,6)
                    & (first_release_date >= {$before}
                    & first_release_date < {$after}
                    & total_rating_count > 5);
                    sort total_rating_count desc;
                    limit 12;", "text/plain"
                )->post(config('services.igdb.endpoint'))
                ->json();
        });
    }

    public function searchDropdown($search)
    {
        return Http::withHeaders([
            'Client-ID' => env('IGDB_CLIENT_ID'),
            'Authorization' => 'Bearer '.$this->authenticateAccessToken()
        ])
            ->withBody(
                "search \"{$search}\";
                        fields name, slug, cover.url;
                        limit 8;
                    ", "text/plain"
            )->post(config('services.igdb.endpoint'))
            ->json();
    }

    public function viewDetails($slug)
    {
        return Http::withHeaders([
            'Client-ID' => env('IGDB_CLIENT_ID'),
            'Authorization' => 'Bearer '.$this->authenticateAccessToken()
        ])
            ->withBody(
                "fields name, cover.url, first_release_date, platforms.abbreviation, rating,
                    slug, involved_companies.company.name, genres.name, aggregated_rating, summary, websites.*, videos.*, screenshots.*, similar_games.cover.url, similar_games.name, similar_games.rating,similar_games.platforms.abbreviation, similar_games.slug;
                    where slug=\"{$slug}\";
                ", "text/plain"
            )->post(config('services.igdb.endpoint'))
            ->json();
    }
}
