<?php

namespace App\Livewire;

use App\Models\WebHookLog;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout("components.layouts.app")]
class WebHookLogPage extends Component
{
    public $logs;

    public function mount()
    {
        $this->logs = WebHookLog::latest()->get();
    }

    public function render()
    {
        return view("livewire.web-hook-logs");
    }
}
