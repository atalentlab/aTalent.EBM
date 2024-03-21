<?php

namespace App\Repositories;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UserVerificationRepository
{
    protected $table = 'user_verifications';

    protected function getToken()
    {
        return hash_hmac('sha256', Str::random(40), config('app.key'));
    }

    public function createVerification($user)
    {
        return !$this->getVerification($user) ? $this->createToken($user) : $this->regenerateToken($user);
    }

    private function regenerateToken($user)
    {
        $token = $this->getToken();

        DB::table($this->table)->where('user_id', $user->id)->update([
            'token'         => $token,
            'created_at'    => now()
        ]);

        return $token;
    }

    private function createToken($user)
    {
        $token = $this->getToken();

        DB::table($this->table)->insert([
            'user_id'       => $user->id,
            'token'         => $token,
            'created_at'    => now()
        ]);

        return $token;
    }

    public function getVerification($user)
    {
        return DB::table($this->table)->where('user_id', $user->id)->first();
    }

    public function getVerificationByToken($token)
    {
        return DB::table($this->table)->where('token', $token)->first();
    }

    public function deleteVerification($token)
    {
        DB::table($this->table)->where('token', $token)->delete();
    }

}
