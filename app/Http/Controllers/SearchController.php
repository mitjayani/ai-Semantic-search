<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Http;

class SearchController extends Controller
{
    public function index()
    {
        return view('search');
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        if (empty($query)) {
            return redirect('/search');
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('JINA_API_KEY'),
            'Content-Type'  => 'application/json',
        ])->post('https://api.jina.ai/v1/embeddings', [
            'input' => $query,
            'model' => 'jina-embeddings-v3'
        ]);

        $queryEmbedding = $response->json('data.0.embedding');

        $categories = Category::all();
        $results = [];

        foreach ($categories as $category) {
            if ($category->embedding) {
                $similarity = $this->cosineSimilarity($queryEmbedding, $category->embedding);
                if ($similarity >= 0.4) {
                    $results[] = ['category' => $category, 'score' => $similarity];
                }
            }
        }

        usort($results, fn($a, $b) => $b['score'] <=> $a['score']);

        $top = array_slice($results, 0, 5);

        return view('search', [
            'query' => $query,
            'results' => $top,
            'noResults' => count($top) === 0
        ]);
    }


    protected function cosineSimilarity($vec1, $vec2)
    {
        $dot = $normA = $normB = 0;

        for ($i = 0; $i < count($vec1); $i++) {
            $dot += $vec1[$i] * $vec2[$i];
            $normA += $vec1[$i] ** 2;
            $normB += $vec2[$i] ** 2;
        }

        return $normA && $normB ? $dot / (sqrt($normA) * sqrt($normB)) : 0;
    }
}
