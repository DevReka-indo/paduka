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
        --accent-light: #dbeafe;
        --success: #059669;
        --success-light: #d1fae5;
        --danger: #dc2626;
        --danger-light: #fee2e2;
        --warning: #d97706;
        --warning-light: #fef3c7;
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

    /* ── Header ── */
    .fb-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 28px;
        gap: 16px;
        flex-wrap: wrap;
    }

    .fb-title-group {}

    .fb-eyebrow {
        font-size: 11px;
        font-weight: 600;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: var(--accent);
        margin-bottom: 4px;
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

    /* ── Stats strip ── */
    .fb-stats {
        display: flex;
        gap: 12px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .fb-stat-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 14px 20px;
        flex: 1;
        min-width: 130px;
        box-shadow: var(--shadow-sm);
    }

    .fb-stat-label {
        font-size: 11.5px;
        color: var(--text-muted);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: .05em;
        margin-bottom: 6px;
    }

    .fb-stat-value {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-primary);
        font-family: 'DM Mono', monospace;
        letter-spacing: -.03em;
    }

    .fb-stat-value.good { color: var(--success); }
    .fb-stat-value.warn { color: var(--warning); }

    /* ── Toolbar ── */
    .fb-toolbar {
        display: flex;
        gap: 10px;
        margin-bottom: 16px;
        align-items: center;
        flex-wrap: wrap;
    }

    .fb-search-wrap {
        position: relative;
        flex: 1;
        min-width: 220px;
        max-width: 420px;
    }

    .fb-search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        pointer-events: none;
    }

    .fb-search {
        width: 100%;
        padding: 9px 14px 9px 38px;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-family: 'DM Sans', sans-serif;
        font-size: 13.5px;
        color: var(--text-primary);
        background: var(--surface);
        box-shadow: var(--shadow-sm);
        outline: none;
        transition: border-color .15s, box-shadow .15s;
    }

    .fb-search:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(29,78,216,.1);
    }

    .fb-search::placeholder { color: var(--text-muted); }

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

    .fb-btn-primary:hover { background: #1e40af; }

    .fb-btn-ghost {
        background: var(--surface);
        color: var(--text-secondary);
        border-color: var(--border);
        box-shadow: var(--shadow-sm);
    }

    .fb-btn-ghost:hover { background: var(--surface-2); color: var(--text-primary); }

    /* ── Flash message ── */
    .fb-flash {
        display: flex;
        align-items: center;
        gap: 10px;
        background: var(--success-light);
        border: 1px solid #a7f3d0;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 13.5px;
        color: var(--success);
        font-weight: 500;
        margin-bottom: 16px;
    }

    /* ── Table card ── */
    .fb-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .fb-table {
        width: 100%;
        border-collapse: collapse;
    }

    .fb-table thead {
        background: var(--surface-2);
        border-bottom: 1px solid var(--border);
    }

    .fb-table th {
        padding: 11px 16px;
        text-align: left;
        font-size: 11.5px;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: .06em;
        white-space: nowrap;
    }

    .fb-table th:last-child { text-align: right; }

    .fb-table tbody tr {
        border-bottom: 1px solid var(--border);
        transition: background .1s;
    }

    .fb-table tbody tr:last-child { border-bottom: none; }
    .fb-table tbody tr:hover { background: #f8fafd; }

    .fb-table td {
        padding: 13px 16px;
        font-size: 13.5px;
        color: var(--text-primary);
        vertical-align: middle;
    }

    .fb-table td:last-child { text-align: right; }

    /* ── Name cell ── */
    .fb-name-cell { display: flex; flex-direction: column; gap: 1px; }
    .fb-name { font-weight: 600; font-size: 13.5px; }
    .fb-jabatan { font-size: 11.5px; color: var(--text-muted); }

    /* ── Badge ── */
    .fb-badge {
        display: inline-block;
        padding: 3px 9px;
        border-radius: 20px;
        font-size: 11.5px;
        font-weight: 600;
        font-family: 'DM Mono', monospace;
    }

    .fb-badge-green  { background: var(--success-light); color: #065f46; }
    .fb-badge-yellow { background: var(--warning-light); color: #92400e; }
    .fb-badge-red    { background: var(--danger-light);  color: #991b1b; }

    /* ── Actions ── */
    .fb-actions { display: flex; align-items: center; justify-content: flex-end; gap: 6px; }

    .fb-action-btn {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all .13s;
        background: none;
        white-space: nowrap;
        font-family: 'DM Sans', sans-serif;
    }

    .fb-action-detail {
        background: var(--accent-light);
        color: var(--accent);
        border-color: #bfdbfe;
    }

    .fb-action-detail:hover { background: #bfdbfe; }

    .fb-action-pdf {
        background: var(--success-light);
        color: var(--success);
        border-color: #a7f3d0;
    }

    .fb-action-pdf:hover { background: #a7f3d0; }

    .fb-action-delete {
        background: var(--danger-light);
        color: var(--danger);
        border-color: #fecaca;
    }

    .fb-action-delete:hover { background: #fecaca; }

    /* ── Empty state ── */
    .fb-empty {
        padding: 56px 24px;
        text-align: center;
        color: var(--text-muted);
    }

    .fb-empty-icon { font-size: 36px; margin-bottom: 12px; }
    .fb-empty-text { font-size: 14px; font-weight: 500; }
    .fb-empty-sub  { font-size: 12.5px; margin-top: 4px; }

    /* ── Pagination ── */
    .fb-pagination { padding: 14px 20px; border-top: 1px solid var(--border); background: var(--surface-2); }

    /* Tailwind pagination override */
    .fb-pagination nav span[aria-current="page"] span,
    .fb-pagination nav .relative.z-10 {
        background: var(--accent) !important;
        color: #fff !important;
        border-color: var(--accent) !important;
    }

    /* ── Responsive ── */
    @media (max-width: 768px) {
        .fb-wrapper { padding: 16px; }
        .fb-col-hide { display: none; }
        .fb-title { font-size: 20px; }
    }

    .fb-chart-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .fb-chart-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 18px;
        box-shadow: var(--shadow-sm);
    }

    .fb-chart-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 4px;
    }

    .fb-chart-subtitle {
        font-size: 12px;
        color: var(--text-muted);
        margin-bottom: 14px;
    }

    .fb-chart-box {
        height: 280px;
    }

    @media (max-width: 900px) {
        .fb-chart-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="fb-wrapper">

    {{-- Header --}}
    <div class="fb-header">

        <div class="fb-title-group">
            <h1 class="fb-title">Feedback Pelanggan</h1>
            <p class="fb-subtitle">Kelola dan pantau hasil survei kepuasan pelanggan</p>
        </div>

        <div class="flex items-center gap-2">

            {{-- Buka Form --}}
            <a href="{{ route('feedback.form') }}" target="_blank"
                class="fb-btn fb-btn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                Form Survey
            </a>

            {{-- Copy Link --}}
            <button onclick="copySurveyLink()"
                class="fb-btn fb-btn-ghost">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <rect x="9" y="9" width="13" height="13" rx="2"/>
                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                </svg>
                Copy Link
            </button>

        </div>

    </div>

    {{-- Flash message --}}
    @if(session('success'))
    <div class="fb-flash">
        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Stats strip --}}
    <div class="fb-stats">
        <div class="fb-stat-card">
            <div class="fb-stat-label">Total Feedback</div>
            <div class="fb-stat-value">{{ $feedbacks->total() }}</div>
        </div>
        <div class="fb-stat-card">
            <div class="fb-stat-label">Rata-rata Skor</div>
            <div class="fb-stat-value good">
                {{ $feedbacks->count() ? number_format($feedbacks->avg('rata_rata'), 2) : '—' }}
            </div>
        </div>
        <div class="fb-stat-card">
            <div class="fb-stat-label">Halaman Ini</div>
            <div class="fb-stat-value">{{ $feedbacks->count() }}</div>
        </div>
        <div class="fb-stat-card">
            <div class="fb-stat-label">Halaman</div>
            <div class="fb-stat-value">{{ $feedbacks->currentPage() }} / {{ $feedbacks->lastPage() }}</div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="fb-chart-grid">
        <div class="fb-chart-card">
            <div class="fb-chart-title">Distribusi nilai Feedback per Proyek</div>
            <div class="fb-chart-subtitle">Top 10 proyek berdasarkan nilai rata-rata survey</div>
            <div class="fb-chart-box">
                <canvas id="chartProyek"></canvas>
            </div>
        </div>

        <div class="fb-chart-card">
            <div class="fb-chart-title">Distribusi nilai  Feedback per Produk</div>
            <div class="fb-chart-subtitle">Top 10 produk berdasarkan nilai rata-rata survey</div>
            <div class="fb-chart-box">
                <canvas id="chartProduk"></canvas>
            </div>
        </div>
    </div>

    {{-- Toolbar --}}
    <div class="fb-toolbar">
        <form method="GET" action="{{ route('feedback.index') }}" style="display:flex;gap:8px;flex:1;flex-wrap:wrap;">
            <div class="fb-search-wrap">
                <svg class="fb-search-icon" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
                <input
                    type="text"
                    name="search"
                    class="fb-search"
                    placeholder="Cari nama, perusahaan, proyek..."
                    value="{{ request('search') }}"
                    autocomplete="off"
                >
            </div>
            <button type="submit" class="fb-btn fb-btn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
                Cari
            </button>
            @if(request('search'))
            <a href="{{ route('feedback.index') }}" class="fb-btn fb-btn-ghost">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M18 6 6 18M6 6l12 12"/>
                </svg>
                Reset
            </a>
            @endif
        </form>
    </div>

    {{-- Table card --}}
    <div class="fb-card">
        <table class="fb-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Pelanggan</th>
                    <th class="fb-col-hide">Perusahaan</th>
                    <th class="fb-col-hide">Proyek</th>
                    <th>Skor</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($feedbacks as $index => $f)
                <tr>
                    <td style="color:var(--text-muted);font-size:12px;font-family:'DM Mono',monospace;width:40px;">
                        {{ $feedbacks->firstItem() + $index }}
                    </td>
                    <td>
                        <div class="fb-name-cell">
                            <span class="fb-name">{{ $f->nama_lengkap ?: '—' }}</span>
                            @if($f->jabatan_unit_kerja)
                            <span class="fb-jabatan">{{ $f->jabatan_unit_kerja }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="fb-col-hide" style="color:var(--text-secondary);">{{ $f->perusahaan ?: '—' }}</td>
                    <td class="fb-col-hide" style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $f->proyek }}">
                        {{ $f->proyek }}
                    </td>
                    <td>
                        @php
                            $r = $f->rata_rata;
                            $cls = $r >= 3.5 ? 'fb-badge-green' : ($r >= 2.5 ? 'fb-badge-yellow' : 'fb-badge-red');
                        @endphp
                        <span class="fb-badge {{ $cls }}">{{ number_format($r, 2) }}</span>
                    </td>
                    <td>
                        <div class="fb-actions">
                            <a href="{{ route('feedback.show', $f->id) }}" class="fb-action-btn fb-action-detail">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/>
                                </svg>
                                Detail
                            </a>
                            <a href="{{ route('feedback.pdf', $f->id) }}" class="fb-action-btn fb-action-pdf" target="_blank">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/>
                                </svg>
                                PDF
                            </a>
                            <form method="POST" action="{{ route('feedback.destroy', $f->id) }}" onsubmit="return confirm('Hapus feedback dari {{ addslashes($f->nama_lengkap ?: 'pelanggan ini') }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="fb-action-btn fb-action-delete">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <polyline points="3,6 5,6 21,6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="fb-empty">
                            <div class="fb-empty-icon">📋</div>
                            <div class="fb-empty-text">
                                @if(request('search'))
                                    Tidak ada hasil untuk "{{ request('search') }}"
                                @else
                                    Belum ada data feedback
                                @endif
                            </div>
                            <div class="fb-empty-sub">
                                @if(request('search'))
                                    Coba kata kunci lain atau <a href="{{ route('feedback.index') }}" style="color:var(--accent);">reset pencarian</a>
                                @else
                                    Data akan muncul setelah pelanggan mengisi form survei
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($feedbacks->hasPages())
        <div class="fb-pagination">
            {{ $feedbacks->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
<script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const notyf = new Notyf({
    duration: 2000,
    position: {
        x: 'right',
        y: 'top'
    }
});

function copySurveyLink() {
    const link = "{{ url('/survey-kepuasan') }}";

    navigator.clipboard.writeText(link).then(() => {
        notyf.success('Link survey berhasil disalin');
    }).catch(() => {
        notyf.error('Gagal menyalin link');
    });
}

const proyekLabels = @json($chartProyek->pluck('proyek'));
// const proyekData = @json($chartProyek->pluck('total'));
const proyekData = @json($chartProyek->pluck('rata_rata_skor'));

const produkLabels = @json($chartProduk->pluck('identitas_barang'));
// const produkData = @json($chartProduk->pluck('total'));
const produkData = @json($chartProduk->pluck('rata_rata_skor'));

const chartBaseOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false
        },
        tooltip: {
            backgroundColor: '#111827',
            padding: 10,
            cornerRadius: 8
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            max:4,
            ticks: {
                stepSize:1
            },
            grid: {
                color: '#eef2f7'
            }
        },
        x: {
            grid: {
                display: false
            },
            ticks: {
                maxRotation: 35,
                minRotation: 0
            }
        }
    }
};

new Chart(document.getElementById('chartProyek'), {
    type: 'bar',
    data: {
        labels: proyekLabels,
        datasets: [{
            label: 'Rata-rata Skor',
            data: proyekData,
            backgroundColor: '#1d4ed8',
            borderRadius: 8,
            maxBarThickness: 44
        }]
    },
    options: chartBaseOptions
});

new Chart(document.getElementById('chartProduk'), {
    type: 'doughnut',
    data: {
        labels: produkLabels,
        datasets: [{
            data: produkData,
            backgroundColor: [
                '#1d4ed8',
                '#059669',
                '#d97706',
                '#dc2626',
                '#7c3aed',
                '#0891b2',
                '#65a30d',
                '#be123c',
                '#4338ca',
                '#0f766e'
            ],
            borderWidth: 3,
            borderColor: '#ffffff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    boxWidth: 12,
                    padding: 14,
                    font: {
                        size: 11
                    }
                }
            },
            tooltip: {
                backgroundColor: '#111827',
                padding: 10,
                cornerRadius: 8
            }
        },
        cutout: '62%'
    }
});
</script>
@endsection
