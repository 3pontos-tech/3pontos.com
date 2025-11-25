<section class="hp-section relative" id="foundations">
    <div class="absolute bottom-30">
        <img src="{{ asset('images/3pontos/big-wave.svg') }}" alt="" class="h-full w-full object-cover" />
    </div>

    <div class="hp-container relative z-10 grid grid-cols-1 content-start items-start gap-x-12 lg:grid-cols-[1fr_5fr]">
        <div
            x-data="{ visible: false }"
            x-intersect.once="visible = true"
            class="mb-4 flex items-center justify-center sm:justify-start"
        >
            <x-he4rt::animate-block duration="700">
                <x-he4rt::section-title size="lg">Nossos pilares</x-he4rt::section-title>
            </x-he4rt::animate-block>
        </div>

        <div class="flex flex-col gap-8">
            <div class="flex items-start">
                <x-he4rt::headline class="mx-0">
                    <x-slot:title>Nossos pilares</x-slot>
                    <x-slot:description>
                        A 3 Pontos conecta empresas e startups inovadoras a talentos excepcionais, acelerando soluções
                        reais e transformando ideias em impacto.
                    </x-slot>
                    <x-slot:actions>
                        <x-he4rt::button href="/">Button</x-he4rt::button>
                    </x-slot>
                </x-he4rt::headline>
            </div>

            <div
                x-data="{ visible: false }"
                x-intersect.threshold.20="visible = true"
                class="grid grid-cols-1 gap-8"
            >
                <x-he4rt::card class="gap-4">
                    <x-slot:icon class="items-center gap-4">
                        <x-he4rt::icon size="sm" class="rounded-sm" />
                        <x-he4rt::heading size="2xs">Soluções Estratégicas para Seu Crescimento</x-he4rt::heading>
                    </x-slot>
                    <x-slot:description class="leading-[1.5]">
                        Sua empresa tem uma visão clara, mas enfrenta desafios tecnológicos. Aqui na 3 Pontos,
                        desenvolvemos e implementamos soluções de ponta que transformam essas barreiras em
                        oportunidades. Do desenvolvimento customizado ao marketing digital estratégico, nós somos o
                        parceiro que sua empresa precisa para escalar com confiança.
                    </x-slot>
                </x-he4rt::card>

                <x-he4rt::card class="gap-4">
                    <x-slot:icon class="items-center gap-4">
                        <x-he4rt::icon size="sm" class="rounded-sm" />
                        <x-he4rt::heading size="2xs">Soluções Estratégicas para Seu Crescimento</x-he4rt::heading>
                    </x-slot>
                    <x-slot:description class="leading-[1.5]">
                        Sua empresa tem uma visão clara, mas enfrenta desafios tecnológicos. Aqui na 3 Pontos,
                        desenvolvemos e implementamos soluções de ponta que transformam essas barreiras em
                        oportunidades. Do desenvolvimento customizado ao marketing digital estratégico, nós somos o
                        parceiro que sua empresa precisa para escalar com confiança.
                    </x-slot>
                </x-he4rt::card>

                <x-he4rt::card class="gap-4">
                    <x-slot:icon class="items-center gap-4">
                        <x-he4rt::icon size="sm" class="rounded-sm" />
                        <x-he4rt::heading size="2xs">Soluções Estratégicas para Seu Crescimento</x-he4rt::heading>
                    </x-slot>
                    <x-slot:description class="leading-[1.5]">
                        Sua empresa tem uma visão clara, mas enfrenta desafios tecnológicos. Aqui na 3 Pontos,
                        desenvolvemos e implementamos soluções de ponta que transformam essas barreiras em
                        oportunidades. Do desenvolvimento customizado ao marketing digital estratégico, nós somos o
                        parceiro que sua empresa precisa para escalar com confiança.
                    </x-slot>
                </x-he4rt::card>
            </div>
        </div>
    </div>
</section>
