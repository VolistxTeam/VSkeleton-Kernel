<?php

namespace Volistx\FrameworkKernel\Tests;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use Volistx\FrameworkKernel\Database\Factories\AccessTokenFactory;
use Volistx\FrameworkKernel\Database\Factories\AdminLogFactory;
use Volistx\FrameworkKernel\Helpers\SHA256Hasher;
use Volistx\FrameworkKernel\Models\AdminLog;

class AdminLogControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function authorize_get_log_permissions()
    {
        $key = Str::random(64);
        $token = $this->GenerateAccessToken($key, 1);
        $log = AdminLog::query()->first();

        $this->TestPermissions($token, $key, 'get', "/sys-bin/admin/logs/$log->id", [
            'admin-logs:*' => 200,
            '' => 401,
            'admin-logs:view' => 200,
        ]);
    }

    private function GenerateAccessToken(string $key, int $logsCount): Collection|Model
    {
        $salt = Str::random(16);
        $token = AccessTokenFactory::new()
            ->create(['key' => substr($key, 0, 32),
                'secret' => SHA256Hasher::make(substr($key, 32), ['salt' => $salt]),
                'secret_salt' => $salt,
                'permissions' => ['admin-logs:*'],]);

        AdminLogFactory::new()->count($logsCount)->create([
            'access_token_id' => $token->id,
        ]);

        return $token;
    }

    #[Test]
    public function get_log(): void
    {
        $key = Str::random(64);
        $this->GenerateAccessToken($key, 1);
        $log = AdminLog::query()->first();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $key,
        ])->get("/sys-bin/admin/logs/$log->id");

        $response->assertStatus(200);
        self::assertSame($log->id, json_decode($response->getContent())->id);
    }

    #[Test]
    public function authorize_get_logs_permissions(): void
    {
        $key = Str::random(64);
        $token = $this->GenerateAccessToken($key, 5);

        $this->TestPermissions($token, $key, 'get', '/sys-bin/admin/logs', [
            'admin-logs:*' => 200,
            '' => 401,
            'admin-logs:view-all' => 200,
        ]);
    }

    #[Test]
    public function get_logs_with_default_pagination(): void
    {
        $key = Str::random(64);
        $this->GenerateAccessToken($key, 50);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $key,
        ])->get('/sys-bin/admin/logs');

        $response->assertStatus(200);
        self::assertCount(50, json_decode($response->getContent())->items);
    }

    #[Test]
    public function get_logs_with_custom_pagination(): void
    {
        $key = Str::random(64);
        $this->GenerateAccessToken($key, 50);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $key,
        ])->get('/sys-bin/admin/logs?limit=1');

        $response->assertStatus(200);
        self::assertCount(1, json_decode($response->getContent())->items);
    }
}
