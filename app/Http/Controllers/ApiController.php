<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\{Article, Source, Keyword};

class ApiController extends Controller
{
    public function saveCrawlerLinks(Request $request) {

    }

    public function getLinksNeedHandle(Request $request) {
        return Article::whereNull('nltk_at')->pluck('url');
    }

    public function saveArticleKeywords(Request $request) {

    }

    public function getSystemInfo(Request $request) {
        $crawlerInfo = [
            'sources' => Source::select(['id', 'name', 'url'])->get(),
            'keywords' => Keyword::pluck('name')
        ];

        return $crawlerInfo;
    }

    public function getTrends(Request $request) {
        $date_begin = $request->get('date_range')[0];
        $date_end = $request->get('date_range')[1];
        $keywords = $request->get('keywords');
        $sources = $request->get('sources');

        $articles = DB::table('articles')->leftJoin('trends', 'articles.id', '=', 'trends.article_id')->whereNotNull('nltk_at')->whereBetween('published_at', [$date_begin, $date_end])->whereNotNull('keyword');

        if( count($keywords) > 0 ) {
            $articles->whereIn('keyword', $keywords);
        }

        if( count($sources) > 0 ) {
            $articles->whereIn('source_id', $sources);
        }

        $trends = [
            'trends' => $articles->select(DB::raw('published_at as date, keyword, count(*) as cnt'))->groupBy('published_at', 'keyword')->get()
        ];

        return $trends;
    }

    public function getArticles(Request $request) {
        $date = $request->get('date');
        $sources = $request->get('source_id');

        $articles = Article::with('source')->with('trend')->select('id','source_id','title', 'url')->where('published_at', $date)->whereNotNull('nltk_at');

        if( count($sources) > 0 ) {
            $articles->whereIn('source_id', $sources);
        }

        return $articles->get();

    }
}
