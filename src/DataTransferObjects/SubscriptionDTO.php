<?php

namespace VolistxTeam\VSkeletonKernel\DataTransferObjects;

use Carbon\Carbon;

class SubscriptionDTO extends DataTransferObjectBase
{
    public string $id;
    public string $user_id;
    public string $plan_id;
    public string $plan_activated_at;
    public ?string $plan_expires_at;
    public string $created_at;
    public string $updated_at;

    public static function fromModel($subscription): self
    {
        return new self($subscription);
    }

    public function GetDTO(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'plan' => PlanDTO::fromModel($this->entity->plan()->first())->GetDTO(),
            'plan_status' => [
                'is_expired' => $this->plan_expires_at != null && Carbon::now()->greaterThan(Carbon::createFromTimeString($this->plan_expires_at)),
                'activated_at' => $this->plan_activated_at,
                'expires_at' => $this->plan_expires_at
            ]
        ];
    }
}