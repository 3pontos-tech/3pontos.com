<section class="hp-section" id="foundations">
    <div class="hp-container grid grid-cols-1 items-start gap-x-12 lg:grid-cols-[1fr_5fr]">
        <div
            x-data="{ visible: false }"
            x-intersect.once="visible = true"
            class="mb-4 flex items-center justify-center sm:justify-start"
        >
            <x-he4rt::animate-block duration="700">
                <x-he4rt::section-title size="lg">Comunidade 3 pontos</x-he4rt::section-title>
            </x-he4rt::animate-block>
        </div>

        <div class="flex flex-col gap-8">
            <x-he4rt::headline class="mx-0">
                <x-slot:title>Comunidade 3 Pontos: Seu hub de tecnologia</x-slot>
                <x-slot:description>
                    Onde o networking é real, o aprendizado é contínuo e os desafios são inspiradores. Junte-se a
                    centenas de profissionais de áreas estratégias e capacitados que estão construindo o futuro num
                    ambiente qualificado e propício para que o melhor aconteça.
                </x-slot>
                <x-slot:actions>
                    <x-he4rt::button href="https://discord.gg/dbFkFkaC">Junte-se a nós</x-he4rt::button>
                </x-slot>
            </x-he4rt::headline>
        </div>

        <div
            x-data="{ visible: false }"
            x-intersect.threshold.20="visible = true"
            class="grid grid-cols-1 gap-8 lg:col-span-2 lg:grid-cols-8 lg:gap-4"
        ></div>
    </div>
</section>
