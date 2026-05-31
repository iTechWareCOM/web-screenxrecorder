<?php
$faqSchema = [
    '@context'   => 'https://schema.org',
    '@type'      => 'FAQPage',
    'mainEntity' => array_map(function($faq) {
        return [
            '@type' => 'Question',
            'name'  => $faq['question'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text'  => strip_tags($faq['answer'])
            ]
        ];
    }, $translation['faqs'])
];
?>
<script type="application/ld+json"><?=json_encode($faqSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)?></script>
<article class="legalPage">
    <h1><?=htmlspecialchars($translation['h1'], ENT_QUOTES, 'UTF-8')?></h1>
    <?=$translation['intro']?>
    <dl class="faqList">
        <?php foreach ($translation['faqs'] as $faq): ?>
        <dt><?=htmlspecialchars($faq['question'], ENT_QUOTES, 'UTF-8')?></dt>
        <dd><?=$faq['answer']?></dd>
        <?php endforeach; ?>
    </dl>
</article>
