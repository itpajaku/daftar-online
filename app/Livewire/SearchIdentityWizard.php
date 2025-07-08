<?php

namespace App\Livewire;

use App\Models\Identity;
use App\Service\HashId;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Attributes\Layout;

use Spatie\Html\Elements\Button;
use Spatie\Html\Elements\Element;
use Spatie\Html\Elements\Form;
use Spatie\Html\Elements\Input;
use Spatie\Html\Elements\Label;
use Spatie\Html\Elements\A;
use Livewire\Attributes\Validate;

#[Layout('components.layouts.wizard')]
class SearchIdentityWizard extends Component
{

    #[Validate('required', message: 'Nomor telepon tidak boleh kosong.')]
    public $nomor_telepon;

    public function search(HashId $hashId)
    {
        $this->validate();
        $nomor_telepon = $hashId->encode($this->nomor_telepon);
        $identity = Identity::where('nomor_telepon', $nomor_telepon)->first();
        if (!$identity) {
            session()->flash('error', 'Akun dengan nomor telepon tersebut tidak ditemukan.');
            return;
        }
        return redirect('timeline/' . $hashId->encode($identity->id));
    }

    public function render()
    {
        $errorMessage = $this->getErrorBag()->first('nomor_telepon');

        return Element::withTag("div")
            ->class("p-0")
            ->children([
                Element::withTag("div")
                    ->class("d-flex justify-content-center")
                    ->child(
                        Element::withTag('video')
                            ->class("img-fluid mb-3")
                            ->attribute('autoplay', true)
                            ->attribute('loop', true)
                            ->attribute('muted', true)
                            ->attribute("alt", "search-animation")
                            ->child(
                                Element::withTag("source")
                                    ->attribute("src", asset("images/animation/search.webm"))
                                    ->attribute("type", "video/webm")
                            )
                    ),
                Element::withTag("div")
                    ->class("alert alert-info d-flex align-items-top")
                    ->child([
                        Element::withTag("i")
                            ->class("ti ti-info-circle me-2 fs-5 text-primary"),
                        Element::withTag("h6")
                            ->text("Lanjutkan pendaftaran anda. Silahkan masukan nomor telepon anda untuk mencari akun yang sudah terdaftar.")
                    ]),
                Form::create()
                    ->attribute("wire:submit", "search")
                    ->child([
                        Element::withTag("div")
                            ->class("mb-3 form-floating")
                            ->children([
                                Input::create("input")
                                    ->type("number")
                                    ->class("form-control")
                                    ->attribute("wire:model", "nomor_telepon")
                                    ->id("nomor_telepon")
                                    ->addClass($errorMessage ? 'is-invalid' : null),
                                Label::create()
                                    ->class("form-label")
                                    ->for("nomor_telepon")
                                    ->text("Masukan Nomor Telepon Anda"),
                                $errorMessage ? Element::withTag("div")
                                    ->class("invalid-feedback")
                                    ->text($errorMessage) : null
                            ]),
                        session()->has('error') ? Element::withTag("div")
                            ->class("alert alert-danger alert-dismissible fade show")
                            ->attribute("ri")
                            ->children([
                                Element::withTag("strong")
                                    ->text("Gagal!"),
                                Element::withTag("span")
                                    ->text(session('error')),
                                Element::withTag("button")
                                    ->class("btn-close")
                                    ->attribute("data-bs-dismiss", "alert")
                                    ->attribute("aria-label", "Close"),
                            ]) : null,
                        Element::withTag("div")
                            ->class("d-flex justify-content-between")
                            ->children([
                                A::create()
                                    ->class("btn btn-danger text-end")
                                    ->text("Kembali")
                                    ->href(route('home'))
                                    ->child(
                                        Element::withTag("i")
                                            ->class("ti ti-arrow-left ms-2")
                                    ),
                                Button::create()
                                    ->class("btn btn-primary text-end")
                                    ->text("Cari Akun")
                                    ->child(
                                        Element::withTag("i")
                                            ->class("bi bi-search ms-2")
                                            ->attribute("aria-hidden", "true")
                                    ),
                            ])
                    ]),
            ])
            ->toHtml();
    }
}
