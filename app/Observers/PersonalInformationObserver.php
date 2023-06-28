<?php

namespace App\Observers;

use App\Models\PersonalInformation;

class PersonalInformationObserver
{
    /**
     * Handle the PersonalDetail "created" event.
     */
    public function created(PersonalInformation $personalInformation): void
    {
        //
    }

    /**
     * Handle the PersonalDetail "updated" event.
     */
    public function updated(PersonalInformation $personalInformation): void
    {
        //
    }

    /**
     * Handle the PersonalDetail "deleted" event.
     */
    public function deleted(PersonalInformation $personalInformation): void
    {
        //
    }

    /**
     * Handle the PersonalDetail "restored" event.
     */
    public function restored(PersonalInformation $personalInformation): void
    {
        //
    }

    /**
     * Handle the PersonalDetail "force deleted" event.
     */
    public function forceDeleted(PersonalInformation $personalInformation): void
    {
        //
    }
}
