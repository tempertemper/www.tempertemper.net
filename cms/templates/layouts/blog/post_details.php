<aside class="post-details">

  <p>Posted <span class="entry-published date" ><?php echo strftime("%d %B, %Y", strtotime(perch_blog_post_field(perch_get('s'), 'postDateTime', true))); ?></span> in <span class="cats"><?php perch_blog_post_categories(perch_get('s')); ?></span></p>
  <p class="tags">Tagged with <?php perch_blog_post_tags(perch_get('s')); ?></p>

</aside>