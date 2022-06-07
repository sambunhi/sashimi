<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Article;
use App\Models\Source;
use App\Models\Keyword;
use App\Models\Trend;

class ApiController extends Controller
{
    public function saveCrawlerLinks(Request $request)
    {
        $request->validate([
            '*.url'=>'required|url',
            '*.title'=>'required',
            '*.source_id'=>'required|exists:sources,id',
            '*.published_at'=>'required|date_format:Y-m-d'
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->json() as $link) {
                Article::updateOrCreate(['url'=>$link['url']], [
                    'title'=>$link['title'],
                    'url'=>$link['url'],
                    'source_id'=>$link['source_id'],
                    'published_at'=>$link['published_at']
                ]);
            }
        });

        return [
            'affect_num' => count($request->json())
        ];
    }

    public function getLinksNeedHandle(Request $request)
    {
        return Article::whereNull('nltk_at')->pluck('url');
    }

    public function saveArticleKeywords(Request $request)
    {
        $article = Article::where('url', $request->get('url'))->first();

        if ($article != null) {
            DB::transaction(function () use ($request, $article) {
                foreach ($request->get('keywords') as $keyword => $cnt) {
                    Trend::updateOrCreate([
                        'article_id' => $article->id,
                        'keyword' => $keyword
                    ], [
                        'article_id' => $article->id,
                        'keyword' => $keyword,
                        'cnt' => $cnt
                    ]);
                }

                $article->nltk_at = Carbon::now();
                $article->save();
            });

            return "success";
        } else {
            return "url not found";
        }
    }

    public function getSystemInfo(Request $request)
    {
        $crawlerInfo = [
            'sources' => Source::select(['id', 'name', 'url'])->get(),
            'keywords' => Keyword::pluck('name')
        ];

        return $crawlerInfo;
    }

    public function getTrends(Request $request)
    {
        $date_begin = $request->get('date_start');
        $date_end = $request->get('date_end');

        $articles = DB::table('articles')->leftJoin('trends', 'articles.id', '=', 'trends.article_id')
            ->whereNotNull('nltk_at')->whereBetween('published_at', [$date_begin, $date_end])
            ->whereNotNull('keyword');

        if ($request->filled('keywords')) {
            $keywords = array_map('trim', explode(',', $request->get('keywords')));
            $articles->whereIn('keyword', $keywords);
        }

        if ($request->filled('sources')) {
            $sources = array_map('trim', explode(',', $request->get('sources')));
            $articles->whereIn('source_id', $sources);
        }

        $trends = [
            'trends' => $articles->select(DB::raw('published_at as date, keyword, count(*) as cnt'))
                ->groupBy('published_at', 'keyword')->get()
        ];

        return $trends;
    }

    public function getArticles(Request $request)
    {
        $date = $request->get('date');

        $articles = Article::with('source')->with('trend')->select('id', 'source_id', 'title', 'url', 'published_at')
            ->where('published_at', $date)->whereNotNull('nltk_at');

        if ($request->filled('source_id')) {
            $sources = array_map('trim', explode(',', $request->get('source_id')));
            $articles->whereIn('source_id', $sources);
        }

        return $articles->get();
    }
}
