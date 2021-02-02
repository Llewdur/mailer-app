<?php

namespace App\Console\Commands;

use App\Jobs\EmailJob;
use App\Models\Email;
use Illuminate\Console\Command;

class LlewCommand extends Command
{
    protected $signature = 'llew:test';

    protected $description = 'Quick testing of methods';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $email = Email::latest()->notSent()->firstOrFail();

        EmailJob::dispatchNow($email);

        $this->info('Done');
    }
}
