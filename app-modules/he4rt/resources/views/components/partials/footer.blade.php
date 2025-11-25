@props([
    'bg' => 'bg-elevation-02dp',
])

<footer
    class="bg-elevation-surface/32 border-outline-dark relative z-10 border-t py-8 backdrop-blur-md sm:py-12 lg:py-16"
>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 content-start items-start gap-8 sm:gap-10 lg:grid-cols-2 lg:gap-12 xl:grid-cols-6">
            <div class="col-span-1 flex flex-col items-start gap-8 lg:col-span-2 lg:gap-12 xl:col-span-2">
                <div class="flex flex-col">
                    <x-he4rt::logo class="mb-0!" size="sm" />
                </div>
                <div class="flex flex-col gap-y-2 sm:gap-y-3">
                    <x-he4rt::text class="text-text-high font-semibold">Nosso Endereço</x-he4rt::text>
                    <x-he4rt::text size="sm" class="font-medium">
                        Dr. Cardoso de Mello, 1666, Cj, 92 Vila Olímpia, São Paulo
                    </x-he4rt::text>
                </div>
            </div>

            <div class="col-span-1">
                <x-he4rt::heading size="2xs" :level="4" class="mb-3 sm:mb-6">Links de Serviços</x-he4rt::heading>
                <ul class="text-text-medium space-y-2 text-sm sm:space-y-4">
                    <li>
                        <a href="/key-account" class="hover:text-secondary transition">Key Account</a>
                    </li>
                    <li>
                        <a href="/code-capital" class="hover:text-secondary transition">Code Capital</a>
                    </li>
                    <li>
                        <a
                            href="https://api.whatsapp.com/send/?phone=5511987201303&text=Visitei+o+site+da+Fire%7Cce+e+quero+mais+informa%C3%A7%C3%B5es&type=phone_number&app_absent=0"
                            class="hover:text-secondary transition"
                        >
                            Planejamento
                        </a>
                    </li>
                    <li>
                        <a href="https://www.educafire.com.br/mentoria" class="hover:text-secondary transition">
                            Educa Fire
                        </a>
                    </li>
                    <li>
                        <a href="/parcerias" class="hover:text-secondary transition">Parcerias</a>
                    </li>
                </ul>
            </div>

            <div class="col-span-1 flex flex-col gap-y-3 sm:gap-y-4">
                <x-he4rt::heading :level="4" size="2xs">Contato e endereço</x-he4rt::heading>
                <x-he4rt::text class="font-semibold" size="sm">contato@firece.com.br</x-he4rt::text>
                <div class="flex items-center gap-2">
                    <img
                        src="{{ asset('images/brasil-flag.png') }}"
                        alt="Phone"
                        class="h-4 w-6 rounded-sm object-contain sm:h-5 sm:w-7"
                    />
                    <x-he4rt::text class="font-semibold" size="sm">(11) 98720-1303</x-he4rt::text>
                </div>
            </div>

            <div class="col-span-1 flex flex-col gap-y-3 sm:gap-y-4 lg:col-span-2 xl:col-span-2">
                <x-he4rt::heading :level="4" size="2xs">Nossa Newsletter</x-he4rt::heading>
                <x-he4rt::text size="sm">
                    Envie nos o seu email e receba as melhores notícias e textos sobre o que acontece no mercado
                    financeiro
                </x-he4rt::text>
                <form class="flex flex-col items-stretch gap-3 sm:flex-row sm:items-center sm:gap-4">
                    <x-he4rt::input type="email" placeholder="Seu email" id="email" class="flex-1" />
                    <x-he4rt::button type="submit" class="shrink-0">Inscrever-se</x-he4rt::button>
                </form>
            </div>
        </div>
    </div>
</footer>
