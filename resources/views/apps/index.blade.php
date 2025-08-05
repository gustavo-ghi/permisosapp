@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('Aplicaciones') }}</h5>
                    <a href="{{ route('apps.create') }}" class="btn btn-primary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16" style="vertical-align: -0.125em;"><path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/></svg>
                        Nueva App
                    </a>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Token</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-center">Caducidad</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($apps as $app)
                                    <tr>
                                        <td class="fw-bold">{{ $app->name }}</td>
                                        <td>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control font-monospace" value="{{ Str::limit($app->api_token, 28) }}" readonly>
                                                <button class="btn btn-outline-secondary" type="button" onclick="copyToken('{{ $app->api_token }}')" title="Copiar token">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard" viewBox="0 0 16 16"><path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1z"/><path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0z"/></svg>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if ($app->is_active)
                                                <span class="badge bg-success-subtle text-success-emphasis rounded-pill">Activo</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger-emphasis rounded-pill">Inactivo</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($app->expires_at)
                                                {{ \Carbon\Carbon::parse($app->expires_at)->format('d/m/Y') }}
                                            @else
                                                <span class="text-muted">Nunca</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('apps.edit', $app) }}" class="btn btn-warning btn-sm">Editar</a>
                                                <form action="{{ route('apps.destroy', $app) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar \'{{ $app->name }}\'?');" class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted py-4">No hay aplicaciones registradas.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($apps->hasPages())
                    <div class="card-footer">{{ $apps->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function copyToken(token) {
        navigator.clipboard.writeText(token).then(() => alert('¡Token copiado!'), () => alert('Error al copiar'));
    }
</script>
@endpush
