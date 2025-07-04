<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PlikiUploaded extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $files;

    public function __construct($user, $files)
    {
        $this->user = $user;
        $this->files = $files;
    }

    public function build()
    {
        return $this->subject('Dziękujemy za wysłanie plików')
            ->view('emails.pliki-uploaded');
    }
} 