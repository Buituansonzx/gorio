<?php

namespace App\Containers\MobileSection\Profile\UI\API\Transformers;

use App\Containers\SharedSection\Room\Models\CheckoutInstructionType;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class CheckoutInstructionTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(CheckoutInstructionType $checkoutInstructionType): array
    {
            return [
                'checkout_method_name' => $checkoutInstructionType->getTranslation('name', request()->header('Accept-Language')),
                'content' => $checkoutInstructionType->pivot->content,
            ];
    }
}
