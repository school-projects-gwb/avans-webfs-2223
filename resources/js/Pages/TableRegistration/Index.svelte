<script context="module">
    export { default as layout } from "../../Layouts/TableRegistrationLayout.svelte";
</script>

<script>
    import {useForm} from "@inertiajs/svelte";

    const form = useForm({
        table_number: 1
    });

    const submit = () => {
        $form.post(route("table-registration.start-order"));
    };
</script>

<form class="flex flex-col mt-16" on:submit|preventDefault={submit}>
    <h1 class="text-4xl font-bold text-secondary text-left">Start hier uw tafelbestelling</h1>
    <label for="table-number" class="text-white text-xl font-bold mt-8">Uw Tafelnummer</label>
    <input id="table-number" type="number" min="1" class="bg-primary" bind:value={$form.table_number}/>
    <input type="submit" class="bg-primary text-white py-2 text-xl uppercase border-none font-bold px-4 mt-8" value="Start bestelling">
</form>
