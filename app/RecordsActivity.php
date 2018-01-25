<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

trait RecordsActivity
{
    // Laravel aceita a convenção bootNomeDaTrait para bootar funções na trait
    protected static function bootRecordsActivity()
    {
        if (auth()->guest()) {
            return;
        }

        foreach (static::getActivitiesToRecord() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }

        static::deleting(function ($model) {
            $model->activity()->delete();
        });
    }

    public static function getActivitiesToRecord()
    {
        return ['created'];
    }

    private function recordActivity($event)
    {
        // Maneira mais simples sem polimorfismo
        // Activity::create([
        //     'user_id' => auth()->id(),
        //     'type' => $this->getActivityType($event),
        //     'subject_id' => $this->id,
        //     'subject_type' => get_class($this)
        // ]);

        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event)
        ]);
    }

    private function activity()
    {
        return $this->morphMany('App\Activity', 'subject');
    }

    private function getActivityType($event)
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());
        return "{$event}_{$type}";
    }
}
