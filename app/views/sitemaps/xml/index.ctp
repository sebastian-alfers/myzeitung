<?php /* <urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"
         xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"> */ ?>
<urlset>
      <url>
        <loc><?php echo Router::url('/', true); ?></loc>
        <lastmod><?php echo trim($time->toAtom(time())); ?></lastmod>
        <changefreq>daily</changefreq>
      <?php //  <priority>1.0</priority> ?>
    </url>
<?php


    if( isset($statics) && !empty($statics) ):
        foreach($statics as $static):?>

            <?php $this->log( Router::url($static['url'], true)); ?>
            <url>
            <loc><?php echo Router::url($static['url'], true); ?></loc>
        <lastmod><?php echo trim($time->toAtom(time())); ?></lastmod>
    <?php /*   <priority><?php echo $static['options']['pr'] ?></priority> */?>
        <changefreq><?php echo $static['options']['changefreq'] ?></changefreq>
    </url>
<?php
    endforeach;
endif;

    if( isset($dynamics) && !empty($dynamics) ):
    foreach ($dynamics as $dynamic):?>
    <url>   
        <loc><?php echo $dynamic['options']['url'];?></loc>
        <lastmod><?php echo trim($time->toAtom(time())); ?></lastmod>
    <?php /*    <priority><?php echo $dynamic['options']['pr'] ?></priority> */?>
        <changefreq><?php echo $dynamic['options']['changefreq'] ?></changefreq>
    </url>
    <?php foreach ($dynamic['data'] as $section):?> 
    <url>
        <?php
            $url = '';
            if($dynamic['model'] == 'Paper' || $dynamic['model'] == 'Post'){
                $url = Router::url($section['Route'][0]['source'], true);
            }elseif($dynamic['model'] == 'User'){
                $url = Router::url('/u/'.strtolower($section['User']['username']), true);
            }

        ?>
        <loc><?php echo $url; ?></loc>
        <lastmod><?php echo trim($time->toAtom($section[$dynamic['model']][$dynamic['options']['fields']['date']]))?></lastmod> 
    <?php /*   <priority><?php echo $dynamic['options']['pr'] ?></priority> */ ?>
        <changefreq><?php echo $dynamic['options']['changefreq'] ?></changefreq>
    </url> 
    <?php endforeach;
    endforeach;
endif; ?> 
</urlset> 