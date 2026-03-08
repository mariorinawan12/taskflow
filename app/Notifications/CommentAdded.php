<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\TaskComment;

class CommentAdded extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public TaskComment $comment,
        public string $commentBy,
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'task_id' => $this->comment->task_id,
            'task_title' => $this->comment->task->title,
            'project_id' => $this->comment->task->project_id,
            'comment_by' => $this->commentBy,
            'comment_body' => substr($this->comment->body, 0, 100),
            'workspace_id' => $this->comment->workspace_id,
        ];
    }
}
