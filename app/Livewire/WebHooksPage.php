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
  public $api_key, $header_auth_name = 'Authorization';
  
  public $showTestModal = false;
  public $testWebhookId;
  public $testVariables = [];
  public $testVariableValues = [];
  public $eventOptions = [];

  protected function rules()
  {
    return [
      'name' => 'required|string|max:255',
      'url' => 'required|url',
      'event' => 'required|string|max:255',
      'type' => 'required|in:GET,POST',
      'api_key' => 'nullable|string|max:255',
      'header_auth_name' => 'nullable|string|max:255',
      'body' => ['required', 'string'],
      'is_active' => 'boolean',
    ];
  }

  public function mount()
  {
    $this->webhooks = WebHook::all();
    $this->eventOptions = [
      'App\\Events\\ECourtAccountCreatedEvent',
      'App\\Events\\EcourtAccountUpdateEvent',
      'App\\Events\\EcourtAccountDeleteEvent',
      'App\\Events\\IdentityCreateEvent',
      'App\\Events\\IdentityUpdateEvent',
      'App\\Events\\IdentityDeleteEvent',
    ];
  }

  public function showAddModal()
  {
    $this->reset(['name', 'url', 'event', 'type', 'api_key', 'header_auth_name', 'body', 'is_active']);
    $this->type = 'POST';
    $this->header_auth_name = 'Authorization';
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
    $this->api_key = $webhook->api_key;
    $this->header_auth_name = $webhook->header_auth_name;
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
    $webhook = WebHook::findOrFail($id);
    $this->testWebhookId = $id;

    preg_match_all('/{([A-Za-z_0-9]+)}/', $webhook->body, $matches);
    
    if (!empty($matches[1])) {
        $this->testVariables = array_unique($matches[1]);
        $this->testVariableValues = [];
        foreach ($this->testVariables as $var) {
            $this->testVariableValues[$var] = '';
        }
        $this->showTestModal = true;
    } else {
        $this->executeTestWebhook();
    }
  }

  public function executeTestWebhook()
  {
    $this->testing = true;
    $webhook = WebHook::findOrFail($this->testWebhookId);
    
    $body = $webhook->body;
    foreach ($this->testVariableValues as $key => $value) {
        $body = str_replace('{' . $key . '}', $value, $body);
    }
    
    $bodyArray = json_decode($body, TRUE);

    try {
      $request = \Illuminate\Support\Facades\Http::withHeaders([]);
      if (!empty($webhook->api_key)) {
          $headerName = $webhook->header_auth_name ?: 'Authorization';
          $request = $request->withHeaders([
              $headerName => $webhook->api_key
          ]);
      }

      if ($webhook->type === 'POST') {
        $response = $request->post($webhook->url, $bodyArray);
      } else {
        $response = $request->get($webhook->url, $bodyArray);
      }

      WebHookLog::create([
        'web_hook_id' => $webhook->id,
        'event' => $webhook->event,
        'payload' => $bodyArray,
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
        'payload' => $bodyArray,
        'success' => false,
        'error_message' => $th->getMessage(),
      ]);

      $this->testResult = 'Error: ' . $th->getMessage();
      session()->flash('error', 'Webhook test failed');
    } finally {
      $this->testing = false;
      $this->showTestModal = false;
    }
  }
}
