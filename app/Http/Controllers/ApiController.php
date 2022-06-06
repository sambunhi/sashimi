<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Article;

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

    }

    public function getArticles(Request $request) {

    }
}
