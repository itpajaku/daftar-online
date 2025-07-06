<?php

use Spatie\Html\Elements\Element;
use Spatie\Html\Elements\Label;
use Spatie\Html\Elements\Input;
use Spatie\Html\Elements\Select;
use Spatie\Html\Elements\Textarea;
?>

<form wire:submit="save" class="tab-wizard wizard-circle">
  @if (session()->has('message'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Berhasil!</strong> {{ session('message') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  @endif
  <div class="d-flex  gap-3 align-items-center bg-primary justify-content-start rounded pt-2 px-2">
    <h5>
      <i class="ti ti-user text-white"></i>
    </h5>
    <h5 class="text-white">1. Personal Info</h5>
  </div>
  <section>
    <!-- Input Nama Lengkap  -->
    <?= Element::withTag('div')->class('my-3 form-group')->children([
      Label::create()
        ->for('nama_lengkap')
        ->text('Nama Lengkap *')
        ->class('form-label'),
      Input::create()
        ->attribute('wire:model', 'nama_lengkap')
        ->name('nama_lengkap')
        ->id('nama_lengkap')
        ->placeholder('Masukkan nama lengkap Anda (tanpa gelar)')
        ->class('required form-control' . ($errors->has('nama_lengkap') ? ' is-invalid' : '')),
      $errors->has('nama_lengkap') ? Element::withTag('div')->class('invalid-feedback')->text($errors->first('nama_lengkap')) : null,
    ]) ?>


    <!-- Select Jenis Kelamin  -->
    <?= Element::withTag("div")->class('my-3 form-group')->children([
      Label::create()
        ->for('jenis_kelamin')
        ->text('Jenis Kelamin *')
        ->class('form-label'),
      Select::create()
        ->attribute('wire:model', 'jenis_kelamin')
        ->name('jenis_kelamin')
        ->id('jenis_kelamin')
        ->child(Element::withTag('option')->text('--- Pilih Salah Satu ---')->attribute('disabled')->attribute('selected'))
        ->options([
          'Laki-laki' => 'Laki-laki',
          'Perempuan' => 'Perempuan',
        ])
        ->class('required form-select ' . ($errors->has('jenis_kelamin') ? ' is-invalid' : '')),
      $errors->has('jenis_kelamin') ? Element::withTag('div')->class('invalid-feedback')->text($errors->first('jenis_kelamin')) : null,
    ]);

    ?>
    <!-- Input Tempat & Tanggal Lahir -->
    <div class="row row-cols-md-2 ">
      <?= Element::withTag('div')->class('col')->child(
        Element::withTag('div')->class('my-3 form-group')->children([
          Label::create()->for('tempat_lahir')->text('Tempat Lahir *')->class('form-label'),
          Input::create()
            ->attribute('wire:model', 'tempat_lahir')
            ->placeholder('Masukan Tempat Lahir')
            ->class('form-control' . ($errors->has('tempat_lahir') ? ' is-invalid' : null))
            ->attribute('list', 'tempat_lahir_list'),
          Element::withTag('datalist')
            ->id('tempat_lahir_list')
            ->child([
              Element::withTag('option')->text('Jakarta'),
              Element::withTag('option')->text('Bandung'),
              Element::withTag('option')->text('Surabaya'),
              Element::withTag('option')->text('Medan'),
              Element::withTag('option')->text('Semarang'),
              Element::withTag('option')->text('Yogyakarta'),
              Element::withTag('option')->text('Palembang'),
              Element::withTag('option')->text('Makassar'),
              Element::withTag('option')->text('Batam'),
              Element::withTag('option')->text('Denpasar'),
              Element::withTag('option')->text('Malang'),
              Element::withTag('option')->text('Banjarmasin'),
              Element::withTag('option')->text('Pekanbaru'),
              Element::withTag('option')->text('Balikpapan'),
              Element::withTag('option')->text('Cirebon'),
              Element::withTag('option')->text('Tangerang'),
              Element::withTag('option')->text('Bogor'),
              Element::withTag('option')->text('Bekasi'),
              Element::withTag('option')->text('Depok'),
              Element::withTag('option')->text('Samarinda'),
              Element::withTag('option')->text('Pontianak'),
              Element::withTag('option')->text('Jambi'),
              Element::withTag('option')->text('Mataram'),
              Element::withTag('option')->text('Kupang'),
              Element::withTag('option')->text('Ambon'),
              Element::withTag('option')->text('Palu'),
              Element::withTag('option')->text('Manado'),
              Element::withTag('option')->text('Banda Aceh'),
            ]),
          $errors->has("tempat_lahir") ? Element::withTag('div')->class('invalid-feedback')->text($errors->first('tempat_lahir')) : null,
        ])
      ); ?>
      <?= Element::withTag('div')->class('col')->child(
        Element::withTag('div')->class('my-3 form-group')->children([
          Label::create()->for('tanggal_lahir')->text('Tanggal Lahir *')->class('form-label'),
          Input::create()
            ->attribute('wire:model', 'tanggal_lahir')
            ->name('tanggal_lahir')
            ->id('tanggal_lahir')
            ->type('date')
            ->class('form-control' . ($errors->has('tanggal_lahir') ? ' is-invalid' : null))
            ->attribute('max', date('Y-m-d')),
          $errors->has("tanggal_lahir") ? Element::withTag('div')->class('invalid-feedback')->text($errors->first('tanggal_lahir')) : null,
        ])
      ) ?>
    </div>

    <!-- Input NIK -->
    <?= Element::withTag('div')->class('my-3 form-group')->children([
      Label::create()->for('nomor_kependudukan')->text('Nomor Kependudukan (NIK) *')->class('form-label'),
      Input::create()->attribute('wire:model', 'nomor_kependudukan')->placeholder('Contoh : 321...')->name('nomor_kependudukan')->id('nomor_kependudukan')->class('required form-control' . ($errors->has('nomor_kependudukan') ? ' is-invalid' : null))->type('number')->minlength(15),
      $errors->has('nomor_kependudukan') ? Element::withTag('div')->class('invalid-feedback')->text($errors->first('nomor_kependudukan')) : null,
    ]);
    ?>

    <!-- Input Nomor HP -->
    <?= Element::withTag('div')->class('my-3 form-group')->children([
      Label::create()->for('nomor_telepon')->text('Nomor Telepon (Aktif Whatsapp) *')->class('form-label'),
      Input::create()->attribute('wire:model', 'nomor_telepon')->placeholder('Contoh : 081..')->name('nomor_telepon')->id('nomor_telepon')->class('required form-control' . ($errors->has('nomor_telepon') ? ' is-invalid' : null))->type('tel'),
      $errors->has('nomor_telepon') ? Element::withTag('div')->class('invalid-feedback')->text($errors->first('nomor_telepon')) : null,
    ]);
    ?>

    <!-- Input Email -->
    <?= Element::withTag('div')->class('my-3 form-group')->children([
      Label::create()->for('email')->text('Alamat Email *')->class('form-label'),
      Input::create()->attribute('wire:model', 'email')->type('email')->placeholder('Contoh :user@gmail.com')->name('email')->id('email')->class('required form-control' . ($errors->has('email') ? ' is-invalid' : '')),
      $errors->has('email') ? Element::withTag('div')->class('invalid-feedback')->text($errors->first('email')) : null,
    ]); ?>


    <!-- Input Pekerjaan -->
    <?=
    Element::withTag('div')->class('my-3 form-group')->children([
      Label::create()->for('pekerjaan')->text('Pekerjaan Anda *')->class('form-label'),
      Input::create()->attribute('wire:model', 'pekerjaan')->placeholder('Contoh : Karyawan, Ibu Rumah Tangga, Wiraswasta, Pegawai Negeri, dll')->name('pekerjaan')->id('pekerjaan')->class('required form-control' . ($errors->has('pekerjaan') ? ' is-invalid' : '')),
      $errors->has('pekerjaan') ? Element::withTag('div')->class('invalid-feedback')->text($errors->first('pekerjaan')) : null,
    ]);
    ?>

    <!-- Input Pendidikan -->
    <?=
    Element::withTag('div')->class('my-3 form-group')->children([
      Label::create()->for('pendidikan')->text('Pendidikan Terakhir *')->class('form-label'),
      Input::create()->attribute('wire:model', 'pendidikan')->placeholder('Contoh : SD, SLTP, SLTA, D3, S1, S2, S3')->name('pendidikan')->id('pendidikan')->class('required form-control' . ($errors->has('pendidikan') ? ' is-invalid' : '')),
      $errors->has('pendidikan') ? Element::withTag('div')->class('invalid-feedback')->text($errors->first('pendidikan')) : null,
    ]);
    ?>
    <!-- Input Agama -->
    <?= Element::withTag('div')->class('my-3 form-group')->children([
      Label::create()->for('agama')->text('Agama *')->class('form-label'),
      Select::create()
        ->options([
          'Islam' => 'Islam',
          'Kristen' => 'Kristen',
          'Katolik' => 'Katolik',
          'Hindu' => 'Hindu',
          'Buddha' => 'Buddha',
          'Konghucu' => 'Konghucu',
        ])
        ->attribute('wire:model', 'agama')
        ->placeholder('Pilih Agama Anda')
        ->id('agama')
        ->class('required form-select' . ($errors->has('agama') ? ' is-invalid' : '')),
      $errors->has('agama') ? Element::withTag('div')->class('invalid-feedback')->text($errors->first('agama')) : null,
    ]);
    ?>

    <!-- Select Status Perkawinan -->
    <?= Element::withTag('div')->class('my-3 form-group')->children([
      Label::create()->for('status_perkawinan')->text('Status Perkawinan *')->class('form-label'),
      Select::create()
        ->attribute('wire:model', 'status_perkawinan')
        ->name('status_perkawinan')
        ->id('status_perkawinan')
        ->child(Element::withTag('option')->text('--- Pilih Salah Satu ---')->attribute('disabled')->attribute('selected'))
        ->options([
          'Belum Menikah' => 'Belum Menikah',
          'Menikah' => 'Menikah',
          'Cerai Hidup' => 'Cerai Hidup',
          'Cerai Mati' => 'Cerai Mati',
        ])
        ->class('required form-select ' . ($errors->has('status_perkawinan') ? ' is-invalid' : '')),
      $errors->has('status_perkawinan') ? Element::withTag('div')->class('invalid-feedback')->text($errors->first('status_perkawinan')) : null,
    ]); ?>


    <!-- Text Input Alamat -->
    <?= Element::withTag('div')->class('my-3 form-group')->children([
      Label::create()->for('alamat')->text('Alamat Lengkap *')->class('form-label'),
      Textarea::create()->attribute('wire:model', 'alamat')->name('alamat')->id('alamat')->placeholder('Masukkan Alamat Lengkap Anda')->class('required form-control ' . ($errors->has('alamat') ? ' is-invalid' : ''))
        ->rows(3)
        ->maxlength(512),
      $errors->has('alamat') ? Element::withTag('div')->class('invalid-feedback')->text($errors->first('alamat')) : null,
    ]); ?>

  </section>
  <hr>
  <div class="row row-cols-md-2">
    <div class="col">
      <a href="{{ route('home') }}" class="btn btn-danger btn-block">
        <i class="ti ti-arrow-left"></i>
        Kembali
      </a>
    </div>
    <div class="col text-end">
      <button type="submit" class="btn btn-success button">
        Selanjutnya
        <i class="ti ti-arrow-right"></i>
      </button>
    </div>
  </div>
</form>