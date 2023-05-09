<script>
    import { Link } from "@inertiajs/svelte";
    import ApplicationLogo from "../Components/ApplicationLogo.svelte";
    import DragonFacingLeft from '../../img/dragon-left-facing.png'
    import DragonFacingRight from '../../img/dragon-right-facing.png'
</script>

<div
    class="min-h-screen h-screen flex flex-col items-center pt-6 sm:pt-0 bg-primary-dark"
>
    <div class="w-11/12 mt-16 p-4 relative z-10 bg-primary flex flex-col items-center">
        <!-- Header -->
        <div class="flex w-full justify-between">
            <Link href="/" class="w-2/8">
                <ApplicationLogo classes="w-20 h-20 fill-current text-gray-500" />
            </Link>
            <div class="w-1/2 text-secondary text-center">
                Welkom bij De Gouden Draak! Klik op deze tekst om de aanbiedingen van deze week te zien!
            </div>
            <Link href="/" class="w-2/8">
                <ApplicationLogo classes="w-20 h-20 fill-current text-gray-500" />
            </Link>
        </div>
        <div class="mt-36 w-11/12 flex flex-col justify-center text-center">
            <div class="flex w-full justify-between">
                <img src="{DragonFacingRight}" class="w-44 h-44">
                <div>
                    <h1 class="text-4xl font-semibold text-secondary">Chinees Indische Specialiteiten</h1>
                    <h1 class="text-5xl font-bold text-secondary mt-2">De Gouden Draak</h1>
                    <div class="mt-6 border border-white w-fit m-auto">
                        <a href="#" class="text-white text-xl px-8 bg-menu-item">Menukaart</a>
                        <a href="#" class="text-white text-xl px-8 bg-menu-item">Nieuws</a>
                        <a href="#" class="text-white text-xl px-8 bg-menu-item">Contact</a>
                    </div>
                </div>
                <img src="{DragonFacingLeft}" class="w-44 h-44">
            </div>
            <slot />
        </div>
        <div class="w-full h-full absolute top-0 left-0 -z-10 bg-primary mt-24 p-2">
            <div class="flex flex-col h-full">
                <div>
                    <div class="flex">
                        <!-- Row 1 -->
                        <span class="w-8 h-8 border-corner-top-left"></span>
                        <span class="w-8 h-8 border-corner-top-right"></span>
                        <span class="w-8 h-8 border-corner-bottom-right"></span>
                        <span class="w-0 flex-1 h-8 border-fill-horizontal"></span>
                        <span class="w-8 h-8 border-corner-bottom-left"></span>
                        <span class="w-8 h-8 border-corner-top-left"></span>
                        <span class="w-8 h-8 border-corner-top-right"></span>
                    </div>
                </div>
                <div class="-mt-1">
                    <div class="flex">
                        <!-- Row 2 -->
                        <span class="w-8 h-8 border-corner-bottom-left"></span>
                        <span class="w-8 h-8 border-corner-all"></span>
                        <span class="w-8 h-8 border-corner-bottom-right"></span>
                        <span class="w-0 flex-1 h-8"></span>
                        <span class="w-8 h-8 border-corner-bottom-left"></span>
                        <span class="w-8 h-8 border-corner-all"></span>
                        <span class="w-8 h-8 border-corner-bottom-right"></span>
                    </div>
                </div>
                <div class="-mt-1">
                    <div class="flex">
                        <!-- Row 3 -->
                        <span class="w-8 h-8"></span>
                        <span class="w-8 h-8 border-corner-all"></span>
                        <span class="w-8 h-8"></span>
                        <span class="w-0 flex-1 h-8"></span>
                        <span class="w-8 h-8"></span>
                        <span class="w-8 h-8 border-corner-all"></span>
                        <span class="w-8 h-8"></span>
                    </div>
                </div>
                <div class="-mt-1 flex flex-1">
                    <div class="flex w-full">
                        <!-- Row 4 -->
                        <span class="w-8 border-corner-all"></span>
                        <span class="w-8 h-8"></span>
                        <span class="w-8 h-8"></span>
                        <span class="w-0 flex-1 h-8"></span>
                        <span class="w-8 h-8"></span>
                        <span class="w-8 h-8"></span>
                        <span class="w-8 border-corner-all"></span>
                    </div>
                </div>
                <div class="-mt-1">
                    <div class="flex ">
                        <!-- Row 5 -->
                        <span class="w-8 h-8"></span>
                        <span class="w-8 h-8 border-corner-all"></span>
                        <span class="w-8 h-8"></span>
                        <span class="w-0 flex-1 h-8"></span>
                        <span class="w-8 h-8"></span>
                        <span class="w-8 h-8 border-corner-all"></span>
                        <span class="w-8 h-8"></span>
                    </div>
                </div>
                <div class="-mt-1">
                    <div class="flex ">
                        <!-- Row 6 -->
                        <span class="w-8 h-8 border-corner-top-left"></span>
                        <span class="w-8 h-8 border-corner-all"></span>
                        <span class="w-8 h-8 border-corner-top-right"></span>
                        <span class="w-0 flex-1 h-8"></span>
                        <span class="w-8 h-8 border-corner-top-left"></span>
                        <span class="w-8 h-8 border-corner-all"></span>
                        <span class="w-8 h-8 border-corner-top-right"></span>
                    </div>
                </div>
                <div class="-mt-1">
                    <div class="flex ">
                        <!-- Row 7 -->
                        <span class="w-8 h-8 border-corner-bottom-left"></span>
                        <span class="w-8 h-8 border-corner-bottom-right"></span>
                        <span class="w-8 h-8 border-corner-top-right"></span>
                        <span class="w-0 flex-1 h-8 border-fill-horizontal"></span>
                        <span class="w-8 h-8 border-corner-top-left"></span>
                        <span class="w-8 h-8 border-corner-bottom-left"></span>
                        <span class="w-8 h-8 border-corner-bottom-right"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
