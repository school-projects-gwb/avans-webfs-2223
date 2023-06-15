<script context="module">
    export { default as layout } from "../../Layouts/AuthenticatedLayout.svelte";
</script>

<script>

</script>

<svelte:head>
    <title>Verkoop overzicht</title>
</svelte:head>

<div class="flex p-4 border-b-2 border-blue-600 m-2">
    <div class="w-1/2 2xl:w-4/12 mr-4 border border-blue-600 p-4 rounded-md flex flex-row items-center">
        <div class="flex flex-col">
            <div>
                <label class="inline-block mr-1">Begin datum:</label>
                <input class="py-0 float-right" type="date" border/>
            </div>
            <div class="mt-2">
                <label class="inline-block mr-1">Eind datum:</label>
                <input class="py-0 float-right" type="date" border/>
            </div>
        </div>
        <a class="block ml-4 bg-blue-100 py-4 px-1 border border-blue-600 rounded-lg text-blue-600 font-bold">Maak Overzicht</a>
    </div>
    <div class="w-1/2 2xl:w-8/12 border border-blue-600 p-4 rounded-md flex justify-between font-bold text-2xl">
        <div>Omzet:</div>
        <div>€ 0,00</div>
        <div>BTW:</div>
        <div>€ 0,00</div>
    </div>
</div>

<div class="flex p-4 border border-blue-600 mx-6 rounded-md justify-center">
    <table class="w-1/2">
        <thead class="border-b-2 border-blue-600">
            <th class="w-1/12 border-l border-r border-blue-600">Datum</th>
            <th class="w-1/2 border-l border-r border-blue-600">Gerecht</th>
            <th class="w-1/12 border-l border-r border-blue-600">Prijs</th>
            <th class="w-1/12 border-l border-r border-blue-600">Aantal</th>
            <th class="w-1/12 border-l border-r border-blue-600">Subtotaal</th>
        </thead>
    </table>
</div>
