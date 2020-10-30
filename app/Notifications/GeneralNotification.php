<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class GeneralNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $title;
    protected $content;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($_title, $_content)
    {
        $this->title = $_title;
        $this->content = $_content;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
        ];
    }


    /**
     * Mutators concept - https://blog.especializati.com.br/mutators-no-laravel/
     * 
     * -> Toda vez que o atributo updated_at é inserido/atualizado, sobescreve o valor esperado por uma nova instância do Carbon.
     */
    public function setUpdatedAtAttribute($value) {
        $this->attributes['updated_at'] = Carbon::now();
    }
}
