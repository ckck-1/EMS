<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventCancelledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $event;

    /**
     * Create a new notification instance.
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Event Cancelled: ' . $this->event->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('We regret to inform you that an event you RSVP\'d to has been cancelled.')
            ->line('**Event:** ' . $this->event->title)
            ->line('**Original Date:** ' . $this->event->date->format('F j, Y'))
            ->line('**Original Time:** ' . $this->event->time->format('g:i A'))
            ->line('**Location:** ' . $this->event->location)
            ->line('We apologize for any inconvenience this may cause.')
            ->action('Browse Other Events', route('home'))
            ->line('Thank you for your understanding.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'event_id' => $this->event->id,
            'event_title' => $this->event->title,
            'message' => 'Event "' . $this->event->title . '" has been cancelled.',
        ];
    }
}