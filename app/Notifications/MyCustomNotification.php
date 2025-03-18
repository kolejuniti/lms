<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MyCustomNotification extends Notification
{
    use Queueable;

    private $message;
    private $url;
    private $icon;
    private $iconColor; // New property for the icon color

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message, $url, $icon, $iconColor = '#4f81c7')
    {
        $this->message = $message;

        $this->url = $url;

        $this->icon = $icon;

        $this->iconColor = $iconColor; // New property for the icon color
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

    // This is the important part for database notifications
    public function toDatabase($notifiable)
    {
        return [
            'url'     => url($this->url),
            'message' => $this->message,
            'icon'    => $this->icon, // optional, if you're using an icon example 'fa bell'
            'icon_color' => $this->iconColor // New property for the icon color
        ];
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
            //
        ];
    }
}
