<?php

namespace App\Listeners;

use App\Events\AuditLogEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProcessAuditLogListener implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AuditLogEvent $event): void
    {
        $activity = activity($event->logName);

        if ($event->causer) {
            $activity->causedBy($event->causer);
        }
        if ($event->model) {
            $activity->performedOn($event->model);
        }

        $activity->withProperties($event->properties)
            ->log($event->description);
    }
}
