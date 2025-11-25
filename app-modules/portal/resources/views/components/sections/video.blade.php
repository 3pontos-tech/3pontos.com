<script>
    function scrollZoomLogic() {
        return {
            scale: 1,
            visible: false,

            minScale: 1,
            maxScale: 3.3,
            animationCutoff: 0.7,

            init() {
                this.setMaxScale();
                window.addEventListener('resize', () => {
                    this.setMaxScale();
                    this.calculateProgress();
                });
                this.scrollHandler = () => {
                    window.requestAnimationFrame(() => this.calculateProgress());
                };
                window.addEventListener('scroll', this.scrollHandler, { passive: true });
                this.calculateProgress();
            },

            setMaxScale() {
                if (window.innerWidth < 821) {
                    this.maxScale = 6;
                } else {
                    this.maxScale = 3.3;
                }
            },

            destroy() {
                window.removeEventListener('scroll', this.scrollHandler);
            },

            calculateProgress() {
                const container = this.$el;
                const rect = container.getBoundingClientRect();
                const viewportHeight = window.innerHeight;
                const scrollableDistance = container.offsetHeight - viewportHeight;
                let scrollPosition = -rect.top;

                let rawProgress = Math.max(0, Math.min(1, scrollPosition / scrollableDistance));

                if (rawProgress >= this.animationCutoff) {
                    this.scale = this.maxScale;
                    this.visible = true;
                } else {
                    let zoomProgress = rawProgress / this.animationCutoff;
                    this.scale = this.minScale + (this.maxScale - this.minScale) * zoomProgress;
                    this.visible = false;
                }
            },
        };
    }
</script>

<section id="video" class="relative h-[300vh]" x-data="scrollZoomLogic()">
    <div class="sticky top-0 flex h-screen w-full items-center justify-center overflow-hidden">
        <div class="hp-container relative z-10 flex items-center justify-center">
            <img
                src="{{ asset('images/3pontos/logo-chain-white.png') }}"
                alt="logo chain"
                class="relative z-20 origin-center will-change-transform"
                :style="`transform: scale(${scale})`"
            />

            <div class="pointer-events-none absolute inset-0 z-10 flex items-center justify-center">
                <x-he4rt::animate-block
                    type="blur"
                    duration="700"
                    class="pointer-events-auto h-[62%] w-[50%] overflow-hidden rounded-lg bg-black shadow-xl sm:h-[34%] sm:w-[25%]"
                >
                    <video class="h-full w-full object-cover" autoplay muted controls playsinline>
                        <source src="{{ asset('videos/3pontos-video.mp4') }}" type="video/mp4" />
                    </video>
                </x-he4rt::animate-block>
            </div>
        </div>
    </div>
</section>
