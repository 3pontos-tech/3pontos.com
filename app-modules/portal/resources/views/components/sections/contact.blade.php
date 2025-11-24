<section class="hp-section" id="contact">
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

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <div class="lg:col-span-2">
                <x-he4rt::headline size="md">
                    <x-slot:title>Entre em contato conosco</x-slot>
                    <x-slot:description>
                        Histórias reais de desenvolvedores que transformaram suas carreiras através da nossa comunidade.
                    </x-slot>
                </x-he4rt::headline>
            </div>

            <div
                class="bg-elevation-surface/20 border-outline-dark flex flex-1 flex-col gap-8 rounded-lg border p-4 lg:p-6"
            >
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-he4rt::input label="Nome completo" />
                    <x-he4rt::input label="Email" />
                </div>
                <x-he4rt::input label="Email" />
                <x-he4rt::textarea label="Mensagem" />

                <x-he4rt::button>Enviar mensagem</x-he4rt::button>
            </div>
        </div>
    </div>
</section>
