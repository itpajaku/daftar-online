<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Spatie\Html\Elements\Button;
use Spatie\Html\Elements\Element;
use Spatie\Html\Elements\Form;
use Spatie\Html\Elements\Input;
use Spatie\Html\Elements\Label;
use Spatie\Html\Elements\Option;

#[Layout('components.layouts.wizard')]
class VerificationStepWizard extends Component
{
    #[Validate('required', message: 'Nama Bank tidak boleh kosong')]
    protected $nama_bank;

    #[Validate('required', message: 'Nomor Rekening tidak boleh kosong')]
    protected $nomor_rekening;

    #[Validate('required', message: 'Nama Akun tidak boleh kosong')]
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
                            ->class('required form-control ' . ($this->getErrorBag()->first('nama_bank') ? ' is-invalid' : '')),
                        Element::withTag('datalist')->id('list_daftar_bank')
                            ->children($this->getDaftarNamaBank()->map(function ($item) {
                                return Option::create()->text($item);
                            })->all()),
                        $this->getErrorBag()->first('nama_bank') ? Element::withTag('div')->class('invalid-feedback')->text($this->getErrorBag()->first('nama_bank')) : null,
                    ]),
                    Element::withTag("div")->class('my-3 form-group')->children([
                        Label::create()
                            ->for('nomor_rekening')
                            ->text('Nomor Rekening *')
                            ->class('form-label'),
                        Input::create()
                            ->type('number')
                            ->placeholder("Tidak perlu ada kode bank")
                            ->attribute('wire:model', 'nomor_rekening')
                            ->attribute('list', 'list_daftar_bank')
                            ->name('nomor_rekening')
                            ->id('nomor_rekening')
                            ->class('required form-control ' . ($this->getErrorBag()->first('nomor_rekening') ? ' is-invalid' : '')),
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
                            ->class('required form-control ' . ($this->getErrorBag()->first('nama_akun') ? ' is-invalid' : '')),
                        $this->getErrorBag()->first('nama_akun') ? Element::withTag('div')->class('invalid-feedback')->text($this->getErrorBag()->first('nama_akun')) : null,
                    ])
                ])
            ])->toHtml();
    }

    protected function getDaftarNamaBank()
    {
        return collect([
            "A N Z PANIN",
            "ARTHA GRAHA",
            "ARTOS INDONESIA",
            "B C A",
            "B I I",
            "B J B",
            "B J B SYARIAH",
            "B N I",
            "B T N",
            "B R I",
            "BANK DKI",
            "BANK EKSEKUTIF",
            "BANK INA",
            "BANK INDEX",
            "BANK JASA JAKARTA",
            "BANK JTRUST INDONESIA",
            "BANK KESEJAHTERAAN",
            "BANK MANTAP",
            "BANK NAGARI",
            "Bank Nusantara Parahyangan",
            "Bank Of Tokyo-Mitsubishi UFJ",
            "BANK PAPUA",
            "BANK QNB",
            "BANK SAUDARA",
            "BANK VICTORIA INT",
            "BARCLAYS",
            "BPD ACEH",
            "BPD BALI",
            "BPD BENGKULU",
            "BPD DIY",
            "BPD JAMBI",
            "BPD JATENG",
            "BPD JATIM",
            "BPD KALBAR",
            "BPD KALSEL",
            "BPD KALTENG",
            "BPD KALTIM",
            "BPD LAMPUNG",
            "BPD MALUKU",
            "BPD NTB",
            "BPD NTT",
            "BPD RIAU",
            "BPD SULSEL",
            "BPD SULTENG",
            "BPD SULTRA",
            "BPD SULUT",
            "BPD SUMSEL BABEL",
            "BPD SUMUT",
            "BRI AGRO",
            "BTPN",
            "BUKOPIN",
            "BUMI ARTA",
            "CAPITAL",
            "CHINA CONSTRUCTION BANK",
            "CIMB NIAGA",
            "CITIBANK",
            "COMMONWEALTH",
            "DANAMON",
            "EKONOMI",
            "GANESHA",
            "H S B C",
            "MANDIRI",
            "MASPION",
            "MAYAPADA INT",
            "MAYORA",
            "MEGA",
            "MEGA SYARIAH",
            "MESTIKA",
            "MNC BANK",
            "MUAMALAT",
            "NOBU BANK",
            "OCBC NISP",
            "PANIN",
            "PERMATA",
            "R B S",
            "RABOBANK",
            "ROYAL",
            "S B I I",
            "S C B",
            "SINARMAS",
            "SWADESI",
            "U I B",
            "UOB BUANA",
            "BANK AGRIS",
            "BSI"
        ]);
    }

    protected function getDaftarNamaBankWithId()
    {
        return [
            "select2-szBank-result-dqd6-1" => "A N Z PANIN",
            "select2-szBank-result-q322-2" => "ARTHA GRAHA",
            "select2-szBank-result-dlh9-3" => "ARTOS INDONESIA",
            "select2-szBank-result-p1gh-4" => "B C A",
            "select2-szBank-result-e5dl-5" => "B I I",
            "select2-szBank-result-5a0c-6" => "B J B",
            "select2-szBank-result-emuq-7" => "B J B SYARIAH",
            "select2-szBank-result-4h9y-8" => "B N I",
            "select2-szBank-result-5k8v-9" => "B T N",
            "select2-szBank-result-eaqu-10" => "B R I",
            "select2-szBank-result-sma6-11" => "BANK DKI",
            "select2-szBank-result-5q9j-12" => "BANK EKSEKUTIF",
            "select2-szBank-result-djx5-13" => "BANK INA",
            "select2-szBank-result-eyqq-14" => "BANK INDEX",
            "select2-szBank-result-fnhp-15" => "BANK JASA JAKARTA",
            "select2-szBank-result-8zi3-16" => "BANK JTRUST INDONESIA",
            "select2-szBank-result-qbou-17" => "BANK KESEJAHTERAAN",
            "select2-szBank-result-fvwh-18" => "BANK MANTAP",
            "select2-szBank-result-cqii-19" => "BANK NAGARI",
            "select2-szBank-result-xch7-20" => "Bank Nusantara Parahyangan",
            "select2-szBank-result-4dth-21" => "Bank Of Tokyo-Mitsubishi UFJ",
            "select2-szBank-result-sj5j-22" => "BANK PAPUA",
            "select2-szBank-result-bpgt-23" => "BANK QNB",
            "select2-szBank-result-3zhb-24" => "BANK SAUDARA",
            "select2-szBank-result-y7jq-26" => "BANK VICTORIA INT",
            "select2-szBank-result-yka2-27" => "BARCLAYS",
            "select2-szBank-result-ji4x-28" => "BPD ACEH",
            "select2-szBank-result-psjm-29" => "BPD BALI",
            "select2-szBank-result-fti4-30" => "BPD BENGKULU",
            "select2-szBank-result-vcoa-31" => "BPD DIY",
            "select2-szBank-result-8tqg-32" => "BPD JAMBI",
            "select2-szBank-result-z7pe-33" => "BPD JATENG",
            "select2-szBank-result-bym8-34" => "BPD JATIM",
            "select2-szBank-result-el9j-35" => "BPD KALBAR",
            "select2-szBank-result-ztx1-36" => "BPD KALSEL",
            "select2-szBank-result-2tjj-37" => "BPD KALTENG",
            "select2-szBank-result-fd09-38" => "BPD KALTIM",
            "select2-szBank-result-ha52-39" => "BPD LAMPUNG",
            "select2-szBank-result-2vmh-40" => "BPD MALUKU",
            "select2-szBank-result-dyx9-41" => "BPD NTB",
            "select2-szBank-result-94q3-42" => "BPD NTT",
            "select2-szBank-result-p5c5-43" => "BPD RIAU",
            "select2-szBank-result-a8c9-44" => "BPD SULSEL",
            "select2-szBank-result-s1we-45" => "BPD SULTENG",
            "select2-szBank-result-o7ty-46" => "BPD SULTRA",
            "select2-szBank-result-7bdp-47" => "BPD SULUT",
            "select2-szBank-result-38se-48" => "BPD SUMSEL BABEL",
            "select2-szBank-result-pyav-49" => "BPD SUMUT",
            "select2-szBank-result-p74q-50" => "BRI AGRO",
            "select2-szBank-result-abbh-52" => "BTPN",
            "select2-szBank-result-l7w8-53" => "BUKOPIN",
            "select2-szBank-result-ryjg-54" => "BUMI ARTA",
            "select2-szBank-result-wzgj-55" => "CAPITAL",
            "select2-szBank-result-7gpv-56" => "CHINA CONSTRUCTION BANK",
            "select2-szBank-result-og4x-57" => "CIMB NIAGA",
            "select2-szBank-result-1wkt-58" => "CITIBANK",
            "select2-szBank-result-5bar-59" => "COMMONWEALTH",
            "select2-szBank-result-uyhs-60" => "DANAMON",
            "select2-szBank-result-ceev-61" => "EKONOMI",
            "select2-szBank-result-v4uv-62" => "GANESHA",
            "select2-szBank-result-c3ii-63" => "H S B C",
            "select2-szBank-result-yqgm-64" => "MANDIRI",
            "select2-szBank-result-t0uu-65" => "MASPION",
            "select2-szBank-result-amgy-66" => "MAYAPADA INT",
            "select2-szBank-result-gmbl-67" => "MAYORA",
            "select2-szBank-result-v3dl-68" => "MEGA",
            "select2-szBank-result-etxd-69" => "MEGA SYARIAH",
            "select2-szBank-result-l4bx-70" => "MESTIKA",
            "select2-szBank-result-ax8q-71" => "MNC BANK",
            "select2-szBank-result-u2bh-72" => "MUAMALAT",
            "select2-szBank-result-92gy-73" => "NOBU BANK",
            "select2-szBank-result-w5j9-74" => "OCBC NISP",
            "select2-szBank-result-ekp2-75" => "PANIN",
            "select2-szBank-result-i4q8-76" => "PERMATA",
            "select2-szBank-result-q03u-77" => "R B S",
            "select2-szBank-result-gie7-78" => "RABOBANK",
            "select2-szBank-result-lmpm-79" => "ROYAL",
            "select2-szBank-result-beo9-80" => "S B I I",
            "select2-szBank-result-qhb5-81" => "S C B",
            "select2-szBank-result-0s0h-82" => "SINARMAS",
            "select2-szBank-result-omlx-83" => "SWADESI",
            "select2-szBank-result-f3wa-84" => "U I B",
            "select2-szBank-result-da83-85" => "UOB BUANA",
            "select2-szBank-result-r7w4-86" => "BANK AGRIS",
            "select2-szBank-result-r47h-89" => "BSI"
        ];
    }
}
