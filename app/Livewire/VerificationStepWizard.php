<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Spatie\Html\Elements\Button;
use Spatie\Html\Elements\Element;
use Spatie\Html\Elements\Form;
use Spatie\Html\Elements\Input;
use Spatie\Html\Elements\Label;

#[Layout('components.layouts.wizard')]
class VerificationStepWizard extends Component
{
    protected $nama_bank;
    protected $nomor_rekening;
    protected $nama_akun;

    public function render()
    {
        return Element::withTag('div')
            ->class('p-0')
            ->children([
                session()->has('success') ? Element::withTag('div')
                    ->class('alert alert-success text-center')
                    ->text(session('success')) : null,
                Element::withTag('div')
                    ->class('col-12')
                    ->child(
                        Element::withTag('h6')->text("Data anda berhasil tersimpan. Silahkan lanjutkan dengan mengisi form dibawah ini")
                    ),
                Form::create()->children([
                    Element::withTag("div")->class('my-3 form-group')->children([
                        Label::create()
                            ->for('nama_bank')
                            ->text('Nama Bank *')
                            ->class('form-label'),
                        Input::create()
                            ->placeholder("Contoh : BCA, BRI, Mandiri, Dll")
                            ->attribute('wire:model', 'nama_bank')
                            ->name('nama_bank')
                            ->id('nama_bank')
                            ->class('required form-control ' . ($this->getErrorBag()->first('nama') ? ' is-invalid' : '')),
                        $this->getErrorBag()->first('nama_bank') ? Element::withTag('div')->class('invalid-feedback')->text($this->getErrorBag()->first('nama_bank')) : null,
                    ]),
                    Element::withTag("div")->class('my-3 form-group')->children([
                        Label::create()
                            ->for('nomor_rekening')
                            ->text('Nama Bank *')
                            ->class('form-label'),
                        Input::create()
                            ->type('number')
                            ->placeholder("Tidak perlu ada kode bank")
                            ->attribute('wire:model', 'nomor_rekening')
                            ->name('nomor_rekening')
                            ->id('nomor_rekening')
                            ->class('required form-control ' . ($this->getErrorBag()->first('nama') ? ' is-invalid' : '')),
                        $this->getErrorBag()->first('nomor_rekening') ? Element::withTag('div')->class('invalid-feedback')->text($this->getErrorBag()->first('nomor_rekening')) : null,
                    ]),
                    Element::withTag("div")->class('my-3 form-group')->children([
                        Label::create()
                            ->for('nama_akun')
                            ->text('Nama Akun *')
                            ->class('form-label'),
                        Input::create()
                            ->placeholder("Nama yang tertera pada rekening")
                            ->type('number')
                            ->attribute('wire:model', 'nama_akun')
                            ->name('nama_akun')
                            ->id('nama_akun')
                            ->class('required form-control ' . ($this->getErrorBag()->first('nama') ? ' is-invalid' : '')),
                        $this->getErrorBag()->first('nama_akun') ? Element::withTag('div')->class('invalid-feedback')->text($this->getErrorBag()->first('nama_akun')) : null,
                    ])
                ])
            ])->toHtml();
    }
}
