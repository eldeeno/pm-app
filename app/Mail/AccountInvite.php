<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class AccountInvite extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $temporaryPassword;
    public $resetPasswordUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $temporaryPassword)
    {
        $this->user = $user;
        $this->temporaryPassword = $temporaryPassword;

        $this->resetPasswordUrl = URL::temporarySignedRoute(
            'password.reset',
            now()->addDays(7),
            [
                'token' => app('auth.password.broker')->createToken($user),
                'email' => $user->email,
            ]
        );
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Account Login Details',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.account_invite',
            with: [
                'user' => $this->user,
                'temporaryPassword' => $this->temporaryPassword,
                'resetPasswordUrl' => $this->resetPasswordUrl,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
