<div class="d-flex align-items-center" x-data="{ copied: false, text: '' }" x-init="text = $refs.content.innerText.trim()">
    <div x-ref="content" class="mb-0">
        {{ $slot }}
    </div>
    <button type="button" class="btn btn-sm text-secondary p-0 border-0 bg-transparent ms-2" 
        @click="navigator.clipboard.writeText(text); copied = true; setTimeout(() => copied = false, 2000)" title="Copy">
        <i class="bi" :class="copied ? 'bi-check2 text-success' : 'bi-clipboard'"></i>
    </button>
</div>
