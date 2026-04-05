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
        $activity = activity($event->log_name);

        if ($event->caused_by) {
            $activity->causedBy($event->caused_by);
        }
        if ($event->performedOn) {
            $activity->performedOn($event->performedOn);
        }

        $activity->withProperties($event->properties)
            ->log($event->description);
    }
}
