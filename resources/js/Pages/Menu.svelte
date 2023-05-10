<script context="module">
    export { default as layout } from "../Layouts/SiteLayout.svelte";
</script>

<script>
    import Menu from "../Components/Menu.svelte";
    export let dish_data;
    export let option_data;
</script>

<svelte:head>
    <title>The Golden Dragon</title>
</svelte:head>

<Menu dish_data={dish_data} option_data={option_data} />

