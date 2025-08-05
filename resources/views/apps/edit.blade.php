@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header"><h5 class="mb-0">{{ __('Editar Aplicación') }}</h5></div>
                <div class="card-body">
                    <form id="updateAppForm" action="{{ route('apps.update', $app) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre de la Aplicación</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $app->name) }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">API Token</label>
                            <div class="input-group">
                                <input type="text" id="api_token_field" class="form-control font-monospace" value="{{ $app->api_token }}" readonly>
                                <button id="regenerateBtn" class="btn btn-outline-secondary" type="button" title="Regenerar Token">
                                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                    Regenerar
                                </button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="expires_at" class="form-label">Fecha de Caducidad (Opcional)</label>
                            <input type="date" name="expires_at" id="expires_at" value="{{ old('expires_at', $app->expires_at ? \Carbon\Carbon::parse($app->expires_at)->format('Y-m-d') : '') }}" class="form-control">
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active" @if(old('is_active', $app->is_active)) checked @endif>
                            <label class="form-check-label" for="is_active">¿Activa?</label>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('apps.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button id="updateBtn" type="submit" class="btn btn-primary d-flex align-items-center">
                                <span class="spinner-border spinner-border-sm d-none me-2" role="status" aria-hidden="true"></span>
                                Actualizar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const updateForm = document.getElementById('updateAppForm');
        const updateBtn = document.getElementById('updateBtn');
        if (updateBtn) {
            const updateSpinner = updateBtn.querySelector('.spinner-border');
            updateForm.addEventListener('submit', function () {
                updateSpinner.classList.remove('d-none');
                updateBtn.disabled = true;
            });
        }

        const regenerateBtn = document.getElementById('regenerateBtn');
        if (regenerateBtn) {
            const regenerateSpinner = regenerateBtn.querySelector('.spinner-border');
            const tokenField = document.getElementById('api_token_field');
            regenerateBtn.addEventListener('click', async function () {
                if (!confirm('¿Estás seguro de que quieres regenerar el token? El token anterior dejará de funcionar.')) return;
                
                regenerateSpinner.classList.remove('d-none');
                regenerateBtn.disabled = true;

                try {
                    const response = await fetch('{{ route("apps.regenerateToken", $app) }}', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                    });
                    const data = await response.json();
                    if (data.success) {
                        tokenField.value = data.new_token;
                        alert(data.message);
                    } else {
                        alert('Hubo un error al regenerar el token.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Hubo un error de conexión.');
                } finally {
                    regenerateSpinner.classList.add('d-none');
                    regenerateBtn.disabled = false;
                }
            });
        }
    });
</script>
@endpush
