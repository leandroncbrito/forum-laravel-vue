<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Inspections\Spam;

class SpamTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_checks_for_invalid_keywords()
    {
        // invalid keywords
        $spam = new Spam();

        $this->assertFalse($spam->detect('Innocent reply here'));

        $this->expectException('Exception');

        $spam->detect('Yahoo Customer Support');
    }


    /**
     * @test
     */
    public function it_checks_for_any_key_being_held_down()
    {
        // invalid keywords
        $spam = new Spam();
        
        $this->expectException('Exception');

        $this->assertFalse($spam->detect('Hello world aaaaaaa'));
    }
}
