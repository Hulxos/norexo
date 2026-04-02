@extends('layout-kasir')

@section('title', 'Dashboard Kasir - NoreXo')

@section('content')
<style>
    .dash-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 14px;
    }

    .kpi-card {
        background: #fff;
        border: 1px solid #eadede;
        border-radius: 14px;
        padding: 14px;
    }

    .kpi-label {
        font-size: 12px;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .kpi-value {
        margin-top: 8px;
        font-size: 30px;
        line-height: 1;
        font-family: 'Sora', sans-serif;
        color: #111827;
    }

    .kpi-sub {
        margin-top: 7px;
        font-size: 12px;
        color: #6b7280;
    }

    .content-grid {
        display: grid;
        grid-template-columns: 1.4fr 1fr;
        gap: 12px;
    }

    .panel {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 14px;
    }

    .panel h3 {
        margin: 0 0 10px;
        font-size: 17px;
        font-weight: 800;
    }

    .chart-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 10px;
    }

    .chart-actions {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .chart-btn {
        border: 1px solid #d9dce3;
        background: #fff;
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 12px;
        cursor: pointer;
        font-weight: 700;
        color: #374151;
    }

    .chart-btn.active {
        color: #fff;
        border-color: #dc2626;
        background: linear-gradient(90deg, #dc2626, #ef4444);
    }

    .chart-canvas {
        width: 100%;
        height: 300px;
        border: 1px solid #f1dede;
        border-radius: 12px;
        background: linear-gradient(180deg, #fff6f6 0%, #fff 100%);
    }

    .chart-wrap {
        position: relative;
    }

    .chart-tooltip {
        position: absolute;
        min-width: 210px;
        max-width: 260px;
        z-index: 5;
        background: #111827;
        color: #fff;
        border-radius: 10px;
        padding: 10px;
        font-size: 12px;
        line-height: 1.5;
        pointer-events: none;
        opacity: 0;
        transform: translateY(4px);
        transition: opacity 0.15s ease, transform 0.15s ease;
    }

    .chart-tooltip.show {
        opacity: 1;
        transform: translateY(0);
    }

    .legend {
        margin-top: 8px;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .legend-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: #4b5563;
    }

    .legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 999px;
    }

    .chart-insight {
        margin-top: 10px;
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 8px;
    }

    .insight-box {
        border: 1px solid #eceff3;
        border-radius: 10px;
        background: #fbfdff;
        padding: 10px;
    }

    .insight-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #6b7280;
    }

    .insight-value {
        margin-top: 6px;
        font-size: 18px;
        font-weight: 800;
        color: #111827;
    }

    .insight-sub {
        margin-top: 4px;
        font-size: 12px;
        color: #6b7280;
    }

    .list {
        display: grid;
        gap: 8px;
    }

    .item {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        padding: 10px;
        border: 1px solid #edf0f3;
        border-radius: 10px;
        background: #fbfdff;
        font-size: 13px;
    }

    .item strong {
        color: #111827;
    }

    .badge {
        display: inline-flex;
        border-radius: 8px;
        padding: 5px 9px;
        font-size: 11px;
        font-weight: 700;
    }

    .badge-danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-success {
        background: #dcfce7;
        color: #166534;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .table th,
    .table td {
        text-align: left;
        border-bottom: 1px solid #eef1f4;
        padding: 8px 6px;
    }

    .empty {
        color: #6b7280;
        font-size: 13px;
        padding: 8px 0;
    }

    @media (max-width: 1100px) {
        .dash-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .content-grid {
            grid-template-columns: 1fr;
        }

        .chart-insight {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 640px) {
        .dash-grid {
            grid-template-columns: 1fr;
        }

        .chart-insight {
            grid-template-columns: 1fr;
        }
    }
</style>

<section class="dash-grid">
    <article class="kpi-card">
        <div class="kpi-label">Omzet Hari Ini</div>
        <div class="kpi-value">Rp {{ number_format($omzetHariIni, 0, ',', '.') }}</div>
        <div class="kpi-sub">Hanya transaksi aktif (belum return).</div>
    </article>

    <article class="kpi-card">
        <div class="kpi-label">Transaksi Hari Ini</div>
        <div class="kpi-value">{{ $transaksiHariIni }}</div>
        <div class="kpi-sub">Termasuk yang berstatus return.</div>
    </article>

    <article class="kpi-card">
        <div class="kpi-label">Total Produk</div>
        <div class="kpi-value">{{ $totalProduk }}</div>
        <div class="kpi-sub">Produk aktif di katalog kasir.</div>
    </article>

    <article class="kpi-card">
        <div class="kpi-label">Return Hari Ini</div>
        <div class="kpi-value">{{ $returnHariIni }}</div>
        <div class="kpi-sub">Jumlah transaksi yang direturn.</div>
    </article>
</section>

<section class="content-grid">
    <div class="panel">
        <div class="chart-head">
            <h3>Grafik Penjualan Dashboard</h3>
            <div class="chart-actions">
                <button type="button" class="chart-btn" data-scope="hour">Jam</button>
                <button type="button" class="chart-btn active" data-scope="day">Hari</button>
                <button type="button" class="chart-btn" data-scope="month">Bulan</button>
                <button type="button" class="chart-btn" data-scope="year">Tahun</button>
            </div>
        </div>

        <div class="chart-wrap">
            <svg id="dashboard-sales-chart" class="chart-canvas" viewBox="0 0 960 300" preserveAspectRatio="none" role="img" aria-label="Grafik penjualan dashboard"></svg>
            <div id="dashboard-chart-tooltip" class="chart-tooltip"></div>
        </div>

        <div class="legend">
            <span class="legend-item"><span class="legend-dot" style="background:#dc2626;"></span> Omzet</span>
            <span class="legend-item"><span class="legend-dot" style="background:#2563eb;"></span> Jumlah Transaksi</span>
            <span class="legend-item"><span class="legend-dot" style="background:#16a34a;"></span> Pelanggan Baru</span>
        </div>

        <div class="chart-insight">
            <article class="insight-box">
                <div class="insight-label">Total Periode</div>
                <div id="insight-total" class="insight-value">Rp 0</div>
                <div class="insight-sub">Akumulasi omzet dari rentang dipilih.</div>
            </article>
            <article class="insight-box">
                <div class="insight-label">Rata-rata</div>
                <div id="insight-average" class="insight-value">Rp 0</div>
                <div class="insight-sub">Rata-rata omzet per titik waktu.</div>
            </article>
            <article class="insight-box">
                <div class="insight-label">Puncak Tertinggi</div>
                <div id="insight-peak" class="insight-value">Rp 0</div>
                <div id="insight-peak-sub" class="insight-sub">-</div>
            </article>
            <article class="insight-box">
                <div class="insight-label">Titik Terendah</div>
                <div id="insight-low" class="insight-value">Rp 0</div>
                <div id="insight-low-sub" class="insight-sub">-</div>
            </article>
        </div>
    </div>

    <div class="panel">
        <h3>Stok Menipis</h3>
        <div class="list">
            @forelse ($stokMenipis as $produk)
                <div class="item">
                    <div>
                        <strong>{{ $produk->NamaProduk }}</strong><br>
                        <span style="color: #6b7280;">ID {{ $produk->ProdukID }}</span>
                    </div>
                    <span class="badge badge-danger">Stok {{ $produk->Stok }}</span>
                </div>
            @empty
                <div class="empty">Stok aman. Tidak ada produk di bawah batas minimum.</div>
            @endforelse
        </div>
    </div>
</section>

<section class="content-grid" style="margin-top: 12px;">
    <div class="panel">
        <h3>Produk Terlaris</h3>
        @if ($produkTerlaris->isEmpty())
            <div class="empty">Belum ada transaksi aktif untuk dihitung.</div>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Terjual</th>
                        <th>Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($produkTerlaris as $produk)
                        <tr>
                            <td>{{ $produk->NamaProduk }}</td>
                            <td>{{ $produk->total_terjual }}</td>
                            <td>Rp {{ number_format($produk->total_nilai, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="panel">
        <h3>Transaksi Terbaru</h3>
        <div class="list">
            @forelse ($transaksiTerbaru as $trx)
                <div class="item">
                    <div>
                        <strong>#{{ $trx->PenjualanID }} • {{ $trx->pelanggan?->NamaPelanggan ?? 'Umum' }}</strong><br>
                        <span style="color: #6b7280;">{{ $trx->TanggalPenjualan->format('d-m-Y') }}</span>
                    </div>
                    @if ($trx->IsReturned)
                        <span class="badge badge-danger">Return</span>
                    @else
                        <span class="badge badge-success">Aktif</span>
                    @endif
                </div>
            @empty
                <div class="empty">Belum ada transaksi.</div>
            @endforelse
        </div>
    </div>
</section>

<script>
    (function () {
        const chartSvg = document.getElementById('dashboard-sales-chart');
        const chartTooltip = document.getElementById('dashboard-chart-tooltip');
        const buttons = Array.from(document.querySelectorAll('.chart-btn'));

        const totalEl = document.getElementById('insight-total');
        const avgEl = document.getElementById('insight-average');
        const peakEl = document.getElementById('insight-peak');
        const peakSubEl = document.getElementById('insight-peak-sub');
        const lowEl = document.getElementById('insight-low');
        const lowSubEl = document.getElementById('insight-low-sub');

        function formatRupiah(value) {
            return `Rp ${Math.round(value).toLocaleString('id-ID')}`;
        }

        const seriesConfig = [
            { key: 'omzet', color: '#dc2626' },
            { key: 'transaksi', color: '#2563eb' },
            { key: 'pelangganBaru', color: '#16a34a' },
        ];

        let chartState = {
            labels: [],
            series: {
                omzet: [],
                transaksi: [],
                pelangganBaru: [],
            },
            points: {
                omzet: [],
                transaksi: [],
                pelangganBaru: [],
            },
            bounds: {
                left: 44,
                right: 916,
                top: 24,
                bottom: 276,
            },
        };

        function drawChart(labels, series) {
            if (!chartSvg || !labels.length) {
                return;
            }

            const width = 960;
            const height = 300;
            const padX = 44;
            const padY = 24;
            const innerW = width - (padX * 2);
            const innerH = height - (padY * 2);

            const flatValues = [
                ...(series.omzet ?? []),
                ...(series.transaksi ?? []),
                ...(series.pelangganBaru ?? []),
            ];
            const max = Math.max(...flatValues, 1);
            const min = 0;
            const range = Math.max(max - min, 1);

            const seriesPoints = {};
            for (const config of seriesConfig) {
                const values = series[config.key] ?? [];
                seriesPoints[config.key] = values.map((value, idx) => {
                    const x = padX + (innerW * (labels.length <= 1 ? 0 : idx / (labels.length - 1)));
                    const y = padY + innerH - (((value - min) / range) * innerH);
                    return { x, y, value, label: labels[idx] };
                });
            }

            const omzetLine = seriesPoints.omzet
                .map((p, idx) => (idx === 0 ? `M ${p.x} ${p.y}` : `L ${p.x} ${p.y}`))
                .join(' ');
            const omzetArea = `${omzetLine} L ${padX + innerW} ${height - padY} L ${padX} ${height - padY} Z`;

            const step = Math.max(1, Math.floor(labels.length / 6));
            const tickIndexes = [];
            for (let i = 0; i < labels.length; i += step) {
                tickIndexes.push(i);
            }
            if (tickIndexes[tickIndexes.length - 1] !== labels.length - 1) {
                tickIndexes.push(labels.length - 1);
            }

            let guides = '';
            for (let i = 0; i <= 4; i++) {
                const y = padY + (innerH / 4) * i;
                guides += `<line x1="${padX}" y1="${y}" x2="${padX + innerW}" y2="${y}" stroke="#f0d7d7" stroke-width="1" />`;
            }

            const ticks = tickIndexes.map((idx) => {
                const p = seriesPoints.omzet[idx];
                return `<text x="${p.x}" y="286" text-anchor="middle" fill="#6b7280" font-size="11">${labels[idx]}</text>`;
            }).join('');

            const area = `<path d="${omzetArea}" fill="url(#dashboardArea)" />`;
            const lines = seriesConfig.map((config) => {
                const path = seriesPoints[config.key]
                    .map((p, idx) => (idx === 0 ? `M ${p.x} ${p.y}` : `L ${p.x} ${p.y}`))
                    .join(' ');
                return `<path d="${path}" fill="none" stroke="${config.color}" stroke-width="${config.key === 'omzet' ? 3 : 2.2}" stroke-linecap="round" stroke-linejoin="round" />`;
            }).join('');

            const dots = seriesConfig.map((config) => {
                return seriesPoints[config.key]
                    .map((p) => `<circle cx="${p.x}" cy="${p.y}" r="${config.key === 'omzet' ? 3.4 : 2.8}" fill="${config.color}" />`)
                    .join('');
            }).join('');

            chartSvg.innerHTML = `
                <defs>
                    <linearGradient id="dashboardArea" x1="0" x2="0" y1="0" y2="1">
                        <stop offset="0%" stop-color="#ef4444" stop-opacity="0.45" />
                        <stop offset="100%" stop-color="#ef4444" stop-opacity="0.04" />
                    </linearGradient>
                </defs>
                ${guides}
                ${area}
                ${lines}
                ${dots}
                ${ticks}
            `;

            chartState = {
                labels,
                series,
                points: seriesPoints,
                bounds: {
                    left: padX,
                    right: padX + innerW,
                    top: padY,
                    bottom: height - padY,
                },
            };
        }

        function updateInsights(labels, series) {
            const omzetValues = series.omzet ?? [];
            const transaksiValues = series.transaksi ?? [];
            const pelangganValues = series.pelangganBaru ?? [];

            const totalOmzet = omzetValues.reduce((sum, v) => sum + v, 0);
            const totalTransaksi = transaksiValues.reduce((sum, v) => sum + v, 0);
            const totalPelangganBaru = pelangganValues.reduce((sum, v) => sum + v, 0);

            const avgOmzet = omzetValues.length ? totalOmzet / omzetValues.length : 0;

            let peakIndex = 0;
            let lowIndex = 0;
            omzetValues.forEach((value, idx) => {
                if (value > omzetValues[peakIndex]) {
                    peakIndex = idx;
                }
                if (value < omzetValues[lowIndex]) {
                    lowIndex = idx;
                }
            });

            totalEl.textContent = formatRupiah(totalOmzet);
            avgEl.textContent = `${Math.round(totalTransaksi).toLocaleString('id-ID')} trx / ${Math.round(totalPelangganBaru).toLocaleString('id-ID')} pelanggan`;
            peakEl.textContent = formatRupiah(omzetValues[peakIndex] ?? 0);
            peakSubEl.textContent = `Puncak pada ${labels[peakIndex] ?? '-'}`;
            lowEl.textContent = formatRupiah(avgOmzet);
            lowSubEl.textContent = `Rata-rata omzet per titik waktu`;
        }

        async function loadScope(scope) {
            const response = await fetch(`{{ route('kasir.chart-data') }}?scope=${encodeURIComponent(scope)}`, {
                headers: {
                    'Accept': 'application/json',
                },
            });

            const payload = await response.json().catch(() => null);

            if (!response.ok || !payload || !Array.isArray(payload.labels) || !payload.series) {
                throw new Error('Gagal memuat data grafik dashboard.');
            }

            drawChart(payload.labels, payload.series);
            updateInsights(payload.labels, payload.series);
        }

        if (chartSvg) {
            chartSvg.addEventListener('mousemove', (event) => {
                if (!chartTooltip || chartState.labels.length === 0) {
                    return;
                }

                const rect = chartSvg.getBoundingClientRect();
                const ratioX = 960 / rect.width;
                const x = (event.clientX - rect.left) * ratioX;

                const { left, right } = chartState.bounds;
                const clamped = Math.max(left, Math.min(right, x));
                const percent = (clamped - left) / Math.max(right - left, 1);
                const index = Math.round(percent * (chartState.labels.length - 1));

                const label = chartState.labels[index] ?? '-';
                const omzet = chartState.series.omzet[index] ?? 0;
                const transaksi = chartState.series.transaksi[index] ?? 0;
                const pelangganBaru = chartState.series.pelangganBaru[index] ?? 0;

                chartTooltip.innerHTML = `
                    <strong>${label}</strong><br>
                    <span style="color:#fca5a5;">Omzet:</span> ${formatRupiah(omzet)}<br>
                    <span style="color:#93c5fd;">Jumlah Transaksi:</span> ${Math.round(transaksi).toLocaleString('id-ID')}<br>
                    <span style="color:#86efac;">Pelanggan Baru:</span> ${Math.round(pelangganBaru).toLocaleString('id-ID')}
                `;

                const tooltipX = event.clientX - rect.left + 12;
                const tooltipY = event.clientY - rect.top + 12;
                chartTooltip.style.left = `${tooltipX}px`;
                chartTooltip.style.top = `${tooltipY}px`;
                chartTooltip.classList.add('show');
            });

            chartSvg.addEventListener('mouseleave', () => {
                chartTooltip?.classList.remove('show');
            });
        }

        buttons.forEach((btn) => {
            btn.addEventListener('click', () => {
                buttons.forEach((item) => item.classList.remove('active'));
                btn.classList.add('active');

                loadScope(btn.dataset.scope).catch(() => {
                    totalEl.textContent = 'Rp 0';
                    avgEl.textContent = 'Rp 0';
                    peakEl.textContent = 'Rp 0';
                    lowEl.textContent = 'Rp 0';
                    peakSubEl.textContent = 'Data tidak tersedia';
                    lowSubEl.textContent = 'Data tidak tersedia';
                });
            });
        });

        loadScope('day').catch(() => {
            totalEl.textContent = 'Rp 0';
            avgEl.textContent = 'Rp 0';
            peakEl.textContent = 'Rp 0';
            lowEl.textContent = 'Rp 0';
            peakSubEl.textContent = 'Data tidak tersedia';
            lowSubEl.textContent = 'Data tidak tersedia';
        });
    })();
</script>
@endsection
