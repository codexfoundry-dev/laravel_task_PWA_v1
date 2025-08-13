<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class DailyDigestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public User $user) { $this->afterCommit(); }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $today = now($notifiable->timezone)->toDateString();
        $tasks = $notifiable->load(['projects.tasks' => function ($q) use ($today) {
            $q->whereDate('due_at', $today)->whereNull('completed_at');
        }])->projects->flatMap->tasks;

        $mail = (new MailMessage)
            ->subject('Your Tasks for Today')
            ->greeting('Good morning!')
            ->line('Here are your tasks for today:');
        foreach ($tasks as $task) {
            $mail->line("- [{$task->project->name}] {$task->title} (".optional($task->due_at)->timezone($notifiable->timezone)->format('H:i').")");
        }
        return $mail->action('Open GlassTasks', url('/'));
    }
}