<?php

namespace App\Livewire;

use App\Models\WebHookLog;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout("components.layouts.app")]
class WebHookLogPage extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $logs = WebHookLog::latest()->paginate(10);
        return view("livewire.web-hook-logs", [
            'logs' => $logs
        ]);
    }
}
