<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;
use App\Reply;
use Mockery\CountValidator\Exception;
use App\Rules\SpamFree;
use Illuminate\Support\Facades\Gate;

class ReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(10);
    }

    public function store($channelId, Thread $thread)
    {
        //$this->authorize('create', new Reply);
        // Delegate para a ReplyPolicy@create
        if (Gate::denies('create', new Reply)) {
            return response(
                'Your are posting too frequently. Please take a break. :)',
                422
            );
        }

        request()->validate([
                'body' => ['required', new SpamFree]
            ]);
    
        $reply = $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id()
            ]);
        
        return $reply->load('owner');
    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        request()->validate([
            'body' => ['required', new SpamFree]
        ]);
            
        $reply->update(['body' => request('body')]);
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Reply deleted']) ;
        }

        return back();
    }
}
