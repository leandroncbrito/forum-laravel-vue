<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;
use App\Reply;
use App\Inspections\Spam;
use Mockery\CountValidator\Exception;

class ReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    private function validateReply()
    {
        $this->validate(request(), [
            'body' => 'required'
        ]);

        // Chamando a classe através do container
        // Outras opções são: injetar no construtor, injetar no método
        resolve(Spam::class)->detect(request('body'));
    }

    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(10);
    }

    public function store($channelId, Thread $thread)
    {
        try {
            $this->validateReply();

            $reply = $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id()
            ]);
    
            return $reply->load('owner');
        } catch (\Exception $e) {
            return response('Sorry, your reply could not be saved at this time.', 422);
        }
    }

    public function update(Reply $reply)
    {
        try {
            $this->authorize('update', $reply);

            $this->validateReply();

            $reply->update(['body' => request('body')]);
        } catch (\Exception $e) {
            return response('Sorry, your reply could not be saved at this time.', 422);
        }
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
