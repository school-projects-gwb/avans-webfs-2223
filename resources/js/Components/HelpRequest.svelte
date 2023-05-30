<script>
    import axios from 'axios';
    import {onMount} from "svelte";

    export let table_id;

    let is_request_pending;

    onMount(async () => {
        await handleHelpRequestData();
        handleHelpRequestStatus();
    });

    async function handleHelpRequestData() {
        axios.get(`/help-requests/get/${table_id}`).then(response => {
            is_request_pending = response.data.length !== 0;
        });
    }

    async function handleCreateHelpRequest() {
        axios.post(`/help-requests/create/${table_id}`).then(response => {
            is_request_pending = response.data === 1;
        });
    }

    function handleHelpRequestStatus() {
        setInterval(() => {
            handleHelpRequestData();
        }, 10000);
    }
</script>

<div class="mt-4">
    {#if !is_request_pending}
        <a class="underline cursor-pointer" on:click={handleCreateHelpRequest}>Schakel hulp van ober in</a>
    {:else}
        <p>Hulpaanvraag verstuurd. U wordt spoedig geholpen.</p>
    {/if}
</div>
