<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MovieApiController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'min:2', 'max:100'],
            'year' => ['nullable', 'integer', 'min:1888', 'max:2100'],
            'page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $response = $this->omdbRequest([
            's' => $validated['title'],
            'type' => 'movie',
            'page' => $validated['page'] ?? 1,
            'y' => $validated['year'] ?? null,
        ]);

        if ($response instanceof JsonResponse) {
            return $response;
        }

        if (($response['Response'] ?? 'False') === 'False') {
            return response()->json([
                'message' => $response['Error'] ?? 'No movies found.',
                'data' => [],
            ], 404);
        }

        return response()->json([
            'data' => collect($response['Search'] ?? [])->map(fn (array $movie) => [
                'imdb_id' => $movie['imdbID'] ?? null,
                'title' => $movie['Title'] ?? null,
                'year' => $movie['Year'] ?? null,
                'type' => $movie['Type'] ?? null,
                'poster' => ($movie['Poster'] ?? 'N/A') === 'N/A' ? null : $movie['Poster'],
            ])->values(),
            'total_results' => (int) ($response['totalResults'] ?? 0),
            'page' => (int) ($validated['page'] ?? 1),
        ]);
    }

    public function show(string $imdbId): JsonResponse
    {
        $response = $this->omdbRequest([
            'i' => $imdbId,
            'plot' => 'short',
        ]);

        if ($response instanceof JsonResponse) {
            return $response;
        }

        if (($response['Response'] ?? 'False') === 'False') {
            return response()->json([
                'message' => $response['Error'] ?? 'Movie not found.',
            ], 404);
        }

        return response()->json([
            'data' => [
                'imdb_id' => $response['imdbID'] ?? null,
                'title' => $response['Title'] ?? null,
                'year' => $response['Year'] ?? null,
                'rated' => $response['Rated'] ?? null,
                'runtime' => $response['Runtime'] ?? null,
                'genre' => $response['Genre'] ?? null,
                'director' => $response['Director'] ?? null,
                'actors' => $response['Actors'] ?? null,
                'plot' => $response['Plot'] ?? null,
                'poster' => ($response['Poster'] ?? 'N/A') === 'N/A' ? null : $response['Poster'],
                'imdb_rating' => $response['imdbRating'] ?? null,
            ],
        ]);
    }

    private function omdbRequest(array $query): array|JsonResponse
    {
        $apiKey = config('services.omdb.key');

        if (! $apiKey) {
            return response()->json([
                'message' => 'OMDb API key is missing. Add OMDB_API_KEY to your .env file.',
            ], 500);
        }

        try {
            return Http::timeout(8)
                ->acceptJson()
                ->get(config('services.omdb.url'), array_filter([
                    'apikey' => $apiKey,
                    ...$query,
                ], fn ($value) => filled($value)))
                ->throw()
                ->json();
        } catch (ConnectionException) {
            return response()->json([
                'message' => 'Could not connect to OMDb. Please try again.',
            ], 503);
        } catch (RequestException) {
            return response()->json([
                'message' => 'OMDb returned an error. Please try again.',
            ], 502);
        }
    }
}
