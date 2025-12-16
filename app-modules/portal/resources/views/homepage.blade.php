<x-filament-panels::page class="relative" full-height="true">
    <x-portal::sections.hero />
    <x-portal::sections.foundations />
    <x-portal::sections.projects />
    <x-portal::sections.video />
    <x-portal::sections.community />
    <x-portal::sections.events />
    <x-portal::sections.contact />

    <div class="absolute bottom-[5%] z-0 translate-x-[90%] lg:-translate-x-[60%] lg:translate-y-1/3">
        <img
            src="{{ asset('images/3pontos/logo-creation.webp') }}"
            loading="lazy"
            class="max-h-[500px] lg:max-h-[700px]"
            alt=""
        />
    </div>

    <x-he4rt::partials.footer
        logoPath="images/3pontos/logo.svg"
        logoSize="sm"
        description="Somos o ecossistema que une solução e conhecimento em um único lugar. Aceleramos sua empresa. Fortalecemos sua carreira."
        company="3 Pontos"
        :columns="[
            'Navegação' => [
                'Home' => '#',
                'Missão social' => '#social-action',
                'Comunidade' => '#community',
                'Propósito' => '#meet-up',
                'Palestrantes' => '#speakers',
                'Lineup' => '#lineup',
                'Ao vivo' => '#watch-live',
                'Parceiros' => '#partners',
                'Saiba mais' => '#about',
            ]
        ]"
    />
</x-filament-panels::page>
