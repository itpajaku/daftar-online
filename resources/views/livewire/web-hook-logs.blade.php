<x-slot:sidebar>
  <x-layouts.sidebar />
</x-slot:sidebar>

<div class="container">
  <h2 class="mb-4">Webhook Logs</h2>
  <hr>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>ID</th>
        <th>Webhook</th>
        <th>Event</th>
        <th>Payload</th>
        <th>Status</th>
        <th>HTTP Code</th>
        <th>Response / Error</th>
        <th>When</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($logs as $log)
        <tr>
          <td>{{ $log->id }}</td>
          <td>{{ $log->webhook?->name ?? '-' }}</td>
          <td>{{ class_basename($log->event) }}</td>
          <td>
            <details>
              <summary>Lihat Payload</summary>
              <pre class="mb-0">{{ json_encode($log->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </details>
          </td>
          <td>
            @if ($log->success)
              <span class="badge bg-success">Success</span>
            @else
              <span class="badge bg-danger">Failed</span>
            @endif
          </td>
          <td>{{ $log->status_code ?? '-' }}</td>
          <td>
            <details>
              <summary>Lihat Response</summary>
              <pre class="mb-0"> {{ print_r(json_decode($log->response, true), true) ?: $log->response }}</pre>
            </details>
          </td>
          <td>
            {{ $log->created_at->diffForHumans() }}
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="8">
            <div class="text-center">
              Tidak ada Data
            </div>
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div class="mt-3">
    {{ $logs->links() }}
  </div>
</div>
