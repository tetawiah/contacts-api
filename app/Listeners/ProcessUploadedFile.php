<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Mail;

class ProcessUploadedFile
{
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
    public function handle(object $event): void
    {
        Mail::raw($event->file->getClientOriginalName() . " has been successfully uploaded", function ($message) use ($event) {
            $message->to(auth()->user()->email)->subject('New File Uploaded');

        });
    }
}
