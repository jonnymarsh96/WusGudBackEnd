<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use Response;
use Purifier;
use Hash;

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

}
