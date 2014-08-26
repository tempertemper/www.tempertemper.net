<footer role="contentinfo">

  <aside>

    <h1>Keep in Touch</h1>

    <ul class="keep-in-touch">

      <li>
        <a href="/mailing-list/subscribe" class="mailing-list icon" title="Subscribe to mailing list">
            <span aria-hidden="true" data-icon="&#9993;"></span>
            <span class="screen-reader-text">Mailing list</span>
        </a>
      </li>

      <li>
        <a href="http://twitter.com/tempertemper" class="twitter icon" title="Head to my Twitter page">
          <span aria-hidden="true" data-icon="&#116;"></span>
          <span class="screen-reader-text">Twitter</span>
        </a>
      </li>

      <li>
        <a href="http://www.facebook.com/tempertemperwebdesign" class="facebook icon" title="Head to my Facebook page">
          <span aria-hidden="true" data-icon="&#102;"></span>
          <span class="screen-reader-text">Facebook</span>
        </a>
      </li>

    </ul>

  </aside>

  <nav class="useful-links">

    <h1>Useful links</h1>

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
        'page'      => '/contact/index.php',
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