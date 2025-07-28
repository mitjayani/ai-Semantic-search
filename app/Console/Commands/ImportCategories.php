<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Category;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImportCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = storage_path('app/public/categories.xlsx');
        $rows = Excel::toArray([], $path)[0];

        $header = array_map('strtolower', $rows[0]);

        foreach (array_slice($rows, 1) as $row) {
            $data = array_combine($header, $row);

            $category = Category::create([
                'main_category' => $data['main category'] ?? null,
                'sub_category'  => $data['sub category'] ?? null,
                'service'       => $data['service'] ?? null,
                'keywords'      => $data['keywords'] ?? null,
            ]);

            $embedding = $this->generateEmbedding($data['keywords']);

            $category->embedding = $embedding;
            $category->save();


        }

        $this->info('Import completed.');
    }

    protected function generateEmbedding($text)
    {

        if (!$text) return null;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('JINA_API_KEY'),
            'Content-Type'  => 'application/json',
        ])->post('https://api.jina.ai/v1/embeddings', [
            'input' => $text,
            'model' => 'jina-embeddings-v3'
        ]);
        
        return $response->json('data.0.embedding');
    }
}
