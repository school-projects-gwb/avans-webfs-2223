<script context="module">
    export { default as layout } from "../Layouts/SiteLayout.svelte";
</script>

<script>
    import Menu from "../Components/Menu.svelte";
</script>

<svelte:head>
    <title>The Golden Dragon</title>
</svelte:head>

<Menu sortable={true} />

