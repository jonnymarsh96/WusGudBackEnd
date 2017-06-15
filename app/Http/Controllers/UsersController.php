<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use Response;
use Purifier;
use Hash;
use JWTAuth;
use File;

class UsersController extends Controller
{
  public function signUp(Request $request)
  {
    $rules = [
      'name' => 'required',
      'email' => 'required',
      'password' => 'required',
    ];

    $validator = Validator::make(Purifier::clean($request->all()), $rules);

    if($validator->fails())
    {
      return Response::json(['error' => 'You need to fill out all fields.']);
    }
    $check = User::where('email', '=', $request->input('email'))->first();
    if(!empty($check))
    {
      return Response::json(['error' => 'Your email has already been used.']);
    }
    $user = new User;
    $user->name = $request->input('name');
    $user->email = $request->input('email');
    $user->password = Hash::make($request->input('password'));

    $user->save();

    return Response::json(['success' => 'Thanks for joining us ' .$request->input('name')]);
  }



  public function signIn(Request $request)
  {
    $rules = [
      'email' => 'required'
      'password' => 'required'
    ];
    $validator = Validator::make(Purifier::clean($request->all()), $rules)

    if($validator->fails())
    {
      return Response::json(['error' => "Sorry you've mispelled you email/password."]);
    }
    $email=$request->input('email');
    $password=$request->input('password');
    $cred=compact('email', 'password', ['email', 'password']);

    $token=JWTAuth::attempt($cred);
    return Response::json(compact('token'));


  }
  public function getIndex()
  {
    return File::get('index.html');
  }
}
