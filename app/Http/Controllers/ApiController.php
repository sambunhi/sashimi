<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

    public function getArticles(Request $request) {

    }
}
