<?php

namespace App\Containers\AppSection\Notification\Data\Repositories;

use App\Containers\AppSection\Notification\Models\Notification;
use App\Ship\Parents\Repositories\Repository as ParentRepository;
use Illuminate\Support\Facades\Auth;

/**
 * @template TModel of Notification
 *
 * @extends ParentRepository<TModel>
 */
final class NotificationRepository extends ParentRepository
{
    protected $fieldSearchable = [
        // 'id' => '=',
    ];

    public function model(): string
    {
        return Notification::class;
    }

    public function listing($request)
    {
        $type = $request['type'] ?? null;
        $userId = Auth::id();
        $query = $this->model->newQuery()
            ->with([
                'users' => fn($q) => $q->where('user_id', $userId)
            ])
            ->where('type', '!=', Notification::TYPE_REMINDER)
            ->whereHas('users', fn($q) => $q->where('user_id', $userId))->orderBy('created_at', 'desc');

        if ($type) {
            $query = $query->where('type', $type);
        }
        $perPage = $request['page_size'] ?? 10;
        return $query->paginate($perPage);
    }

    public function markAsRead($notificationId)
    {
        $userId = Auth::id();
        $notification = $this->model->findOrFail($notificationId);

        if (!$notification->users()->where('user_id', $userId)->exists()) {
            abort(403, 'You are not authorized to mark this notification as read.');
        }

        $pivot = $notification->users()
            ->where('user_id', $userId)
            ->first()
            ->pivot;

        if (!$pivot->is_read) {
            // Mark read
            $notification->users()->updateExistingPivot($userId, [
                'is_read' => true,
                'read_at' => now(),
            ]);

            // 4. Giảm unread_count của user
            $user = Auth::user();

            $data = $user->data ?? [];
            if (is_string($data)) {
                $data = json_decode($data, true) ?: [];
            }

            $currentUnread = $data['unread_count'] ?? 0;
            $data['unread_count'] = max(0, $currentUnread - 1);

            $user->data = $data;
            $user->saveQuietly();
        }

        return $notification;
    }

    public function markAllRead()
    {
        $userId = Auth::id();
        $notifications = $this->model->whereHas('users', fn($q) => $q->where('user_id', $userId))->get();

        foreach ($notifications as $notification) {
            $notification->users()->updateExistingPivot($userId, [
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        $user = Auth::user();
        $data = $user->data ?? [];
        if (is_string($data)) {
            $data = json_decode($data, true) ?: [];
        }

        $data['unread_count'] = 0;

        $user->data = $data;
        $user->saveQuietly();
        return $notifications;
    }
}
