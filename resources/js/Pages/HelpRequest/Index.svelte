<script context="module">
    export { default as layout } from "../../Layouts/AuthenticatedLayout.svelte";
</script>

<script>
    import axios from "axios";
    import {onMount} from "svelte";

    export let help_requests;

    onMount(async () => {
        setInterval(handleHelpRequestData, 10000);
    });

    async function handleHelpRequestData() {
        axios.get(`/help-requests/data`).then(response => {
            help_requests = response.data;
        });
    }

    async function handleDeleteRequest(helpRequestId) {
        axios.delete(`/help-requests/destroy/${helpRequestId}`).then(async response => {
            await handleHelpRequestData();
        });
    }
</script>

<svelte:head>
    <title>Hulpaanvragen</title>
</svelte:head>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div
            class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 block"
        >
            <h1 class="text-4xl font-bold block">Hulpaanvragen</h1>

            <div class="flex flex-col">
                {#if help_requests.length > 0}
                    {#each help_requests as help_request}
                        <div class="border border-2 mt-4 p-4">
                            <h2 class="text-xl font-bold">Tafelnummer: {help_request.table.table_number}</h2>
                            <span class="text-xs"><b>Gemaakt op</b> {help_request.created_at}</span>
                            <a class="font-bold underline mt-4 block cursor-pointer" on:click={handleDeleteRequest(help_request.id)}>Oplossen</a>
                        </div>
                    {/each}
                {:else}
                    <p class="font-semibold mt-4">Er zijn geen hulpaanvragen gevonden</p>
                {/if}

            </div>
        </div>
    </div>
</div>

