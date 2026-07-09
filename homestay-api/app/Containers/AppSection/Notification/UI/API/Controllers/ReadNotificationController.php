<?php

namespace App\Containers\AppSection\Notification\UI\API\Controllers;

use App\Containers\AppSection\Notification\Actions\MarkAllReadAction;
use App\Containers\AppSection\Notification\Actions\ReadNotificationAction;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\Request;

final class ReadNotificationController extends ApiController
{
    public function read(Request $request, ReadNotificationAction $action)
    {
        $unreadCount = $action->run($request);
        return response()->json(['message' => 'Notification marked as read successfully.', 'unread_count' => $unreadCount]);
    }

    public function readAll(MarkAllReadAction $action)
    {
        $unreadCount = $action->run();
        return response()->json(['message' => 'All notifications marked as read successfully.', 'unread_count' => $unreadCount]);
    }

}
