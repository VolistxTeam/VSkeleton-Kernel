<?php

namespace VolistxTeam\VSkeletonKernel\Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use VolistxTeam\VSkeletonKernel\Classes\SHA256Hasher;
use VolistxTeam\VSkeletonKernel\Models\AccessToken;

class AccessTokenFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AccessToken::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */

    public function definition()
    {
        $key = Str::random(64);
        $salt = Str::random(16);

        return [
            'key' => substr($key, 0, 32),
            'secret' => SHA256Hasher::make(substr($key, 32), ['salt' => $salt]),
            'secret_salt' => $salt,
            'permissions' => array(),
            'whitelist_range' => array(),
            'created_at' => Carbon::now()
        ];
    }
}
