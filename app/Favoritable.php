<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

trait Favoritable
{
    protected static function bootFavoritable()
    {
        static::deleting(function ($model) {
            $model->favorites->each->delete();
        });
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorite');
    }

    public function favorite()
    {
        $attributes = ['user_id' => auth()->id()];
        if (!$this->favorites()->where($attributes)->exists()) {
            $this->favorites()->create($attributes);
        }
    }

    public function unfavorite()
    {
        $attributes = ['user_id' => auth()->id()];
        $this->favorites()->where($attributes)->get()
                    ->each(function ($favorite) {
                        $favorite->delete();
                    });
    }

    public function isFavorited()
    {
        return !! $this->favorites->where('user_id', auth()->id())->count();
    }

    // Retorna o atributo IsFavorited - convenção get_Attribute
    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    // Retorna o atributo IsFavorited - convenção get_Attribute
    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }
}
