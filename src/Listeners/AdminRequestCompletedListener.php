<?php

namespace App\Listeners;

namespace Volistx\FrameworkKernel\Listeners;

use Volistx\FrameworkKernel\Events\UserRequestCompleted;
use Volistx\FrameworkKernel\Facades\PersonalTokens;
use Volistx\FrameworkKernel\Repositories\UserLogRepository;
use Volistx\FrameworkKernel\Services\Interfaces\IAdminLoggingService;
use Volistx\FrameworkKernel\Services\Interfaces\IUserLoggingService;

class AdminRequestCompletedListener
{
    private IAdminLoggingService $adminLoggingService;

    public function __construct(IAdminLoggingService $adminLoggingService)
    {
        $this->adminLoggingService = $adminLoggingService;
    }

    public function handle(UserRequestCompleted $event)
    {
        $this->adminLoggingService->CreateAdminLog($event->inputs);
    }
}
