<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\WebHook;
use App\Models\WebHookLog;

class WebHooksPage extends Component
{
  public $webhooks;
  public $testResult;
  public $showModal = false;
  public $name, $url, $event, $type = 'POST', $body, $is_active = true;
  public $eventOptions = [];

  protected function rules()
  {
    return [
      'name' => 'required|string|max:255',
      'url' => 'required|url',
      'event' => 'required|string|max:255',
      'type' => 'required|in:GET,POST',
      'body' => ['required', 'string'],
      'is_active' => 'boolean',
    ];
  }

  public function mount()
  {
    $this->webhooks = WebHook::all();
    $this->eventOptions = [
      'App\\Events\\EcourtAccountCreateEvent',
      'App\\Events\\EcourtAccountUpdateEvent',
      'App\\Events\\EcourtAccountDeleteEvent',
      'App\\Events\\IdentityCreateEvent',
      'App\\Events\\IdentityUpdateEvent',
      'App\\Events\\IdentityDeleteEvent',
    ];
  }

  public function showAddModal()
  {
    $this->reset(['name', 'url', 'event', 'type', 'body', 'is_active']);
    $this->type = 'POST';
    $this->is_active = true;
    $this->showModal = true;
  }


  public function saveWebhook()
  {
    $validated = $this->validate();
    WebHook::create($validated);
    $this->webhooks = WebHook::all();
    $this->showModal = false;
    session()->flash('success', 'Webhook berhasil ditambahkan!');
  }


  public $editId = null;

  public function editWebhook($id)
  {
    $webhook = WebHook::findOrFail($id);
    $this->editId = $id;
    $this->name = $webhook->name;
    $this->url = $webhook->url;
    $this->event = $webhook->event;
    $this->type = $webhook->type;
    $this->body = $webhook->body;
    $this->is_active = $webhook->is_active;
    $this->showModal = true;
  }

  public function updateWebhook()
  {
    $validated = $this->validate();
    $webhook = WebHook::findOrFail($this->editId);
    $webhook->update($validated);
    $this->webhooks = WebHook::all();
    $this->showModal = false;
    $this->editId = null;
    session()->flash('success', 'Webhook berhasil diupdate!');
  }

  public function deleteWebhook($id)
  {
    WebHook::findOrFail($id)->delete();
    $this->webhooks = WebHook::all();
    session()->flash('success', 'Webhook berhasil dihapus!');
  }

  public function render()
  {
    return view('livewire.web-hooks-page');
  }


  public function testWebhook($id)
  {
    $this->testing = true;
    $webhook = WebHook::findOrFail($id);

    try {
      if ($webhook->type === 'POST') {
        $response = \Illuminate\Support\Facades\Http::post($webhook->url, json_decode($webhook->body, TRUE));
      } else {
        $response = \Illuminate\Support\Facades\Http::get($webhook->url, json_decode($webhook->body, TRUE));
      }

      WebHookLog::create([
        'web_hook_id' => $webhook->id,
        'event' => $webhook->event,
        'payload' => json_decode($webhook->body, TRUE),
        'status_code' => $response->status(),
        'response' => $response->body(),
        'success' => $response->successful(),
      ]);

      $this->testResult = $response->body();
      session()->flash('success', 'Webhook test executed!');
    } catch (\Throwable $th) {
      WebHookLog::create([
        'web_hook_id' => $webhook->id,
        'event' => $webhook->event,
        'payload' => json_decode($webhook->body, TRUE),
        'success' => false,
        'error_message' => $th->getMessage(),
      ]);

      $this->testResult = 'Error: ' . $th->getMessage();
      session()->flash('error', 'Webhook test failed');
    } finally {
      $this->testing = false;
    }
  }
}
