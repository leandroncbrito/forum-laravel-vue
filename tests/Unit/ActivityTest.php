<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Activity;
use Carbon\Carbon;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_records_activity_when_a_thread_is_created()
    {
        $this->signIn();

        $thread = create('App\Thread');

        $this->assertDatabaseHas('activities', [
            'type' => 'created_thread',
            'user_id' => auth()->id(),
            'subject_id' => $thread->id,
            'subject_type' => 'App\Thread'
        ]);

        $activity = Activity::first();

        $this->assertEquals($activity->subject->id, $thread->id);
    }

    /**
     * @test
     */
    public function it_records_activity_when_a_reply_is_created()
    {
        $this->signIn();

        $reply = create('App\Reply');

        $this->assertEquals(2, Activity::count());

        // $this->assertDatabaseHas('activities', [
        //     'type' => 'created_thread',
        //     'user_id' => auth()->id(),
        //     'subject_id' => $thread->id,
        //     'subject_type' => 'App\Thread'
        // ]);

        // $activity = Activity::first();

        // $this->assertEquals($activity->subject->id, $thread->id);
    }

    /**
     * @test
     */
    public function it_fetches_a_feed_for_any_user()
    {
        $this->signIn();

        // given we have two threads
        create('App\Thread', ['user_id' => auth()->id()], 2);

        // the first one was published one week ago e the seconde one now
        auth()->user()->activity()->first()->update(['created_at' => Carbon::now()->subWeek()]);

        // when we fetch their feed.
        $feed = Activity::feed(auth()->user());

        // then, it should be returned in the proper format.
        // deve retornar uma key com a data de hoje
        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->format('Y-m-d')
        ));

        // deve retornar uma key com a data de uma semana atras
        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->subWeek()->format('Y-m-d')
        ));
    }
}
