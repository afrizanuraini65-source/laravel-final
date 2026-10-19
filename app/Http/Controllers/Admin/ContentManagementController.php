<?php

namespace App\Http\Controllers\Admin;

use App\Data\DutaGenreData;
use App\Data\KegiatanData;
use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ContentManagementController extends Controller
{
    /**
     * Ensure only authorized management roles can access.
     */
    protected function authorizeAccess(): void
    {
        if (! Auth::check() || ! Auth::user()->hasAnyRole(['ADMIN', 'KETUA', 'WAKIL_KETUA', 'PEMBINA', 'PDD', 'HUMAS'])) {
            abort(403, 'Akses terbatas untuk Pengurus.');
        }
    }

    // =========================================================================
    // 1. ARTIKEL & EDUKASI MANAGEMENT
    // =========================================================================

    public function articlesIndex(Request $request)
    {
        $this->authorizeAccess();

        $query = Article::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $articles = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        return view('admin.content.articles.index', compact('articles'));
    }

    public function articleCreate()
    {
        $this->authorizeAccess();

        return view('admin.content.articles.form', [
            'article' => new Article,
            'isEdit' => false,
        ]);
    }

    public function articleStore(Request $request)
    {
        $this->authorizeAccess();

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:articles,slug',
            'category' => 'required|string|max:100',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'status' => 'required|in:DRAFT,PUBLISHED',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'cover_image_text' => 'nullable|string|max:255',
        ]);

        $slug = $request->filled('slug')
            ? Str::slug($request->slug)
            : Str::slug($request->title);

        $imageName = 'all_genre.jpeg';
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $imageName = time().'_'.Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)).'.'.$file->getClientOriginalExtension();
            $file->move(public_path('images'), $imageName);
        } elseif ($request->filled('cover_image_text')) {
            $imageName = $request->cover_image_text;
        }

        $article = Article::create([
            'author_id' => Auth::id(),
            'title' => $request->title,
            'slug' => $slug,
            'category' => $request->category,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'cover_image' => $imageName,
            'status' => $request->status,
            'published_at' => $request->status === 'PUBLISHED' ? now() : null,
        ]);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel "'.$article->title.'" berhasil ditambahkan.');
    }

    public function articleEdit($id)
    {
        $this->authorizeAccess();
        $article = Article::findOrFail($id);

        return view('admin.content.articles.form', [
            'article' => $article,
            'isEdit' => true,
        ]);
    }

    public function articleUpdate(Request $request, $id)
    {
        $this->authorizeAccess();
        $article = Article::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:articles,slug,'.$article->id,
            'category' => 'required|string|max:100',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'status' => 'required|in:DRAFT,PUBLISHED',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'cover_image_text' => 'nullable|string|max:255',
        ]);

        $imageName = $article->cover_image;
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $imageName = time().'_'.Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)).'.'.$file->getClientOriginalExtension();
            $file->move(public_path('images'), $imageName);
        } elseif ($request->filled('cover_image_text')) {
            $imageName = $request->cover_image_text;
        }

        $article->update([
            'title' => $request->title,
            'slug' => Str::slug($request->slug),
            'category' => $request->category,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'cover_image' => $imageName,
            'status' => $request->status,
            'published_at' => $request->status === 'PUBLISHED' ? ($article->published_at ?? now()) : null,
        ]);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel "'.$article->title.'" berhasil diperbarui.');
    }

    public function articleToggleStatus($id)
    {
        $this->authorizeAccess();
        $article = Article::findOrFail($id);
        $newStatus = $article->status === 'PUBLISHED' ? 'DRAFT' : 'PUBLISHED';
        $article->update([
            'status' => $newStatus,
            'published_at' => $newStatus === 'PUBLISHED' ? ($article->published_at ?? now()) : null,
        ]);

        return redirect()->back()
            ->with('success', 'Status artikel diubah menjadi: '.$newStatus);
    }

    public function articleDestroy($id)
    {
        $this->authorizeAccess();
        $article = Article::findOrFail($id);
        $title = $article->title;
        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel "'.$title.'" berhasil dihapus.');
    }

    // =========================================================================
    // 2. DUTA GENRE PROFILES MANAGEMENT
    // =========================================================================

    public function dutaIndex()
    {
        $this->authorizeAccess();
        $dutagenres = DutaGenreData::getAll();

        return view('admin.content.duta.index', compact('dutagenres'));
    }

    public function dutaEdit($slug)
    {
        $this->authorizeAccess();
        $duta = DutaGenreData::findBySlug($slug);
        if (! $duta) {
            abort(404, 'Duta GenRe tidak ditemukan.');
        }

        return view('admin.content.duta.edit', compact('duta'));
    }

    public function dutaUpdate(Request $request, $slug)
    {
        $this->authorizeAccess();
        $duta = DutaGenreData::findBySlug($slug);
        if (! $duta) {
            abort(404, 'Duta GenRe tidak ditemukan.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'summary' => 'required|string|max:500',
            'bio' => 'required|string',
            'focus' => 'required|string|max:255',
            'quote' => 'nullable|string|max:500',
            'contributions' => 'nullable|string',
            'achievements' => 'nullable|string',
            'experiences' => 'nullable|string',
            'photo_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $imageName = $duta['image'] ?? 'all_genre.jpeg';
        if ($request->hasFile('photo_file')) {
            $file = $request->file('photo_file');
            $imageName = time().'_'.Str::slug($duta['name']).'.'.$file->getClientOriginalExtension();
            $file->move(public_path('images'), $imageName);
        }

        // Helper to parse line-separated items
        $parseList = function (?string $raw): array {
            if (! $raw) {
                return [];
            }
            $lines = explode("\n", str_replace("\r", '', $raw));

            return array_values(array_filter(array_map('trim', $lines)));
        };

        $contributions = $request->filled('contributions') ? $parseList($request->contributions) : ($duta['contributions'] ?? []);
        $achievements = $request->filled('achievements') ? $parseList($request->achievements) : ($duta['achievements'] ?? []);
        $experiences = $request->filled('experiences') ? $parseList($request->experiences) : ($duta['experiences'] ?? []);

        $updates = [
            'name' => $request->name,
            'title' => $request->role,
            'role' => $request->role,
            'summary' => $request->summary,
            'bio' => $request->bio,
            'focus' => $request->focus,
            'quote' => $request->quote ?? '',
            'image' => $imageName,
            'photo' => '/images/'.$imageName,
            'contributions' => $contributions,
            'achievements' => $achievements,
            'experiences' => $experiences,
        ];

        DutaGenreData::saveProfile($slug, $updates);

        return redirect()->route('admin.duta-genre.index')
            ->with('success', 'Profil Duta GenRe "'.$request->name.'" berhasil diperbarui.');
    }

    // =========================================================================
    // 3. KEGIATAN & DOKUMENTASI MANAGEMENT
    // =========================================================================

    public function kegiatanIndex(Request $request)
    {
        $this->authorizeAccess();
        $allKegiatan = KegiatanData::getAll();

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $allKegiatan = array_filter($allKegiatan, function ($item) use ($search) {
                return str_contains(strtolower($item['title'] ?? ''), $search)
                    || str_contains(strtolower($item['category'] ?? ''), $search)
                    || str_contains(strtolower($item['location'] ?? ''), $search);
            });
        }

        if ($request->filled('category')) {
            $cat = $request->category;
            $allKegiatan = array_filter($allKegiatan, fn ($item) => ($item['category'] ?? '') === $cat);
        }

        return view('admin.content.kegiatan.index', [
            'kegiatan' => $allKegiatan,
        ]);
    }

    public function kegiatanCreate()
    {
        $this->authorizeAccess();

        return view('admin.content.kegiatan.form', [
            'item' => [
                'id' => null,
                'title' => '',
                'category' => 'Pemilihan Duta GenRe',
                'category_label' => 'Kegiatan',
                'date_display' => date('d F Y'),
                'month' => strtoupper(date('M')),
                'day' => date('d'),
                'year' => date('Y'),
                'location' => 'SMK Negeri 1 Purwokerto',
                'image' => 'pendutgen.png',
                'status' => 'TERLAKSANA',
                'status_label' => 'Terlaksana',
                'source' => '',
                'desc' => '',
            ],
            'isEdit' => false,
        ]);
    }

    public function kegiatanStore(Request $request)
    {
        $this->authorizeAccess();

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'category_label' => 'nullable|string|max:100',
            'date_display' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'status' => 'required|in:TERLAKSANA,MENDATANG,AKTIF',
            'desc' => 'required|string',
            'source' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'image_text' => 'nullable|string|max:255',
        ]);

        $imageName = 'pendutgen.png';
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $imageName = time().'_'.Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)).'.'.$file->getClientOriginalExtension();
            $file->move(public_path('images'), $imageName);
        } elseif ($request->filled('image_text')) {
            $imageName = $request->image_text;
        }

        $dateParts = explode(' ', trim($request->date_display));
        $day = $dateParts[0] ?? date('d');
        $month = strtoupper(substr($dateParts[1] ?? date('M'), 0, 3));
        $year = $dateParts[2] ?? date('Y');

        $statusLabels = [
            'TERLAKSANA' => 'Terlaksana',
            'MENDATANG' => 'Agenda Mendatang',
            'AKTIF' => 'Layanan Aktif',
        ];

        $data = [
            'id' => null,
            'title' => $request->title,
            'category' => $request->category,
            'category_label' => $request->category_label ?: $request->category,
            'date_display' => $request->date_display,
            'month' => $month,
            'day' => $day,
            'year' => $year,
            'location' => $request->location,
            'image' => $imageName,
            'status' => $request->status,
            'status_label' => $statusLabels[$request->status] ?? 'Terlaksana',
            'source' => $request->source ?? '',
            'desc' => $request->desc,
        ];

        KegiatanData::save($data);

        return redirect()->route('admin.kegiatan.index')
            ->with('success', 'Kegiatan "'.$request->title.'" berhasil ditambahkan.');
    }

    public function kegiatanEdit($id)
    {
        $this->authorizeAccess();
        $item = KegiatanData::find($id);
        if (! $item) {
            abort(404, 'Kegiatan tidak ditemukan.');
        }

        return view('admin.content.kegiatan.form', [
            'item' => $item,
            'isEdit' => true,
        ]);
    }

    public function kegiatanUpdate(Request $request, $id)
    {
        $this->authorizeAccess();
        $existing = KegiatanData::find($id);
        if (! $existing) {
            abort(404, 'Kegiatan tidak ditemukan.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'category_label' => 'nullable|string|max:100',
            'date_display' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'status' => 'required|in:TERLAKSANA,MENDATANG,AKTIF',
            'desc' => 'required|string',
            'source' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'image_text' => 'nullable|string|max:255',
        ]);

        $imageName = $existing['image'] ?? 'pendutgen.png';
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $imageName = time().'_'.Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)).'.'.$file->getClientOriginalExtension();
            $file->move(public_path('images'), $imageName);
        } elseif ($request->filled('image_text')) {
            $imageName = $request->image_text;
        }

        $dateParts = explode(' ', trim($request->date_display));
        $day = $dateParts[0] ?? ($existing['day'] ?? date('d'));
        $month = strtoupper(substr($dateParts[1] ?? ($existing['month'] ?? date('M')), 0, 3));
        $year = $dateParts[2] ?? ($existing['year'] ?? date('Y'));

        $statusLabels = [
            'TERLAKSANA' => 'Terlaksana',
            'MENDATANG' => 'Agenda Mendatang',
            'AKTIF' => 'Layanan Aktif',
        ];

        $data = [
            'id' => (int) $id,
            'title' => $request->title,
            'category' => $request->category,
            'category_label' => $request->category_label ?: $request->category,
            'date_display' => $request->date_display,
            'month' => $month,
            'day' => $day,
            'year' => $year,
            'location' => $request->location,
            'image' => $imageName,
            'status' => $request->status,
            'status_label' => $statusLabels[$request->status] ?? 'Terlaksana',
            'source' => $request->source ?? '',
            'desc' => $request->desc,
        ];

        KegiatanData::save($data);

        return redirect()->route('admin.kegiatan.index')
            ->with('success', 'Kegiatan "'.$request->title.'" berhasil diperbarui.');
    }

    public function kegiatanDestroy($id)
    {
        $this->authorizeAccess();
        KegiatanData::delete($id);

        return redirect()->route('admin.kegiatan.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }
}
