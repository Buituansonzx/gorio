<?php

namespace App\Containers\AppSection\Notification\Data\Factories;

use App\Containers\AppSection\Notification\Models\Notification;
use App\Ship\Parents\Factories\Factory as ParentFactory;

/**
 * @template TModel of Notification
 *
 * @extends ParentFactory<TModel>
 */
final class NotificationFactory extends ParentFactory
{
    /** @var class-string<TModel> */
    protected $model = Notification::class;

    public function definition(): array
    {
        return [];
    }
}
