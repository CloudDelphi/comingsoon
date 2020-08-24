<?php

namespace MBonaldo\ComingSoon\Controllers;

use Illuminate\Support\Facades\Facade;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ComingSoonController extends Controller
{
  public function index()
  {
    $subscribeUrl = config('comingsoon.subscribe_url');
    $tokens = config('comingsoon.tokens');
    $isSubscribeNeeded = !is_null($subscribeUrl);
    $hasTokens = count($tokens) > 0;
    return view('comingsoon::coming-soon', compact('subscribeUrl', 'tokens', 'isSubscribeNeeded', 'hasTokens'));
  }

  public function token(Request $request)
  {
    $tokens = config('comingsoon.tokens');
    if ($request->has('token') && in_array($request->get('token'), $tokens)) {
      $request->session()->put('comingsoon-token', $request->get('token'));
      return redirect('/');
    } else {
      return redirect()->route('comingsoon.index');
    }
  }
}