<script context="module">
    export { default as layout } from "../../Layouts/AuthenticatedLayout.svelte";
</script>

<script>
    export let news_articles;
</script>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div
            class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 block"
        >
            <h1 class="text-4xl font-bold block">Nieuwsartikelen</h1>
            <a class="block w-fit font-bold border bg-primary text-white my-4 px-8 py-2" href="{route('news.create')}">Nieuw artikel aanmaken</a>

            <div class="flex flex-col">
                {#each news_articles as news_article}
                    <div class="border border-2 mt-4 p-4">
                        <h2 class="text-xl font-bold">{news_article.title}</h2>
                        <span class="text-xs"><b>Gemaakt op</b> {news_article.created_at}</span>
                        <p class="text-lg">{news_article.content}</p>
                        <a class="font-bold underline mt-4" href={route("news.edit", news_article.id)}>Beheren</a>
                    </div>
                {/each}
            </div>
        </div>
    </div>
</div>
