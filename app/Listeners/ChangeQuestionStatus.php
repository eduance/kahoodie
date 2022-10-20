<?php

namespace App\Listeners;

use App\Events\ProcessQuestion;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ChangeQuestionStatus
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ProcessQuestion $event)
    {
        $event->question->update([
           'status' => $event->status
        ]);
    }
}
