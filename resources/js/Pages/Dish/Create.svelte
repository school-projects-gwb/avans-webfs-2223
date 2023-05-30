<script context="module">
    export { default as layout } from "../../Layouts/AuthenticatedLayout.svelte";
</script>

<script>
    import {useForm} from "@inertiajs/svelte";
    import TextInput from "@/Components/TextInput.svelte";
    import InputLabel from "@/Components/InputLabel.svelte";
    import InputError from "@/Components/InputError.svelte";
    import TextArea from "@/Components/TextArea.svelte";
    import Checkbox from "@/Components/Checkbox.svelte";
    import PrimaryButton from "@/Components/PrimaryButton.svelte";

    export let options, categories;

    const form = useForm({
        name: "",
        description: "",
        price: "",
        is_discount: false,
        option_required: false,
        option_amount: "",
        category_id: "",
        option_ids: ""
    });

    const submit = () => {
        $form.post(route("admin.dishes.store"));
    };
</script>

<svelte:head>
    <title>Gerecht aanmaken</title>
</svelte:head>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div
            class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8"
        >
            <a class="underline" href="{route('admin.dishes.index')}">Terug naar overzicht</a>
            <h1 class="text-4xl font-bold mt-4">Gerecht aanmaken</h1>

            <form class="flex flex-col mt-4 max-w-xl" on:submit|preventDefault={submit}>
                <div>
                    <InputLabel for="name" value="Naam *" />
                    <TextInput id="name" type="text" bind:value={$form.name} required />
                    <InputError message={$form.errors.name} />
                </div>

                <div class="mt-4">
                    <InputLabel for="description" value="Omschrijving *" />
                    <TextArea id="description" bind:value={$form.description} required />
                    <InputError message={$form.errors.description} />
                </div>

                <div class="mt-4">
                    <InputLabel for="price" value="Prijs *" />
                    <TextInput id="price" type="number" bind:value={$form.price} required />
                    <InputError message={$form.errors.price} />
                </div>

                <div class="mt-4">
                    <label class="flex items-center">
                        <Checkbox name="is_discount" bind:checked={$form.is_discount} />
                        <span class="ml-2 text-sm text-gray-600">Aanbieding</span>
                    </label>
                    <InputError message={$form.errors.is_discount} />
                </div>

                <div class="mt-4">
                    <InputLabel for="category" value="Categorie *" />
                    <select bind:value={$form.category_id} name="category_id" id="category" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                        {#each categories as category}
                            <option value="{category.id}">{category.name}</option>
                        {/each}
                    </select>
                    <InputError message={$form.errors.category_id} />
                </div>

                <h2 class="font-bold text-2xl mt-4">Extra's</h2>

                <div class="mt-4">
                    <label class="flex items-center">
                        <Checkbox name="option_required" bind:checked={$form.option_required} />
                        <span class="ml-2 text-sm text-gray-600">Extra's verplicht</span>
                    </label>
                    <InputError message={$form.errors.option_required} />
                </div>

                <div class="mt-4">
                    <InputLabel for="option_amount" value="Max. aantal extra's" />
                    <TextInput id="option_amount" type="number" bind:value={$form.option_amount} />
                    <InputError message={$form.errors.option_amount} />
                </div>

                <div class="mt-4">
                    {#each options as option}
                        <div>
                            <input type="checkbox" name="option_ids[]" bind:group={$form.option_ids} value="{option.id}"/>
                            <span>{option.name} - {option.price == null ? '(Verplichte optie)' : '(Optionele optie)'}</span>
                        </div>
                    {/each}
                    <InputError message={$form.errors.option_ids} />
                </div>

                <PrimaryButton disabled={$form.processing} classes="mt-4">
                    Gerecht aanmaken
                </PrimaryButton>

                <span class="font-bold mt-2">* : Verplichte velden</span>
            </form>
        </div>
    </div>
</div>
