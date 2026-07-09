<?php

namespace App\Containers\AppSection\Notification\UI\API\Transformers;

use App\Containers\AppSection\Notification\Models\Notification;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class NotificationTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(Notification $notification): array
    {
        $user = $notification->users->first();
        return [
            'type' => $notification->getResourceKey(),
            'id' => $notification->id,
            'title' => $notification->title,
            'message' => $notification->message,
            'body_segments' => $notification->data['body_segments'] ?? null,
            'is_read' => $notification->is_read,
            'type_notification' => $notification->type,
            'data' => $notification->data,
            'read_at' => ($user && $notification->is_read) ? $user->pivot->read_at : null,
            'created_at' => $notification->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $notification->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
