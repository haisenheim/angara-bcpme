<?php

namespace App\Notifications\Structuration;

use App\Models\Structuration\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private Request $req)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->from('alliages.technologies@gamail.com', 'Nouvel appel de fonds')
                    ->subject('Nouvel appel de fonds.')
                    ->greeting(sprintf('Hello %s!', $notifiable->name))
                    ->line('Bonjour cher gestionnaire une cooperative vient d\'emettre un nouvel appel de fonds.')
                   // ->action('Consulter', url(route('gestionnaire.request.get',[tenant('token'),$this->req->token])))
                   ->action('Consulter', url('/gestionnaire/requests/'.tenant('token').'/'.$this->req->token))
                    ->line('Thank you for using Angara by Angara Finance!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
            'title' => 'Appel de fond',
            'montant' => $this->req->montant,
        ];
    }
}
