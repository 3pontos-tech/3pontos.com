@php
    $contacts = [
        [
            'label' => 'Telefone',
            'value' => '+55 90000-0000',
            'icon' => 'heroicon-o-phone',
        ],
        [
            'label' => 'Email',
            'value' => 'email@example.com',
            'icon' => 'heroicon-o-envelope',
        ],
        [
            'label' => 'Endereço',
            'value' => 'Av. Dr. Cardoso de Melo - Vila Olímpia - São Paulo',
            'icon' => 'heroicon-o-map-pin',
        ],
    ];

    $socials = ['fab-instagram', 'fab-x-twitter', 'fab-linkedin'];
@endphp

<section class="hp-section relative z-10" id="contact">
    <div class="hp-container grid grid-cols-1 items-start gap-x-12 lg:grid-cols-[1fr_5fr]">
        <div
            x-data="{ visible: false }"
            x-intersect.once="visible = true"
            class="mb-4 flex items-center justify-center sm:justify-start"
        >
            <x-he4rt::animate-block duration="700">
                <x-he4rt::section-title size="lg">Contato</x-he4rt::section-title>
            </x-he4rt::animate-block>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-[1.5fr_1fr] lg:gap-20">
            <div class="order-first lg:col-span-2">
                <x-he4rt::headline class="mx-0">
                    <x-slot:title>Fale conosco</x-slot>
                    <x-slot:description>
                        Estamos prontos para acelerar seu negócio ou te receber em nossa comunidade. Escolha a forma de
                        contato que funciona melhor para você.
                    </x-slot>
                </x-he4rt::headline>
            </div>

            <div
                class="bg-elevation-surface/20 border-outline-dark order-2 flex flex-1 flex-col gap-8 rounded-lg border p-4 lg:order-1 lg:p-6"
            >
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-he4rt::input label="Nome completo" placeholder="Digite seu nome completo" />
                    <x-he4rt::input label="Email" placeholder="Digite seu email" />
                </div>
                <x-he4rt::input label="Telefone" placeholder="(99) 99999-9999" />
                <x-he4rt::textarea label="Mensagem" placeholder="Digite sua mensagem..." />

                <x-he4rt::button>Enviar mensagem</x-he4rt::button>
            </div>

            <div
                x-data="{ visible: false }"
                x-intersect.threshold.80="visible = true"
                class="order-1 grid h-full grid-cols-1 content-between gap-12 py-2 sm:grid-cols-2 lg:order-2 lg:grid-cols-1 lg:py-4"
            >
                @foreach ($contacts as $index => $item)
                    <x-he4rt::animate-block type="fade-right" :delay="$index * 100">
                        <x-he4rt::card :interactive="false" class="h-fit border-none bg-transparent p-0">
                            <x-slot:icon class="flex-col items-start gap-3 sm:flex-row sm:items-center">
                                <x-he4rt::icon :icon="$item['icon']" class="bg-elevation-02dp" />
                                <div class="flex flex-1 flex-col gap-0.5">
                                    <x-he4rt::text class="font-medium">
                                        {{ $item['label'] }}
                                    </x-he4rt::text>
                                    <x-he4rt::text class="text-text-high font-bold">
                                        {{ $item['value'] }}
                                    </x-he4rt::text>
                                </div>
                            </x-slot>
                        </x-he4rt::card>
                    </x-he4rt::animate-block>
                @endforeach

                <x-he4rt::animate-block type="fade-right" :delay="count($contacts) * 100">
                    <x-he4rt::card :interactive="false" class="h-fit border-none bg-transparent p-0">
                        <x-slot:icon class="flex-col items-start gap-3 sm:flex-row sm:items-center">
                            <x-he4rt::icon icon="heroicon-o-arrow-path" class="bg-elevation-02dp" />
                            <div class="flex flex-1 flex-col gap-1">
                                <x-he4rt::text class="font-medium">Siga nas redes sociais</x-he4rt::text>
                                <div class="flex gap-6">
                                    @foreach ($socials as $socialIcon)
                                        <x-he4rt::icon :icon="$socialIcon" class="border-none bg-transparent p-0" />
                                    @endforeach
                                </div>
                            </div>
                        </x-slot>
                    </x-he4rt::card>
                </x-he4rt::animate-block>
            </div>
        </div>
    </div>
</section>
