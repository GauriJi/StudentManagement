<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Assignment;

class AssignmentUploaded extends Notification
{
    use Queueable;

    public $assignment;

    public function __construct(Assignment $assignment)
    {
        $this->assignment = $assignment;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $teacherName = $this->assignment->teacher ? $this->assignment->teacher->name : 'A teacher';
        return [
            'assignment_id' => $this->assignment->id,
            'title' => $this->assignment->title,
            'message' => $teacherName . ' has uploaded a new assignment: ' . $this->assignment->title,
            'my_class_id' => $this->assignment->my_class_id,
        ];
    }
}
