<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Article;
use Response;
use Purifier;

class ArticlesController extends Controller
{
    //List of Articles
    public function index()
    {
      $articles = Article::all();

      return Response::json($articles);
    }
    //Stores our Articles
    public function store(Request $request)
    {
      $rules = [
        'title' => 'required',
        'body' => 'required',
        'image' => 'required',
      ];

      $validator = Validator::make(Purifier::clean($request->all()), $rules);

      if($validator->fails())
      {
        return Response::json(["error" => "You need to fill out all fields."]);
      }

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
    //Update our Articles.
    public function update($id, Request $request)
    {
      //Finds a specific Article based on ID.
      $article = Article::find($id);

      //Saves the Title
      $article->title = $request->input('title');
      //Saves the Body
      $article->body = $request->input('body');

      //Moves the Image to the server and saves the Image URL to the DB.
      $image = $request->file('image');
      $imageName = $image->getClientOriginalName();
      $image->move("storage/", $imageName);
      $article->image = $request->root(). "/storage/".$imageName;

      //Commits the Saves to the DataBase.
      $article->save();

      //Sends a message back to the Front-End.
      return Response::json(["success" => "Article Updated"]);
    }
    //Show single Article
    public function show($id)
    {
      $article = Article::find($id);

      return Response::json($article);
    }
    //Delete a single Articles
    public function destroy($id)
    {
      $article = Article::find($id);

      $article->delete();

      return Response::json(['success' => 'Deleted Article.']);
    }

}
