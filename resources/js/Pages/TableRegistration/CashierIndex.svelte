<script context="module">
    export { default as layout } from "../../Layouts/AuthenticatedLayout.svelte";
</script>

<script>
    export let table_registrations;
</script>

<svelte:head>
    <title>Tafelbestellingen</title>
</svelte:head>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div
            class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 block"
        >
            <h1 class="text-4xl font-bold block">Tafelbestellingen</h1>

            <div class="flex flex-col">
                {#if table_registrations.length > 0}
                    {#each table_registrations as table_registration}
                        {#if table_registration['orders'].length > 0}
                            <div class="border border-2 mt-4 p-4 flex justify-between items-center">
                                <div>
                                    <h2 class="text-xl font-bold">Tafelnummer {table_registration['table'].table_number}</h2>
                                    <span class="text-xs"><b>Gemaakt op</b> {table_registration.created_at}</span>
                                </div>
                                <div>
                                    <a class="font-bold underline mt-4" href={route("table-registration.get-receipt-pdf", table_registration.id)}>Print Bon</a>
                                </div>
                            </div>
                        {/if}
                    {/each}
                {:else}
                    <p class="font-semibold mt-4">Er zijn geen tafelbestellingen gevonden</p>
                {/if}
            </div>
        </div>
    </div>
</div>
