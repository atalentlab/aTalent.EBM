<?php

namespace App\Services;

use App\Exceptions\UserVerificationTokenExpiredException;
use App\Models\User;
use App\Repositories\UserVerificationRepository;
use App\Recipients\AdminRecipient;
use App\Notifications\Auth\VerifyUserNotification;

class UserVerificationService
{
    protected $userVerificationRepo;

    protected $resendAfter = 48; // hours

    public function __construct()
    {
        $this->userVerificationRepo = new UserVerificationRepository();
    }

    public function sendVerificationMail($user, $forceSend = false)
    {
        if ($forceSend === false && ($user->verified || !$this->shouldSend($user))) {
            return;
        }

        $token = $this->userVerificationRepo->createVerification($user);

        $link = route('admin.auth.verify', ['token' => $token]);

        (new AdminRecipient($user->email))->notify(new VerifyUserNotification($user, $link));
    }

    public function getUserToVerify($token)
    {
        $verification = $this->userVerificationRepo->getVerificationByToken($token);

        if ($verification === null) {
            return null;
        }

        $user = User::find($verification->user_id);

        if (strtotime($verification->created_at) + 60 * 60 * $this->resendAfter < time()) {
            $this->sendVerificationMail($user);

            throw new UserVerificationTokenExpiredException('token expired');
        }

        return $user;
    }

    public function deleteVerification($token)
    {
        $this->userVerificationRepo->deleteVerification($token);
    }

    private function shouldSend($user)
    {
        $verification = $this->userVerificationRepo->getVerification($user);
        return $verification === null || strtotime($verification->created_at) + 60 * 60 * $this->resendAfter < time();
    }
}
