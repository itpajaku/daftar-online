<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class ECourtAccountForm extends Form
{
  #[Validate('required', message: 'Tidak Boleh Kosong')]
  public $username;

  #[Validate('required', message: 'Tidak Boleh Kosong')]
  public $password;
}
