<?php

namespace App\Jobs;

use App\Notifications\ResetPassword;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * User need verify
     *
     * @var $user
     */
    protected $user;

    /**
     * The code instance.
     *
     * @var string $code
     */
    protected $code;

    /**
     * Create a new job instance.
     */
    public function __construct($user, $code)
    {
        $this->user = $user;
        $this->code = $code;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->user->update([
            'reset_password_code' => $this->code,
            'reset_password_expired_at' => Carbon::now()->addMinutes(5),
        ]);
        Notification::send(
            $this->user,
            new ResetPassword($this->code)
        );
    }
}
