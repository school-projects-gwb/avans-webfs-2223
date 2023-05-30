<script context="module">
    export { default as layout } from "../../Layouts/TableRegistrationLayout.svelte";
</script>

<script>
    import {useForm} from "@inertiajs/svelte";
    import InputError from "@/Components/InputError.svelte";

    const form = useForm({
        table_number: 1
    });

    const submit = () => {
        $form.post(route("table-registration.start-order"));
    };
</script>

<svelte:head>
    <title>Tafelreservatie</title>
</svelte:head>

<form class="flex flex-col mt-16" on:submit|preventDefault={submit}>
    <a href="/" class="font-bold underline">Terug naar website</a>
    <h1 class="text-4xl font-bold text-primary text-left mt-4">Start hier uw tafelbestelling</h1>
    <label for="table-number" class="text-primary text-xl font-bold mt-8">Uw Tafelnummer</label>
    <input id="table-number" type="number" min="1" bind:value={$form.table_number}/>
    <InputError message={$form.errors.table_number} />
    <input type="submit" class="bg-primary text-white py-2 text-xl uppercase border-none font-bold px-4 mt-8 cursor-pointer" value="Start bestelling">
</form>
