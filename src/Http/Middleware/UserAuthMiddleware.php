<?php

namespace VolistxTeam\VSkeletonKernel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use VolistxTeam\VSkeletonKernel\Facades\Messages;
use VolistxTeam\VSkeletonKernel\Repositories\PersonalTokenRepository;
use VolistxTeam\VSkeletonKernel\ValidationRules\IPValidationRule;
use VolistxTeam\VSkeletonKernel\ValidationRules\KeyExpiryValidationRule;
use VolistxTeam\VSkeletonKernel\ValidationRules\RateLimitValidationRule;
use VolistxTeam\VSkeletonKernel\ValidationRules\RequestsCountValidationRule;
use VolistxTeam\VSkeletonKernel\ValidationRules\ValidationRuleBase;
use VolistxTeam\VSkeletonKernel\ValidationRules\ValidKeyValidationRule;

class UserAuthMiddleware
{
    private PersonalTokenRepository $personalTokenRepository;

    public function __construct(PersonalTokenRepository $personalTokenRepository)
    {
        $this->personalTokenRepository = $personalTokenRepository;
    }


    public function handle(Request $request, Closure $next)
    {
        $token = $this->personalTokenRepository->AuthPersonalToken($request->bearerToken());

        if (!$token) {
            return response()->json(Messages::E500());
        }
        $plan = $token->subscription()->first()->plan()->first();
        //prepare inputs array
        $inputs = [
            'request' => $request,
            'token' => $token,
            'plan' => $plan
        ];

        $getValidators = config('volistx.validators');

        $validators = [];

        foreach ($getValidators as $item) {
            $validators[] = new $item($inputs);
        }

        foreach ($validators as $validator) {
            $result = $validator->validate();
            if ($result !== true) {
                return response()->json($result['message'], $result['code']);
            }
        }

        $request->merge([
            'X_PERSONAL_TOKEN' => $token,
            'PLAN' => $plan
        ]);

        return $next($request);
    }
}