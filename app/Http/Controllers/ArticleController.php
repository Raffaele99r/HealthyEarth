<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\User;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ArticleRequest;

class ArticleController extends Controller
{

    public function __construct() {
        $this->middleware('auth')->except('index', 'show');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleRequest $request)
    {
        $request->validate([
            'title' => 'required|unique:articles|min:5|max:20',
            'subtitle' => 'required|unique:articles|min:5|max:40',
            'body' => 'required|min:10|max:1000',
            'image' => 'image|required',
            'category' => 'required',
            'tags' => 'required',
        ]);

        $article = Article::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'body' => $request->body,
            'image' => $request->file('image')->store('public/images'),
            'category_id' => $request->category,
            'user_id' => Auth::user()->id,
        ]);

        $tags = explode(', ',$request->tags);
        
        foreach($tags as $tag){
            $newTag = Tag::updateOrCreate([
                'name'=> $tag,
            ]);
            $article->tags()->attach($newTag);
            $article->save();
        }

        return redirect(route('homepage'))->with('message', 'Articolo creato correttamente');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::where('is_accepted', true)->orderBy('created_at', 'desc')->get();
        return view('article.index', compact('articles'));
    }

    public function byCategory(Category $category){
        $articles = $category->articles->sortByDesc('created_at')->filter(function($article){
            return $article->is_accepted == true;
        });
        return view('article.byCategory', compact('category', 'articles'));
    }

    public function byUser(User $user){
        $articles = $user->articles->sortByDesc('created_at')->filter(function($article){
            return $article->is_accepted == true;
        });
        return view('article.byUser', compact('user', 'articles'));
    }

   

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('article.create');
    }



    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        return view('article.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        //
    }

    public function articleSearch(Request $request){
        $query = $request->input('query');
        $articles = Article::search($query)->where('is_accepted', true)->orderBy('created_at', 'desc')->get();

        return view('article.search-index', compact('articles', 'query'));
    }
    
    

    public function deleteTag(Tag $tag){
        foreach($tag->articles as $article){
            $article->tags()->detach($tag);
        }

        $tag->delete();

        return redirect(route('admin.dashboard'))->with('message', 'Hai correttamente eliminato il tag');
    }

  

    

    

}
