<?php

namespace Modules\Email\Observers;

use App\Models\User;
use Modules\Email\Models\Email;

class UserObserver
{
    /**
     * Hvis brugeren opdaterer sin e-mail, skal vi opdatere alle planlagte e-mails.
     */
    public function updated(User $user)
    {
        if ($user->isDirty('email')) {
            Email::where('user_id', $user->id)
                ->where('status', 'scheduled')
                ->update([
                    'to' => $user->email,
                ]);
        }
    }

    /**
     * Hvis brugeren slettes, skal planlagte e-mails annulleres.
     */
    public function deleted(User $user)
    {
        Email::where('user_id', $user->id)
            ->where('status', 'scheduled')
            ->update([
                'status' => 'canceled',
            ]);
    }

    /**
     * Hvis brugeren gendannes, skal alle annullerede e-mails sættes tilbage til 'scheduled'.
     */
    public function restored(User $user)
    {
        Email::where('user_id', $user->id)
            ->where('status', 'canceled')
            ->update([
                'status' => 'scheduled',
            ]);
    }
}
