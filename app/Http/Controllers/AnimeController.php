<?php

namespace App\Http\Controllers;

use App\Models\AnimeList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AnimeController extends Controller
{
    public function fetchAndStoreAnime()
    {
        $baseUrl = 'https://api.jikan.moe/v4/top/anime';
        $totalFetched = 0;
        AnimeList::truncate();
        for ($page = 1; $page <= 4; $page++) {
            $response = Http::get($baseUrl, ['limit' => 25, 'page' => $page]);
            try {
                if ($response->successful()) {
                    $animes = $response->json()['data'];

                    foreach ($animes as $anime) {
                        $slug = Str::slug(str_replace('°', '-degree', $anime['title']), '-');
                        $originalSlug = $slug;
                        if (AnimeList::where('slug', $slug)->exists()) {
                            $slug = $originalSlug . '-' . $anime['mal_id'];
                        }
                        AnimeList::updateOrCreate(
                            ['mal_id' => $anime['mal_id']],
                            [
                                'slug' => $slug,
                                'title' => $anime['title'] ?? null,
                                'title_english' => $anime['title_english'] ?? null,
                                'title_japanese' => $anime['title_japanese'] ?? null,
                                'synopsis' => $anime['synopsis'] ?? '',
                                'source' => $anime['source'] ?? null,
                                'trailer' => $anime['trailer']['url'] ?? null,
                                'airing' => $anime['airing'] ?? null,
                                'aired_from' => isset($anime['aired']['from']) ? Carbon::parse($anime['aired']['from'])->toDateTimeString() : null,
                                'aired_to' => isset($anime['aired']['to']) ? Carbon::parse($anime['aired']['to'])->toDateTimeString() : null,
                                'episodes' => $anime['episodes'] ?? null,
                                'duration' => $anime['duration'] ?? null,
                                'status' => $anime['status'] ?? null,
                                'image_url' => $anime['images']['jpg']['image_url'] ?? null,
                                'rating' => $anime['rating'] ?? null,
                                'score' => $anime['score'] ?? null,
                                'rank' => $anime['rank'] ?? null,
                                'genres' => collect($anime['genres'])->pluck(value: 'name')->implode(', '),
                                'studio' => $anime['studios'][0]['name'] ?? null,
                            ]
                        );
                    }

                    $totalFetched += count($animes);
                } else {
                    return response()->json(['error' => 'Failed to fetch data from Jikan API'], 500);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while fetching anime data',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }

        return response()->json(['message' => "$totalFetched anime fetched and stored successfully"], 200);
    }
    public function getAnime($slug)
    {
        try {
            $anime = AnimeList::where('slug', $slug)->first();

            if (!$anime) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anime not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $anime,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the anime',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
