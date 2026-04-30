@extends('layouts.app')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

    :root {
        --bg: #f4f5f7;
        --surface: #ffffff;
        --surface-2: #f9fafb;
        --border: #e5e7eb;
        --text-primary: #111827;
        --text-secondary: #6b7280;
        --text-muted: #9ca3af;
        --accent: #1d4ed8;
        --danger: #dc2626;
        --shadow-sm: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
        --shadow: 0 4px 16px rgba(0,0,0,.06);
        --radius: 10px;
    }

    .fb-wrapper {
        font-family: 'DM Sans', sans-serif;
        background: var(--bg);
        min-height: 100vh;
        padding: 32px 24px;
        color: var(--text-primary);
    }

    .fb-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 28px;
        gap: 16px;
        flex-wrap: wrap;
    }

    .fb-title {
        font-size: 26px;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: -.02em;
        line-height: 1.2;
    }

    .fb-subtitle {
        font-size: 13.5px;
        color: var(--text-secondary);
        margin-top: 4px;
    }

    .fb-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
        max-width: 760px;
    }

    .fb-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border);
        background: var(--surface-2);
    }

    .fb-card-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 4px;
    }

    .fb-card-subtitle {
        font-size: 12px;
        color: var(--text-muted);
    }

    .fb-form {
        padding: 20px;
    }

    .fb-field {
        margin-bottom: 16px;
    }

    .fb-label {
        display: block;
        font-size: 12.5px;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 7px;
    }

    .fb-input,
    .fb-select,
    .fb-textarea {
        width: 100%;
        border: 1px solid var(--border);
        border-radius: 8px;
        background: var(--surface);
        color: var(--text-primary);
        font-family: 'DM Sans', sans-serif;
        font-size: 13.5px;
        padding: 10px 13px;
        outline: none;
        transition: border-color .15s, box-shadow .15s;
        box-shadow: var(--shadow-sm);
    }

    .fb-select {
        height: 42px;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        padding-right: 38px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 20 20' fill='none'%3E%3Cpath d='M5 7l5 5 5-5' stroke='%239ca3af' stroke-width='2' stroke-linecap='round'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 13px center;
        background-size: 14px;
    }

    .fb-input:focus,
    .fb-select:focus,
    .fb-textarea:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(29,78,216,.1);
    }

    .fb-textarea {
        resize: vertical;
        min-height: 110px;
        line-height: 1.6;
    }

    .fb-invalid {
        border-color: var(--danger);
    }

    .fb-error {
        display: block;
        margin-top: 6px;
        font-size: 12px;
        color: var(--danger);
    }

    .fb-check {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 20px;
    }

    .fb-check input {
        width: 16px;
        height: 16px;
        accent-color: var(--accent);
    }

    .fb-check label {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-secondary);
        cursor: pointer;
    }

    .fb-actions-form {
        display: flex;
        align-items: center;
        gap: 10px;
        padding-top: 16px;
        border-top: 1px solid var(--border);
        flex-wrap: wrap;
    }

    .fb-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 16px;
        border-radius: 8px;
        font-family: 'DM Sans', sans-serif;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        border: 1px solid transparent;
        transition: all .15s;
        text-decoration: none;
        white-space: nowrap;
    }

    .fb-btn-primary {
        background: var(--accent);
        color: #fff;
        box-shadow: var(--shadow-sm);
    }

    .fb-btn-primary:hover {
        background: #1e40af;
    }

    .fb-btn-ghost {
        background: var(--surface);
        color: var(--text-secondary);
        border-color: var(--border);
        box-shadow: var(--shadow-sm);
    }

    .fb-btn-ghost:hover {
        background: var(--surface-2);
        color: var(--text-primary);
    }

    @media (max-width: 768px) {
        .fb-wrapper {
            padding: 16px;
        }

        .fb-title {
            font-size: 20px;
        }

        .fb-card {
            max-width: 100%;
        }
    }

    .dark .fb-wrapper {
        background: #111827;
        color: #e5e7eb;
    }

    .dark .fb-title,
    .dark .fb-card-title {
        color: #f3f4f6;
    }

    .dark .fb-subtitle,
    .dark .fb-card-subtitle,
    .dark .fb-label,
    .dark .fb-check label {
        color: #9ca3af;
    }

    .dark .fb-card,
    .dark .fb-input,
    .dark .fb-select,
    .dark .fb-textarea {
        background-color: #1f2937;
        border-color: #374151;
        box-shadow: none;
        color: #e5e7eb;
    }

    .dark .fb-card-header {
        background: rgba(55, 65, 81, 0.5);
        border-color: #374151;
    }

    .dark .fb-input:focus,
    .dark .fb-select:focus,
    .dark .fb-textarea:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,.22);
    }

    .dark .fb-btn-ghost {
        background: #1f2937;
        border-color: #374151;
        color: #d1d5db;
    }

    .dark .fb-btn-ghost:hover {
        background: #374151;
        color: #f9fafb;
    }
</style>

<div class="fb-wrapper">
    <div class="fb-header">
        <div>
            <h1 class="fb-title">Edit Barang Project</h1>
            <p class="fb-subtitle">Perbarui barang atau komponen yang terkait dengan project feedback.</p>
        </div>

        <a href="{{ route('feedback.index') }}" class="fb-btn fb-btn-ghost">
            Kembali
        </a>
    </div>

    <div class="fb-card">
        <div class="fb-card-header">
            <div class="fb-card-title">Informasi Barang</div>
            <div class="fb-card-subtitle">Ubah project, nama barang, deskripsi, atau status aktifnya.</div>
        </div>

        <form action="{{ route('feedback-project-items.update', $feedbackProjectItem->id) }}" method="POST" class="fb-form">
            @csrf
            @method('PUT')

            <div class="fb-field">
                <label class="fb-label" for="feedback_project_id">Project</label>
                <select id="feedback_project_id"
                        name="feedback_project_id"
                        class="fb-select @error('feedback_project_id') fb-invalid @enderror"
                        required>
                    <option value="">Pilih project</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}"
                            {{ old('feedback_project_id', $feedbackProjectItem->feedback_project_id) == $project->id ? 'selected' : '' }}>
                            {{ $project->nama_project }}
                        </option>
                    @endforeach
                </select>

                @error('feedback_project_id')
                    <span class="fb-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="fb-field">
                <label class="fb-label" for="nama_barang">Nama Barang</label>
                <input type="text"
                       id="nama_barang"
                       name="nama_barang"
                       class="fb-input @error('nama_barang') fb-invalid @enderror"
                       value="{{ old('nama_barang', $feedbackProjectItem->nama_barang) }}"
                       placeholder="Contoh: Panel Control / Roll Filter"
                       required>

                @error('nama_barang')
                    <span class="fb-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="fb-field">
                <label class="fb-label" for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi"
                          name="deskripsi"
                          class="fb-textarea @error('deskripsi') fb-invalid @enderror"
                          rows="4"
                          placeholder="Tambahkan deskripsi singkat barang">{{ old('deskripsi', $feedbackProjectItem->deskripsi) }}</textarea>

                @error('deskripsi')
                    <span class="fb-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="fb-check">
                <input type="checkbox"
                       name="is_active"
                       value="1"
                       id="is_active"
                       {{ old('is_active', $feedbackProjectItem->is_active) ? 'checked' : '' }}>

                <label for="is_active">Aktif</label>
            </div>

            <div class="fb-actions-form">
                <button type="submit" class="fb-btn fb-btn-primary">
                    Update
                </button>

                <a href="{{ route('feedback.index') }}" class="fb-btn fb-btn-ghost">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
