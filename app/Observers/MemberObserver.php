<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Member;

class MemberObserver
{
    /**
     * Handle the User "created" event.
     */
    // public function created(User $user): void
    // {
    //      Member::create([
    //         'user_id'     => $user->id,
    //         'member_code' => Member::generateNextMemberCode(),
    //         'name'        => $user->name,
    //         'is_active'   => 1,
    //     ]);
    // }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        Member::where('user_id', $user->id)->delete();
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }

}
