<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsCreateRequest;
use App\Http\Requests\NewsUpdateRequest;
use App\Models\News;
use App\Models\Restaurant;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class NewsController extends Controller
{
    public function index()
    {
        return Inertia::render('News/Index', [
            'news_articles' => News::orderBy('created_at', 'desc')->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('News/Create');
    }

    public function store(NewsCreateRequest $request)
    {
        $news = new News();
        $news->title = $request->input('title');
        $news->content = $request->input('content');
        $news->restaurant_id = Restaurant::first()->id;
        $news->save();

        return redirect::Route('news.index');
    }

    public function edit(string $id)
    {
        return Inertia::render('News/Edit', [
            'news_article' => News::find($id)
        ]);
    }

    public function update(NewsUpdateRequest $request, News $news)
    {
        $news->update([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
        ]);

        return redirect::Route('news.index');
    }

    public function destroy(News $news)
    {
        $news->delete();
        return redirect::Route('news.index');
    }
}
