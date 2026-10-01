<?php

namespace App\Observers;

use App\Models\Sala;

class SalaObserver
{
    /**
     * Handle the Sala "created" event.
     */
    public function created(Sala $sala): void
    {
        //
    }

    /**
     * Handle the Sala "updated" event.
     */
    public function updated(Sala $sala): void
    {
        //
    }

    /**
     * Handle the Sala "deleted" event.
     */
    public function deleted(Sala $sala): void
    {
        //
    }

    /**
     * Handle the Sala "restored" event.
     */
    public function restored(Sala $sala): void
    {
        //
    }

    /**
     * Handle the Sala "force deleted" event.
     */
    public function forceDeleted(Sala $sala): void
    {
        //
    }
}
