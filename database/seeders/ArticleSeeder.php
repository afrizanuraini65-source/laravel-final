<?php

namespace Database\Seeders;

use App\Data\ArticleData;
use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        $authorId = $admin ? $admin->id : null;

        $defaultArticles = ArticleData::getDefaultArticles();

        foreach ($defaultArticles as $slug => $data) {
            Article::firstOrCreate(
                ['slug' => $data['slug']],
                [
                    'author_id' => $authorId,
                    'title' => $data['title'],
                    'excerpt' => $data['summary'] ?? ($data['excerpt'] ?? ''),
                    'content' => $data['content'] ?? '',
                    'cover_image' => $data['image'] ?? 'all_genre.jpeg',
                    'category' => $data['category'] ?? 'Edukasi Remaja',
                    'status' => 'PUBLISHED',
                    'published_at' => now(),
                ]
            );
        }
    }
}
