<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Identity;
use Carbon\Carbon;
use Livewire\Attributes\Computed;

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

        // Mengambil data dari database (menggunakan pengelompokan Koleksi PHP agar bebas error di SQLite/MySQL)
        $identities = Identity::whereYear('created_at', $this->selectedYear)
            ->whereMonth('created_at', $this->selectedMonth)
            ->get()
            ->groupBy(function ($item) {
                // Mengambil tanggal (1-31) dari property created_at (Carbon)
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
            'series' => array_values($seriesData)
        ];
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
