<?php

namespace App\Livewire;

use Livewire\Component;

use App\Library\HashId;
use App\Models\Identity;
use App\Service\HashRouteId;
use Illuminate\Support\Facades\Vite;
use Livewire\Attributes\Layout;
use Spatie\Html\Elements\Element;
use Spatie\Html\Elements\Span;
use Spatie\Html\Elements\A;
use Spatie\Html\Elements\Button;

#[Layout('components.layouts.wizard')]
class TimeLineWizard extends Component
{
    public Identity $identity;
    public mixed $hash_id;

    public function mount(HashRouteId $hash_id)
    {
        $this->identity = Identity::find($hash_id->getDecodedId());
        if (!$this->identity) {
            session()->flash('error', 'Akun tidak ditemukan.');
            return redirect()->route('search');
        }
        if (!$this->identity->bank_account) {
            session()->flash('error', 'Silahkan lanjutkan proses pengisian data anda.');
            return redirect()->route('step-2', ['hash_id' =>  $hash_id->getOriginalId()]);
        }
        $this->hash_id = $hash_id->getOriginalId();
    }

    public function render()
    {
        $nowDate = now()->translatedFormat('l j F Y');
        return Element::withTag('section')->class('py-1 py-md-1')->children([
            Element::withTag('div')->class('container')->children([
                Element::withTag('div')->class('card widget-card bsb-timeline-8 border-light shadow-sm')->children([
                    Element::withTag('div')->class('card-body')->children([
                        Element::withTag('h5')->text('Progress Pendaftaran Anda')->class('card-title widget-card-title mb-3'),
                        Element::withTag('ul')->class('timeline')->children([
                            Element::withTag('li')->class('timeline-item')->children([
                                Element::withTag('div')->class('timeline-body')->children([
                                    Element::withTag('div')->class('timeline-meta')->children([
                                        Span::create()->text($this->identity->created_at->translatedFormat("l j F Y")),
                                    ]),
                                    Element::withTag('div')->class('timeline-content timeline-indicator')->children([
                                        Element::withTag('h6')->text('Pendaftaran Identitas Pengguna Lain.')->class('mb-1'),
                                        Span::create()->text('User: ' . $this->identity->nama_lengkap)->class('text-secondary fs-7'),
                                        A::create()
                                            ->attribute('wire:navigate')
                                            ->href('/step-1/' . $this->hash_id)
                                            ->class('btn btn-sm btn-primary my-2')->children([
                                                Element::withTag('i')->class('ti ti-pencil me-2'),
                                                'Ubah Data'
                                            ])
                                    ]),
                                ]),
                            ]),

                            Element::withTag('li')->class('timeline-item')->children([
                                Element::withTag('div')->class('timeline-body')->children([
                                    Element::withTag('div')->class('timeline-meta')->children([
                                        Span::create()->text($this->identity->created_at->translatedFormat("l j F Y")),
                                    ]),
                                    Element::withTag('div')->class('timeline-content timeline-indicator')->children([
                                        Element::withTag('h6')->text('Pengisian Akun dan Nomor Rekening.')->class('mb-1'),
                                        Span::create()->text('Nama Bank: ' . $this->identity->bank_account->nama_bank)->class('text-secondary fs-7'),
                                        Element::withTag('br'),
                                        A::create()
                                            ->attribute('wire:navigate')
                                            ->href('/step-2/' . $this->hash_id)
                                            ->class('btn btn-sm btn-primary my-2')->children([
                                                Element::withTag('i')->class('ti ti-pencil me-2'),
                                                'Ubah Data'
                                            ])
                                    ]),

                                ]),
                            ]),

                            Element::withTag('li')->class('timeline-item')->children([
                                Element::withTag('div')->class('timeline-body')->children([
                                    Element::withTag('div')->class('timeline-meta')->children([
                                        Span::create()->text($nowDate),
                                    ]),
                                    $this->identity->ecourt_account
                                        ? $this->existedEcourtAccountCard()
                                        : $this->notExistedEcourtAccountCard()
                                ]),
                            ]),

                            $this->identity->ecourt_account
                                ? Element::withTag('li')->class('timeline-item')->children([
                                    Element::withTag('div')->class('timeline-body')->children([
                                        Element::withTag('div')->class('timeline-meta')->children([
                                            Span::create()->text($nowDate),
                                        ]),
                                        Element::withTag('div')->class('timeline-content timeline-indicator')->children([
                                            Element::withTag('h6')->text('Silahkan lanjutkan pendaftaran anda di E-Court Mahkamah Agung Republik Indonesia.')->class('mb-1'),
                                            A::create()
                                                ->href('https://ecourt.mahkamahagung.go.id/')
                                                ->text('Kunjungi E-Court')
                                                ->class('btn btn-primary mt-2')
                                                ->child(Element::withTag('i')->class('bi bi-box-arrow-up-right ms-2'))
                                                ->attribute('target', '_blank'),
                                        ]),
                                    ]),
                                ]) : null,
                        ]),
                    ]),
                    A::create()
                        ->href('/')
                        ->attribute('wire:navigate')
                        ->text('Kembali ke Homepage')
                        ->class('btn btn-secondary mt-3')
                        ->child(Element::withTag('i')->class('ti ti-home fs-5 ms-2'))
                        ->attribute('wire:click', 'redirectToSearch'),
                ]),
            ]),
        ])->toHtml();
    }

    public function redirectToSearch()
    {
        return redirect()->route('homepage');
    }

    public function existedEcourtAccountCard()
    {
        return  Element::withTag('div')->class('timeline-content timeline-indicator')->children([
            Element::withTag('h6')->text('Proses Pendaftaran Ecourt oleh Admin.')->class('mb-1'),
            Span::create()->text('Status: Sudah Terbit')->class('text-secondary fs-7'),
            Element::withTag('div')->attribute('role', 'alert')->class('alert alert-success mt-2')->children([
                Element::withTag('i')->class('bi bi-check-circle-fill me-1'),
                Span::create()->text('Pendaftaran berhasil, User : johndoe@gmail.com. Password: 5xFGG17'),
            ]),
        ]);
    }

    public function notExistedEcourtAccountCard()
    {
        return  Element::withTag('div')->class('timeline-content timeline-indicator')->children([
            Element::withTag('h6')->text('Proses Pendaftaran Ecourt oleh Admin.')->class('mb-1'),
            Span::create()->text('Status: Belum Terbit')->class('text-danger fs-7'),
            Element::withTag('div')->attribute('role', 'alert')->class('alert alert-warning mt-2')->children([
                Span::create()->text('Silahkan Tunggu Admin untuk memverifikasi data anda'),
            ]),
        ]);
    }
}
