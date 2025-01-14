<?php

namespace Volistx\FrameworkKernel\Services;

use Volistx\FrameworkKernel\DataTransferObjects\AdminLogDTO;
use Volistx\FrameworkKernel\Repositories\AdminLogRepository;
use Volistx\FrameworkKernel\Services\Interfaces\IAdminLoggingService;

class LocalAdminLoggingService implements IAdminLoggingService
{
    private AdminLogRepository $logRepository;

    public function __construct(AdminLogRepository $logRepository)
    {
        $this->logRepository = $logRepository;
    }

    /**
     * Create a new admin log entry.
     */
    public function CreateAdminLog(array $inputs): void
    {
        $this->logRepository->Create($inputs);
    }

    /**
     * Get an admin log entry by log ID.
     */
    public function GetAdminLog(string $logId): mixed
    {
        $log = $this->logRepository->Find($logId);

        if ($log === null) {
            return null;
        }

        return AdminLogDTO::fromModel($log)->getDTO();
    }

    /**
     * Get all admin log entries with pagination support.
     */
    public function GetAdminLogs(string $search, int $page, int $limit): ?array
    {
        $logs = $this->logRepository->FindAll($search, $page, $limit);

        if ($logs === null) {
            return null;
        }

        $logDTOs = [];

        foreach ($logs->items() as $log) {
            $logDTOs[] = AdminLogDTO::fromModel($log)->getDTO();
        }

        return [
            'pagination' => [
                'per_page' => $logs->perPage(),
                'current' => $logs->currentPage(),
                'total' => $logs->lastPage(),
            ],
            'items' => $logDTOs,
        ];
    }
}
