<?php

namespace App\Jobs;

use App\Mail\Padrao;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries;
    protected $details;
    protected $corpo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details, $corpo, $tries)
    {
        $this->details = $details;
        $this->corpo = $corpo;
        $this->tries = $tries;
    }
 

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->details['email'])->send($this->corpo);
    }
}
