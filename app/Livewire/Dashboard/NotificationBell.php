<?php

namespace App\Livewire\Dashboard;

use App\Models\Announcement;
use Livewire\Component;

class NotificationBell extends Component
{
    public bool $isOpen = false;

    public function toggle(): void
    {
        $this->isOpen = ! $this->isOpen;
    }

    public function render()
    {
        $user = auth()->user();
        $visibleAnnouncements = Announcement::forUser($user);
        $unreadCount = (clone $visibleAnnouncements)->count();
        $announcements = $this->isOpen
            ? $visibleAnnouncements->orderByDesc('is_pinned')->latest()->limit(10)->get()
            : collect();

        return view('livewire.dashboard.notification-bell', compact('announcements', 'unreadCount'));
    }
}
