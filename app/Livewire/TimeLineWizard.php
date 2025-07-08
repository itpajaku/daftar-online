<?php

namespace App\Livewire;

use Livewire\Component;

use App\Library\HashId;
use App\Models\Identity;
use App\Service\HashRouteId;
use Illuminate\Support\Facades\Vite;
use Spatie\Html\Elements\Element;
use Spatie\Html\Elements\Span;

class TimeLineWizard extends Component
{
    public function mount(HashRouteId $hash_id)
    {
        $this->identity = Identity::find($hash_id->getDecodedId());
        if (!$this->identity) {
            session()->flash('error', 'Akun tidak ditemukan.');
            return redirect()->route('search');
        }
    }

    public function render()
    {
        return Element::withTag('section')->class('py-1 py-md-1')->children([
            Element::withTag('div')->class('container')->children([
                Element::withTag('div')->class('row justify-content-center')->children([
                    Element::withTag('div')->class('col-12')->children([
                        Element::withTag('div')->class('card widget-card bsb-timeline-8 border-light shadow-sm')->children([
                            Element::withTag('div')->class('card-body')->children([
                                Element::withTag('h5')->text('Progress Pendaftaran Anda')->class('card-title widget-card-title mb-3'),
                                Element::withTag('ul')->class('timeline')->children([
                                    Element::withTag('li')->class('timeline-item')->children([
                                        Element::withTag('div')->class('timeline-body')->children([
                                            Element::withTag('div')->class('timeline-meta')->children([
                                                Span::create()->text('32 minutes'),
                                            ]),
                                            Element::withTag('div')->class('timeline-content timeline-indicator')->children([
                                                Element::withTag('h6')->text('Amount received in the PayPal gateway.')->class('mb-1'),
                                                Span::create()->text('User: William Lucas')->class('text-secondary fs-7'),
                                            ]),
                                        ]),
                                    ]),

                                    Element::withTag('li')->class('timeline-item')->children([
                                        Element::withTag('div')->class('timeline-body')->children([
                                            Element::withTag('div')->class('timeline-meta')->children([
                                                Span::create()->text('49 minutes'),
                                            ]),
                                            Element::withTag('div')->class('timeline-content timeline-indicator')->children([
                                                Element::withTag('h6')->text('New sale recorded in the Bootstrap admin templates.')->class('mb-1'),
                                                Span::create()->text('Product: Console')->class('text-secondary fs-7'),
                                            ]),
                                        ]),
                                    ]),

                                    Element::withTag('li')->class('timeline-item')->children([
                                        Element::withTag('div')->class('timeline-body')->children([
                                            Element::withTag('div')->class('timeline-meta')->children([
                                                Span::create()->text('2 hours'),
                                            ]),
                                            Element::withTag('div')->class('timeline-content timeline-indicator')->children([
                                                Element::withTag('h6')->text('User registered in the discount campaign.')->class('mb-1'),
                                                Span::create()->text('Country: United States')->class('text-secondary fs-7'),
                                            ]),
                                        ]),
                                    ]),

                                    Element::withTag('li')->class('timeline-item')->children([
                                        Element::withTag('div')->class('timeline-body')->children([
                                            Element::withTag('div')->class('timeline-meta')->children([
                                                Span::create()->text('19 hours'),
                                            ]),
                                            Element::withTag('div')->class('timeline-content timeline-indicator')->children([
                                                Element::withTag('h6')->text('Ticket created about the SSL certificate of the domain.')->class('mb-1'),
                                                Span::create()->text('Issue: Technical')->class('text-secondary fs-7'),
                                            ]),
                                        ]),
                                    ]),
                                ]),
                            ]),
                        ]),
                    ]),
                ]),
            ]),
        ])->toHtml();
    }
}
