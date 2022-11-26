<?php

namespace Volistx\FrameworkKernel\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;

class UserRequestCompleted
{
    use  InteractsWithSockets, SerializesModels;

    public array $inputs;

    public function __construct(array $inputs)
    {
        $this->inputs = $inputs;
    }
}
