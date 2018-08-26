<aside class="post-details">
    <p>Posted <span class="entry-published date" ><?php echo strftime("%e %B %Y", strtotime(perch_blog_post_field(perch_get('s'), 'postDateTime', true))); ?></span><?php perch_blog_post_categories(perch_get('s')); ?></p>
    <?php perch_blog_post_tags(perch_get('s')); ?>
    <p><a href="/blog/" class="back">Back to blog</a></p>
</aside>
