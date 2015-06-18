<footer role="contentinfo">

  <nav class="useful-links">

    <?php
      perch_pages_navigation(array(
        'hide-extensions'   => true,
        'hide-default-doc'  => true,
        'levels'            => 2,
        'navgroup'          => 'footer-nav',
        'template'          => 'list.html'
      ));
    ?>

  </nav>

  <div class="copyright">

    <p>&copy;&nbsp;copyright 2009&nbsp;to&nbsp;<?php echo date ("Y"); ?></p>

    <?php
      $opts = array(
        'page'      => '/contact',
        'template'  => '/_address.html',
      );
      perch_content_custom('Primary content',$opts);
    ?>

    <ul>
      <li><a href="/legal/privacy-policy" rel="privacypolicy">Privacy policy</a>, </li>
      <li><a href="/legal/terms">Terms&nbsp;of&nbsp;business</a></li>
    </ul>

  </div>

</footer>