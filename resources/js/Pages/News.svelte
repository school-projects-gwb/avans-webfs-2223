<script context="module">
    export { default as layout } from "../Layouts/SiteLayout.svelte";
</script>

<script>
    export let news_articles;
</script>

<svelte:head>
    <title>The Golden Dragon</title>
</svelte:head>

{#each news_articles as news_article}
    <div class="mb-8">
        <h3 class="text-3xl font-bold">
            {news_article.title}
        </h3>
        <p class="text-lg">
            {news_article.content}
        </p>
        <span class="mt-1 text-sm font-bold">Geplaatst op {new Date(news_article.created_at).toISOString().substring(0, 10)}</span>
    </div>
{/each}
