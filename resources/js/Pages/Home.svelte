<script context="module">
    export { default as layout } from "../Layouts/SiteLayout.svelte";
</script>

<script>
    export let highlighted_offer, restaurant;
</script>

<svelte:head>
    <title>The Golden Dragon</title>
</svelte:head>

<div class="flex flex-col justify-center items-center my-8">
    <h3 class="w-11/12 lg:w-1/2 text-lg font-bold tracking-tight">
        {restaurant.home_description}
    </h3>
    {#if highlighted_offer}
        <b class="text-2xl underline mt-8">{highlighted_offer.description}</b>
        <b class="text-3xl mt-4">{highlighted_offer.name}</b>
        {#if highlighted_offer.options.length > 0 && highlighted_offer.option_required != null}
            <p class="mt-6 text-lg font-bold">Maak een keuze uit {highlighted_offer.option_amount} van onderstaande keuzegerechten:</p>
            <div class="grid grid-cols-2 text-lg mt-2 gap-2">
                {#each highlighted_offer.options as option}
                    {#if option.price == null}
                        <span class="font-bold">{ option.name }</span>
                    {/if}
                {/each}
            </div>

            <div class="flex flex-col items-start justify-start">
                <p class="mt-4 font-bold">Mogelijke extra's (voor meerprijs):</p>
                {#each highlighted_offer.options as option}
                    {#if option.price != null}
                        <span>{option.name} ({option.condition_text}) - €{option.price}</span>
                    {/if}
                {/each}
            </div>
        {/if}
    {/if}
    <a href="/menu" class="mt-8 underline font-bold text-xl">Bekijk ons volledige menu</a>
</div>


