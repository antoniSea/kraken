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
    public $customSubject;
    public $customMessage;

    public function __construct($user, $files, $subject = null, $message = null)
    {
        $this->user = $user;
        $this->files = $files;
        $this->customSubject = $subject;
        $this->customMessage = $message;
    }

    public function build()
    {
        $subject = $this->customSubject ?? 'Dziękujemy za wysłanie plików';
        $message = $this->customMessage ?? 'Dziękujemy za przesłanie plików do konkursu.';
        
        return $this->subject($subject)
            ->view('emails.pliki-uploaded')
            ->with([
                'user' => $this->user,
                'files' => $this->files,
                'customMessage' => $message,
            ]);
    }
} 