<section class="hp-section relative" id="social-action">
    <div
        class="absolute bottom-30 left-0 z-1 flex origin-top rotate-90 justify-start sm:-translate-x-[20%] sm:translate-y-32 lg:-translate-x-[5%] lg:translate-y-24 lg:rotate-0"
    >
        <img
            src="{{ asset('images/3pontos/logo-chain.png') }}"
            alt=""
            class="hidden h-auto w-full object-contain sm:block sm:max-w-[80%]"
        />
    </div>

    <div class="hp-container relative z-10 grid grid-cols-1 items-start gap-x-12 lg:grid-cols-[1fr_5fr]">
        <div
            x-data="{ visible: false }"
            x-intersect.once="visible = true"
            class="mb-4 flex items-center justify-center sm:justify-start"
        >
            <x-he4rt::animate-block duration="700">
                <x-he4rt::section-title size="lg">Prova social</x-he4rt::section-title>
            </x-he4rt::animate-block>
        </div>

        <div class="flex flex-col gap-8">
            <div>
                <x-he4rt::headline>
                    <x-slot:title>Nossos Números falam por Nós</x-slot>
                    <x-slot:description>
                        Cada número reflete nosso impacto real: a união entre inovação, talentos e negócios que aceleram
                        o crescimento e criam conexões com propósito
                    </x-slot>
                    <x-slot:actions>
                        <x-he4rt::button>Button</x-he4rt::button>
                    </x-slot>
                </x-he4rt::headline>
            </div>

            <div
                x-data="{ visible: false }"
                x-intersect.threshold.20.once="visible = true"
                class="grid grid-cols-1 gap-8 lg:grid-cols-3"
            >
                <x-he4rt::animate-block>
                    <x-he4rt::card>
                        <x-slot:icon class="font-family-secondary text-4xl">000</x-slot>
                        <x-slot:title>Empresas aceleradas</x-slot>
                    </x-he4rt::card>
                </x-he4rt::animate-block>

                <x-he4rt::animate-block delay="100">
                    <x-he4rt::card>
                        <x-slot:icon class="font-family-secondary text-4xl">000</x-slot>
                        <x-slot:title>Empresas aceleradas</x-slot>
                    </x-he4rt::card>
                </x-he4rt::animate-block>

                <x-he4rt::animate-block delay="200">
                    <x-he4rt::card>
                        <x-slot:icon class="font-family-secondary text-4xl">000</x-slot>
                        <x-slot:title>Empresas aceleradas</x-slot>
                    </x-he4rt::card>
                </x-he4rt::animate-block>
            </div>
        </div>
    </div>
</section>
