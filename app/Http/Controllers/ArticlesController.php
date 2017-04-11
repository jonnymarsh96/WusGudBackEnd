<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use Response;

class ArticlesController extends Controller
{
    //List of Articles
    public function index()
    {

    }
    //Stores our Articles
    public function store(Request $request)
    {
      $article = new Article;
      $article->title = $request->input('title');
      $article->body = $request->input('body');

      $image = $request->file('image');
      $imageName = $image->getClientOriginalName();
      $image->move("storage/", $imageName);
      $article->image = $request->root()."/storage/".$imageName;

      $article->save();

      return Response::json(['success' => 'Congrats! You did it.']);
    }
    //Update our Articles
    public function update($id, Request $request)
    {

    }
    //Show single Article
    public function show($id)
    {

    }
    //Delete a single Articles
    public function destroy($id)
    {

    }

}
