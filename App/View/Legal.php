<article class="legalPage">
    <h1><?=htmlspecialchars($translation['h1'], ENT_QUOTES, 'UTF-8')?></h1>
    <p class="legalPage__date"><?=htmlspecialchars($translation['lastUpdated'], ENT_QUOTES, 'UTF-8')?></p>
    <?=$translation['intro']?>
    <?php foreach ($translation['sections'] as $section): ?>
    <section class="legalPage__section">
        <h2><?=htmlspecialchars($section['title'], ENT_QUOTES, 'UTF-8')?></h2>
        <?=$section['content']?>
    </section>
    <?php endforeach; ?>
</article>
