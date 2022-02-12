<?php

namespace VolistxTeam\VSkeletonKernel\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use VolistxTeam\VSkeletonKernel\Classes\SHA256Hasher;
use VolistxTeam\VSkeletonKernel\Models\AccessToken;

class GenerateCommand extends Command
{
    protected $signature = "access-key:generate";

    protected $description = "Create an access key";

    public function handle()
    {
        $key = Str::random(64);
        $salt = Str::random(16);

        AccessToken::query()->create(array(
            'key' => substr($key, 0, 32),
            'secret' => SHA256Hasher::make(substr($key, 32), ['salt' => $salt]),
            'secret_salt' => $salt,
            'permissions' => array('*'),
            'whitelist_range' => array()
        ));

        $this->info('Your access key is created: ' . $key);
    }
}
