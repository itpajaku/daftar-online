<?php

namespace App\Livewire;

use App\Models\Identity;
use App\Models\WebHookLog;
use App\Service\HashId;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Dashboard extends Component
{
    public $selectedMonth;

    public $selectedYear;

    public function mount()
    {
        $this->selectedMonth = date('m');
        $this->selectedYear = date('Y');
    }

    public function updatedSelectedMonth()
    {
        $this->dispatch('update-chart', data: $this->chartData);
    }

    public function updatedSelectedYear()
    {
        $this->dispatch('update-chart', data: $this->chartData);
    }

    #[Computed]
    public function stats()
    {
        $total = Identity::count();
        $verified = Identity::where('status', 'verified')->orWhereHas('ecourt_account')->count();
        $rejected = Identity::where('status', 'rejected')->count();
        $pending = Identity::where(function ($q) {
            $q->where('status', 'pending')->orWhereNull('status');
        })->doesntHave('ecourt_account')->count();

        $today = Identity::whereDate('created_at', Carbon::today())->count();
        $thisMonth = Identity::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();

        $verificationRate = $total > 0 ? round(($verified / $total) * 100, 1) : 0;

        return [
            'total' => $total,
            'verified' => $verified,
            'rejected' => $rejected,
            'pending' => $pending,
            'today' => $today,
            'this_month' => $thisMonth,
            'verification_rate' => $verificationRate,
        ];
    }

    #[Computed]
    public function statusDistribution()
    {
        $stats = $this->stats;

        return [
            'series' => [
                $stats['pending'],
                $stats['verified'],
                $stats['rejected'],
            ],
            'labels' => ['Menunggu Verifikasi', 'Terverifikasi', 'Ditolak'],
        ];
    }

    #[Computed]
    public function recentPendingIdentities()
    {
        return Identity::with(['bank_account', 'ecourt_account'])
            ->where(function ($q) {
                $q->where('status', 'pending')->orWhereNull('status');
            })
            ->doesntHave('ecourt_account')
            ->latest()
            ->take(5)
            ->get();
    }

    #[Computed]
    public function webhookSummary()
    {
        $totalLogs = WebHookLog::count();
        $successLogs = WebHookLog::where('success', true)->count();
        $failedLogs = WebHookLog::where('success', false)->count();
        $recentLogs = WebHookLog::with('webhook')->latest()->take(4)->get();

        return [
            'total' => $totalLogs,
            'success' => $successLogs,
            'failed' => $failedLogs,
            'recent' => $recentLogs,
        ];
    }

    #[Computed]
    public function chartData()
    {
        // Mendapatkan jumlah hari dalam bulan dan tahun yang dipilih
        $daysInMonth = Carbon::create($this->selectedYear, $this->selectedMonth)->daysInMonth;

        $labels = [];
        $seriesData = [];

        // Inisialisasi data 0 untuk setiap hari
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $labels[] = str_pad($i, 2, '0', STR_PAD_LEFT);
            $seriesData[$i] = 0;
        }

        // Mengambil data dari database
        $identities = Identity::whereYear('created_at', $this->selectedYear)
            ->whereMonth('created_at', $this->selectedMonth)
            ->get()
            ->groupBy(function ($item) {
                return $item->created_at->format('j');
            })
            ->map(function ($group) {
                return $group->count();
            })
            ->toArray();

        // Mengisi array dengan data asli
        foreach ($identities as $day => $count) {
            $seriesData[$day] = $count;
        }

        return [
            'labels' => $labels,
            'series' => array_values($seriesData),
        ];
    }

    public function render(HashId $hashId)
    {
        return view('livewire.dashboard', [
            'hashId' => $hashId,
        ]);
    }
}
