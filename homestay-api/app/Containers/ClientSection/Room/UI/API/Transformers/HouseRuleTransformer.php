<?php

namespace App\Containers\ClientSection\Room\UI\API\Transformers;

use App\Containers\SharedSection\Room\Models\HouseRule;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class HouseRuleTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(HouseRule $houseRule): array
    {
        return [
            'type' => $houseRule->getResourceKey(),
            'id' => $houseRule->id,
            'name' => $houseRule->getTranslation('name', request()->header('Accept-Language')),
            'type_rule' => $houseRule->type,
            'value' => $houseRule->pivot->value,
        ];
    }
}
