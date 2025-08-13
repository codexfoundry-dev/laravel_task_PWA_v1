<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use App\Models\Task;

class TaskReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Task $task, public string $channel)
    {
        $this->afterCommit();
    }

    public function via($notifiable): array
    {
        return ['database', 'broadcast', 'mail', 'webPush'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Task Reminder: '.$this->task->title)
            ->line('You have an upcoming task: '.$this->task->title)
            ->line('Due: '.optional($this->task->due_at)->timezone($notifiable->timezone)->toDayDateTimeString())
            ->action('Open Task', url('/'));
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'type' => 'task.reminder',
            'task_id' => $this->task->id,
            'title' => $this->task->title,
            'due_at' => optional($this->task->due_at)->toISOString(),
        ]);
    }

    public function toArray($notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'title' => $this->task->title,
        ];
    }

    public function toWebPush($notifiable, $notification = null, $channel = null): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Task Reminder')
            ->icon('/icons/icon-192.png')
            ->body($this->task->title)
            ->tag('task-'.$this->task->id)
            ->data(['url' => url('/')]);
    }
}