<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\LoginRequest;

use App\Models\User;

class UserController extends Controller
{
  public function index()
  {
    //
  }

  public function store(Request $request)
  {
    //
  }

  public function show($id)
  {
    //
  }

  public function update(Request $request, $id)
  {
    //
  }

  public function destroy($id)
  {
    //
  }

  public function login(LoginRequest $request)
  {
    $credentials = $request->only("username", "password");

    if (Auth::attempt($credentials)) {
      $user = User::find(Auth::id());
      $user->token = $user->createToken("bionic")->plainTextToken;

      return response()->login($user);
    }

    return response(
      [
        "status" => 401,
        "message" => "Invalid username or password. Please Try again.",
      ],
      401
    );
  }

  public function logout()
  {
    $data = Auth::user()
      ->currentAccessToken()
      ->delete();

    if ($data) {
      return response()->logout();
    }
  }
}
