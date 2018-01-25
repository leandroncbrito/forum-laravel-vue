<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reply;
use App\Favorite;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Reply $reply)
    {
        $reply->favorite();

        if (request()->wantsJson()) {
            return response(['status' => 'Favorited']) ;
        }

        return back();
    }

    public function destroy(Reply $reply)
    {
        $reply->unfavorite();

        if (request()->wantsJson()) {
            return response(['status' => 'Unfavorited']) ;
        }

        return back();
    }
}
