<?php

namespace App\Listeners;

use App\Events\ProcessQuestion;
use Illuminate\Contracts\Queue\ShouldQueue;

class ChangeQuestionStatus implements ShouldQueue
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
