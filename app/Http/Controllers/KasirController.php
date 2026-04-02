<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualan;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class KasirController extends Controller
{
    public function kelolaProduk(): View
    {
        $products = Product::latest('ProdukID')->paginate(12);

        return view('kasir.produk-kelola', [
            'products' => $products,
        ]);
    }

    public function simpanProduk(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'NamaProduk' => ['required', 'string', 'max:255'],
            'Harga' => ['required', 'numeric', 'min:0'],
            'Stok' => ['required', 'integer', 'min:0'],
            'foto_produk' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('foto_produk')) {
            $data['image_path'] = $request->file('foto_produk')->store('produk', 'public');
        }

        Product::create($data);

        return redirect()
            ->route('kasir.produk.index')
            ->with('success', 'Produk baru berhasil ditambahkan.');
    }

    public function updateProduk(Request $request, Product $produk): RedirectResponse
    {
        $data = $request->validate([
            'NamaProduk' => ['required', 'string', 'max:255'],
            'Harga' => ['required', 'numeric', 'min:0'],
            'Stok' => ['required', 'integer', 'min:0'],
            'foto_produk' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('foto_produk')) {
            if (!empty($produk->image_path)) {
                Storage::disk('public')->delete($produk->image_path);
            }

            $data['image_path'] = $request->file('foto_produk')->store('produk', 'public');
        }

        $produk->update($data);

        return redirect()
            ->route('kasir.produk.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function chartData(Request $request): JsonResponse
    {
        $scope = (string) $request->query('scope', 'day');
        $allowedScope = ['hour', 'day', 'month', 'year'];

        if (!in_array($scope, $allowedScope, true)) {
            $scope = 'day';
        }

        if ($scope === 'hour') {
            $start = now()->copy()->subHours(23)->startOfHour();
            $labels = [];
            $keys = [];

            for ($i = 0; $i < 24; $i++) {
                $slot = $start->copy()->addHours($i);
                $keys[] = $slot->format('Y-m-d H:00:00');
                $labels[] = $slot->format('H:i');
            }

            $salesRows = Penjualan::selectRaw("DATE_FORMAT(created_at, '%Y-%m-%d %H:00:00') as grp, COUNT(*) as total_transaksi, COALESCE(SUM(CASE WHEN IsReturned = 0 THEN TotalHarga ELSE 0 END), 0) as total_omzet")
                ->where('created_at', '>=', $start)
                ->groupBy('grp')
                ->get()
                ->keyBy('grp');

            $customerRows = Pelanggan::selectRaw("DATE_FORMAT(created_at, '%Y-%m-%d %H:00:00') as grp, COUNT(*) as total_pelanggan")
                ->where('created_at', '>=', $start)
                ->groupBy('grp')
                ->pluck('total_pelanggan', 'grp');

            $series = [
                'omzet' => collect($keys)->map(fn ($key) => (float) ($salesRows[$key]->total_omzet ?? 0))->all(),
                'transaksi' => collect($keys)->map(fn ($key) => (int) ($salesRows[$key]->total_transaksi ?? 0))->all(),
                'pelangganBaru' => collect($keys)->map(fn ($key) => (int) ($customerRows[$key] ?? 0))->all(),
            ];

            return response()->json(['scope' => $scope, 'labels' => $labels, 'series' => $series]);
        }

        if ($scope === 'day') {
            $start = now()->copy()->subDays(13)->startOfDay();
            $labels = [];
            $keys = [];

            for ($i = 0; $i < 14; $i++) {
                $slot = $start->copy()->addDays($i);
                $keys[] = $slot->format('Y-m-d');
                $labels[] = $slot->format('d M');
            }

            $salesRows = Penjualan::selectRaw('TanggalPenjualan as grp, COUNT(*) as total_transaksi, COALESCE(SUM(CASE WHEN IsReturned = 0 THEN TotalHarga ELSE 0 END), 0) as total_omzet')
                ->whereDate('TanggalPenjualan', '>=', $start->toDateString())
                ->groupBy('grp')
                ->get()
                ->keyBy('grp');

            $customerRows = Pelanggan::selectRaw("DATE(created_at) as grp, COUNT(*) as total_pelanggan")
                ->whereDate('created_at', '>=', $start->toDateString())
                ->groupBy('grp')
                ->pluck('total_pelanggan', 'grp');

            $series = [
                'omzet' => collect($keys)->map(fn ($key) => (float) ($salesRows[$key]->total_omzet ?? 0))->all(),
                'transaksi' => collect($keys)->map(fn ($key) => (int) ($salesRows[$key]->total_transaksi ?? 0))->all(),
                'pelangganBaru' => collect($keys)->map(fn ($key) => (int) ($customerRows[$key] ?? 0))->all(),
            ];

            return response()->json(['scope' => $scope, 'labels' => $labels, 'series' => $series]);
        }

        if ($scope === 'month') {
            $start = now()->copy()->startOfMonth()->subMonths(11);
            $labels = [];
            $keys = [];

            for ($i = 0; $i < 12; $i++) {
                $slot = $start->copy()->addMonths($i);
                $keys[] = $slot->format('Y-m');
                $labels[] = $slot->format('M Y');
            }

            $salesRows = Penjualan::selectRaw("DATE_FORMAT(TanggalPenjualan, '%Y-%m') as grp, COUNT(*) as total_transaksi, COALESCE(SUM(CASE WHEN IsReturned = 0 THEN TotalHarga ELSE 0 END), 0) as total_omzet")
                ->whereDate('TanggalPenjualan', '>=', $start->toDateString())
                ->groupBy('grp')
                ->get()
                ->keyBy('grp');

            $customerRows = Pelanggan::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as grp, COUNT(*) as total_pelanggan")
                ->whereDate('created_at', '>=', $start->toDateString())
                ->groupBy('grp')
                ->pluck('total_pelanggan', 'grp');

            $series = [
                'omzet' => collect($keys)->map(fn ($key) => (float) ($salesRows[$key]->total_omzet ?? 0))->all(),
                'transaksi' => collect($keys)->map(fn ($key) => (int) ($salesRows[$key]->total_transaksi ?? 0))->all(),
                'pelangganBaru' => collect($keys)->map(fn ($key) => (int) ($customerRows[$key] ?? 0))->all(),
            ];

            return response()->json(['scope' => $scope, 'labels' => $labels, 'series' => $series]);
        }

        $startYear = now()->year - 4;
        $labels = [];
        $keys = [];

        for ($year = $startYear; $year <= now()->year; $year++) {
            $keys[] = (string) $year;
            $labels[] = (string) $year;
        }

        $salesRows = Penjualan::selectRaw("YEAR(TanggalPenjualan) as grp, COUNT(*) as total_transaksi, COALESCE(SUM(CASE WHEN IsReturned = 0 THEN TotalHarga ELSE 0 END), 0) as total_omzet")
            ->whereYear('TanggalPenjualan', '>=', $startYear)
            ->groupBy('grp')
            ->get()
            ->keyBy('grp');

        $customerRows = Pelanggan::selectRaw("YEAR(created_at) as grp, COUNT(*) as total_pelanggan")
            ->whereYear('created_at', '>=', $startYear)
            ->groupBy('grp')
            ->pluck('total_pelanggan', 'grp');

        $series = [
            'omzet' => collect($keys)->map(fn ($key) => (float) ($salesRows[$key]->total_omzet ?? 0))->all(),
            'transaksi' => collect($keys)->map(fn ($key) => (int) ($salesRows[$key]->total_transaksi ?? 0))->all(),
            'pelangganBaru' => collect($keys)->map(fn ($key) => (int) ($customerRows[$key] ?? 0))->all(),
        ];

        return response()->json(['scope' => $scope, 'labels' => $labels, 'series' => $series]);
    }

    public function dashboard(): View
    {
        $today = now()->toDateString();
        $startWeek = now()->startOfWeek()->toDateString();

        $totalProduk = Product::count();
        $totalPelanggan = Pelanggan::count();

        $transaksiHariIni = Penjualan::whereDate('TanggalPenjualan', $today)->count();
        $omzetHariIni = (float) Penjualan::whereDate('TanggalPenjualan', $today)
            ->where('IsReturned', false)
            ->sum('TotalHarga');
        $returnHariIni = Penjualan::whereDate('ReturnedAt', $today)
            ->where('IsReturned', true)
            ->count();

        $ringkasanMingguan = Penjualan::selectRaw('TanggalPenjualan, COUNT(*) as jumlah_transaksi, COALESCE(SUM(CASE WHEN IsReturned = 0 THEN TotalHarga ELSE 0 END), 0) as omzet')
            ->whereDate('TanggalPenjualan', '>=', $startWeek)
            ->groupBy('TanggalPenjualan')
            ->orderBy('TanggalPenjualan')
            ->get();

        $stokMenipis = Product::where('Stok', '<=', 5)
            ->orderBy('Stok')
            ->limit(8)
            ->get();

        $produkTerlaris = DetailPenjualan::query()
            ->join('penjualan', 'penjualan.PenjualanID', '=', 'detailpenjualan.PenjualanID')
            ->join('produk', 'produk.ProdukID', '=', 'detailpenjualan.ProdukID')
            ->where('penjualan.IsReturned', false)
            ->selectRaw('produk.ProdukID, produk.NamaProduk, SUM(detailpenjualan.JumlahProduk) as total_terjual, SUM(detailpenjualan.Subtotal) as total_nilai')
            ->groupBy('produk.ProdukID', 'produk.NamaProduk')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        $transaksiTerbaru = Penjualan::with('pelanggan')
            ->latest('PenjualanID')
            ->limit(8)
            ->get();

        return view('kasir.dashboard', [
            'totalProduk' => $totalProduk,
            'totalPelanggan' => $totalPelanggan,
            'transaksiHariIni' => $transaksiHariIni,
            'omzetHariIni' => $omzetHariIni,
            'returnHariIni' => $returnHariIni,
            'ringkasanMingguan' => $ringkasanMingguan,
            'stokMenipis' => $stokMenipis,
            'produkTerlaris' => $produkTerlaris,
            'transaksiTerbaru' => $transaksiTerbaru,
        ]);
    }

    public function faq(): View
    {
        return view('kasir.faq');
    }

    public function index(): View
    {
        $query = Product::query();

        $search = trim((string) request('q', ''));
        $sort = (string) request('sort', 'terbaru');
        $allowedSort = ['terbaru', 'harga_termurah', 'harga_tertinggi', 'stok'];
        if (!in_array($sort, $allowedSort, true)) {
            $sort = 'terbaru';
        }

        $minHarga = request('min_harga');
        $maxHarga = request('max_harga');
        $minHargaValue = is_numeric($minHarga) ? max(0, (float) $minHarga) : null;
        $maxHargaValue = is_numeric($maxHarga) ? max(0, (float) $maxHarga) : null;

        if ($minHargaValue !== null && $maxHargaValue !== null && $minHargaValue > $maxHargaValue) {
            [$minHargaValue, $maxHargaValue] = [$maxHargaValue, $minHargaValue];
        }

        if (!empty($search)) {
            $query->where('NamaProduk', 'like', '%' . $search . '%');
        }

        if ($minHargaValue !== null) {
            $query->where('Harga', '>=', $minHargaValue);
        }

        if ($maxHargaValue !== null) {
            $query->where('Harga', '<=', $maxHargaValue);
        }

        if ($sort === 'harga_termurah') {
            $query->orderBy('Harga');
        } elseif ($sort === 'harga_tertinggi') {
            $query->orderByDesc('Harga');
        } elseif ($sort === 'stok') {
            $query->orderByDesc('Stok');
        } else {
            $query->latest('ProdukID');
        }

        $produk = $query->get();
        $pelanggan = Pelanggan::orderBy('NamaPelanggan')->get();

        $keranjang = $this->ambilKeranjang(Product::orderBy('NamaProduk')->get());

        return view('kasir.index', [
            'products' => $produk,
            'customers' => $pelanggan,
            'keranjang' => $keranjang,
            'total' => collect($keranjang)->sum('subtotal'),
            'filter' => [
                'q' => $search,
                'sort' => $sort,
                'min_harga' => $minHargaValue,
                'max_harga' => $maxHargaValue,
            ],
            'jumlahProdukAktif' => Product::count(),
        ]);
    }

    public function tambahKeranjang(Request $request): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'produk_id' => ['required', 'exists:produk,ProdukID'],
            'jumlah' => ['required', 'integer', 'min:1'],
        ]);

        $produk = Product::findOrFail((int) $data['produk_id']);
        $cart = session()->get('kasir_cart', []);
        $produkId = (int) $data['produk_id'];
        $jumlahBaru = ($cart[$produkId] ?? 0) + (int) $data['jumlah'];

        if ($jumlahBaru > (int) $produk->Stok) {
            $message = 'Jumlah melebihi stok untuk produk: ' . $produk->NamaProduk;

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => $message], 422);
            }

            return back()->with('error', $message);
        }

        $cart[$produkId] = $jumlahBaru;

        session()->put('kasir_cart', $cart);

        if ($request->expectsJson() || $request->ajax()) {
            return $this->ajaxCartResponse('Produk ditambahkan ke keranjang kasir.');
        }

        return back()->with('success', 'Produk ditambahkan ke keranjang kasir.');
    }

    public function updateKeranjang(Request $request, Product $produk): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'jumlah' => ['required', 'integer', 'min:1'],
        ]);

        if ((int) $data['jumlah'] > (int) $produk->Stok) {
            $message = 'Jumlah melebihi stok untuk produk: ' . $produk->NamaProduk;

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => $message], 422);
            }

            return back()->with('error', $message);
        }

        $cart = session()->get('kasir_cart', []);
        $cart[$produk->ProdukID] = (int) $data['jumlah'];

        session()->put('kasir_cart', $cart);

        if ($request->expectsJson() || $request->ajax()) {
            return $this->ajaxCartResponse('Jumlah produk pada keranjang diperbarui.');
        }

        return back()->with('success', 'Jumlah produk pada keranjang diperbarui.');
    }

    public function hapusKeranjang(Request $request, Product $produk): RedirectResponse|JsonResponse
    {
        $cart = session()->get('kasir_cart', []);
        unset($cart[$produk->ProdukID]);

        session()->put('kasir_cart', $cart);

        if ($request->expectsJson() || $request->ajax()) {
            return $this->ajaxCartResponse('Produk dihapus dari keranjang.');
        }

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    public function simpanTransaksi(Request $request): RedirectResponse
    {
        $cart = session()->get('kasir_cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Keranjang kosong, transaksi belum bisa disimpan.');
        }

        $data = $request->validate([
            'pelanggan_id' => ['nullable', 'exists:pelanggan,PelangganID'],
            'nama_pelanggan' => ['nullable', 'string', 'max:255', 'required_without:pelanggan_id'],
            'alamat' => ['nullable', 'string'],
            'nomor_telepon' => ['nullable', 'string', 'max:15'],
        ]);

        $penjualan = DB::transaction(function () use ($cart, $data) {
            $pelanggan = null;

            if (!empty($data['pelanggan_id'])) {
                $pelanggan = Pelanggan::find($data['pelanggan_id']);
            } else {
                $pelanggan = Pelanggan::create([
                    'NamaPelanggan' => $data['nama_pelanggan'],
                    'Alamat' => $data['alamat'] ?? null,
                    'NomorTelepon' => $data['nomor_telepon'] ?? null,
                ]);
            }

            $produkMap = Product::whereIn('ProdukID', array_keys($cart))
                ->lockForUpdate()
                ->get()
                ->keyBy('ProdukID');

            $penjualan = Penjualan::create([
                'TanggalPenjualan' => now()->toDateString(),
                'TotalHarga' => 0,
                'PelangganID' => $pelanggan?->PelangganID,
            ]);

            $totalHarga = 0;

            foreach ($cart as $produkId => $jumlah) {
                $produk = $produkMap->get($produkId);

                if (!$produk) {
                    abort(422, 'Produk tidak ditemukan pada saat simpan transaksi.');
                }

                if ($produk->Stok < $jumlah) {
                    abort(422, 'Stok tidak mencukupi untuk produk: ' . $produk->NamaProduk);
                }

                $subtotal = (float) $produk->Harga * (int) $jumlah;
                $totalHarga += $subtotal;

                DetailPenjualan::create([
                    'PenjualanID' => $penjualan->PenjualanID,
                    'ProdukID' => $produk->ProdukID,
                    'JumlahProduk' => (int) $jumlah,
                    'Subtotal' => $subtotal,
                ]);

                $produk->decrement('Stok', (int) $jumlah);
            }

            $penjualan->update(['TotalHarga' => $totalHarga]);

            return $penjualan;
        });

        session()->forget('kasir_cart');

        return redirect()
            ->route('kasir.riwayat')
            ->with('success', 'Transaksi berhasil disimpan. Nomor transaksi: #' . $penjualan->PenjualanID);
    }

    public function riwayat(): View
    {
        $salesHistory = Penjualan::with(['pelanggan', 'detail.produk'])
            ->latest('PenjualanID')
            ->paginate(10);

        return view('kasir.riwayat', ['salesHistory' => $salesHistory]);
    }

    public function returnTransaksi(Penjualan $penjualan): RedirectResponse
    {
        if ($penjualan->IsReturned) {
            return back()->with('error', 'Transaksi ini sudah direturn sebelumnya.');
        }

        DB::transaction(function () use ($penjualan): void {
            $penjualan->load(['detail.produk']);

            foreach ($penjualan->detail as $detail) {
                if ($detail->produk) {
                    $detail->produk->increment('Stok', (int) $detail->JumlahProduk);
                }
            }

            $penjualan->update([
                'IsReturned' => true,
                'ReturnedAt' => now(),
            ]);
        });

        return back()->with('success', 'Transaksi berhasil direturn dan stok produk dikembalikan.');
    }

    public function hapusTransaksi(Penjualan $penjualan): RedirectResponse
    {
        DB::transaction(function () use ($penjualan): void {
            $penjualan->load(['detail.produk']);

            if (!$penjualan->IsReturned) {
                foreach ($penjualan->detail as $detail) {
                    if ($detail->produk) {
                        $detail->produk->increment('Stok', (int) $detail->JumlahProduk);
                    }
                }
            }

            $penjualan->delete();
        });

        return back()->with('success', 'Transaksi berhasil dihapus.');
    }

    /**
     * Bentuk data keranjang agar siap ditampilkan pada dashboard kasir.
     */
    private function ambilKeranjang($produk): array
    {
        $cart = session()->get('kasir_cart', []);
        $produkMap = $produk->keyBy('ProdukID');

        $items = [];

        foreach ($cart as $produkId => $jumlah) {
            $item = $produkMap->get($produkId);

            if (!$item) {
                continue;
            }

            $harga = (float) $item->Harga;
            $subtotal = $harga * (int) $jumlah;

            $items[] = [
                'produk' => $item,
                'jumlah' => (int) $jumlah,
                'subtotal' => $subtotal,
            ];
        }

        return $items;
    }

    /**
     * Balikan payload JSON untuk update panel keranjang tanpa reload halaman.
     */
    private function ajaxCartResponse(string $message): JsonResponse
    {
        $keranjang = $this->ambilKeranjang(Product::orderBy('NamaProduk')->get());
        $customers = Pelanggan::orderBy('NamaPelanggan')->get();
        $total = collect($keranjang)->sum('subtotal');

        $cartHtml = view('kasir.partials.cart-panel', [
            'keranjang' => $keranjang,
            'customers' => $customers,
            'total' => $total,
        ])->render();

        return response()->json([
            'message' => $message,
            'cart_html' => $cartHtml,
            'total' => $total,
            'items' => count($keranjang),
        ]);
    }
}
