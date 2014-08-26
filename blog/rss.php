<?php include($_SERVER['DOCUMENT_ROOT'].'/cms/runtime.php'); ?>

<?php
    header('Content-Type: application/rss+xml');

    echo '<'.'?xml version="1.0" encoding="UTF-8"?'.'>';
?>

<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	>
    <channel>
        <title>tempertemper Web Design</title>
        <link>http://tempertemper.net</link>
        <description>TemperTemper: Web Design for small businesses</description>
        <atom:link href="http://tempertemper.net/blog/rss" rel="self" type="application/rss+xml" />
        <?php
            perch_blog_custom(array(
                'template'=>'blog/latest_post_date.html',
                'count'=>1,
                'sort'=>'postDateTime',
                'sort-order'=>'DESC'
                ));
        ?>
        <?php
            perch_blog_custom(array(
                'template'=>'blog/rss_post.html',
                'count'=>10,
                'sort'=>'postDateTime',
                'sort-order'=>'DESC'
                ));
        ?>
    </channel>
</rss>