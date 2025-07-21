<?php

namespace App\Traits;

use Spatie\Html\Elements\Button;
use Spatie\Html\Elements\Element;

trait AlertElement
{
  public function errorAlertElement($message)
  {
    return Element::withTag('div')->class('alert alert-danger alert-dismissible fade show')
      ->children([
        Button::create()
          ->class('btn-close')
          ->type('button')
          ->attribute('data-bs-dismiss', 'alert')
          ->attribute('aria-label', 'Close'),
        Element::withTag('p')
          ->children(
            [
              Element::withTag('strong')
                ->text('Terjadi Kesalahan'),
              $message
            ]
          )
      ])
      ->toHtml();
  }

  public function successAlertElement($message)
  {
    return Element::withTag('div')->class('alert alert-success alert-dismissible fade show')
      ->children([
        Button::create()
          ->class('btn-close')
          ->type('button')
          ->attribute('data-bs-dismiss', 'alert')
          ->attribute('aria-label', 'Close'),
        Element::withTag('p')
          ->children(
            [
              Element::withTag('strong')
                ->text('Berhasil !'),
              $message
            ]
          )
      ])
      ->toHtml();
  }
}
