<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\ThreadWasUpdated;
use App\Events\ThreadReceivedNewReply;
use Illuminate\Support\Facades\Redis;
use Stevebauman\Purify\Purify;

//use Laravel\Scout\Searchable;

class Thread extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    // Include
    protected $with = ['creator', 'channel'];
        
    // Anexa as propriedades no retorno
    protected $appends = ['isSubscribedTo'];

    protected $casts = [
        'locked' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        // Essa coluna foi incluída na tabela
        // static::addGlobalScope('replyCount', function ($builder) {
        //     $builder->withCount('replies');
        // });

        static::deleting(function ($thread) {
            $thread->replies->each->delete();
        });
    }

    public function getRouteKeyName(Type $var = null)
    {
        return 'slug';
    }

    // public function toSearchableArray()
    // {
    //     return array_only($this->toArray(), ['id', 'title']);
    // }
    
    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->slug}";
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        event(new ThreadReceivedNewReply($reply));

        // Enviando notificação de maneira mais simples
        //$this->notifySubscribers($reply);

        return $reply;
    }

    private function notifySubscribers($reply)
    {
        $this->subscriptions
            ->where('user_id', '!=', $reply->user_id)
            ->each
            ->notify($reply);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);

        return $this;
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }


    public function hasUpdatesFor($user)
    {
        $key = $user->visitedThreadCacheKey($this);

        return $this->updated_at > cache($key);
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function setSlugAttribute($value)
    {
        $slug = str_slug($value);
        $original = $slug;
        $count =2;

        while (static::whereSlug($slug)->exists()) {
            $slug = "{$original}-" . $count++;
        }

        $this->attributes['slug'] = $slug;
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
                    ->where('user_id', auth()->id())
                    ->exists();
    }

    // Acessor
    public function getBodyAttribute($body)
    {
        return \Purify::clean($body);
    }

    public function markBestReply(Reply $reply)
    {
        $reply->thread->update(['best_reply_id' => $reply->id]);
    }

    // Usado para implentação com a Classe Visits
    // public function visits()
    // {
    //     return new Visits($this);
    // }


    // Método desnecessário, pode ser feito tudo no setSlugAttribute
    // private function incrementSlug($slug, $count = 2)
    // {
    //     $original = $slug;

    //     while (static::whereSlug($slug)->exists()) {
    //         $slug = "{$original}-" . $count++;
    //     }

    //     return $slug;

    //     // $max = static::whereTitle($this->title)->latest('id')->value('slug'); //teste ou teste-1

    //     // if (is_numeric($max[-1])) {
    //     //     return preg_replace_callback('/(\d+)$/', function ($matches) {
    //     //         return $matches[1] + 1;
    //     //     }, $max);
    //     // }

    //     // return "{$slug}-2";
    // }
}
