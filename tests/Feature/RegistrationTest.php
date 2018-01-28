<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Auth\Events\Registered;
use App\Mail\PleaseConfirmYourEmail;
use Illuminate\Support\Facades\Mail;
use App\User;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_confirmation_email_is_sent_upon_registration()
    {
        Mail::fake();

        event(new Registered(create('App\User')));

        Mail::assertSent(PleaseConfirmYourEmail::class);
    }


    /**
     * @test
     */
    public function user_can_fully_confirm_their_email_addresses()
    {
        $this->post('/register', [
            'name' => 'Leandro',
            'email' => 'leandroncbrito-53c3b4@inbox.mailtrap.io',
            'password' => 'foobar',
            'password_confirmation' => 'foobar'
        ]);

        $user = User::whereName('Leandro')->first();
        
        $this->assertFalse($user->confirmed);

        $this->assertNotNull($user->confirmation_token);

        $response = $this->get('/register/confirm?token=' . $user->confirmation_token);

        $this->assertTrue($user->fresh()->confirmed);

        $response->assertRedirect('/threads');
    }
}
