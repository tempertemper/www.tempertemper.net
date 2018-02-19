<footer role="contentinfo">
    <nav class="useful-links">
        <?php
            perch_pages_navigation([
                'hide-extensions'  => true,
                'hide-default-doc' => true,
                'levels'           => 1,
            ]);
            perch_pages_navigation([
                'hide-extensions'   => true,
                'hide-default-doc'  => true,
                'levels'            => 2,
                'navgroup'          => 'footer-nav',
                'template'          => 'list.html'
            ]);
        ?>
        <div>
            <a href="https://twitter.com/tempertemper">
                <?php include(PERCH_PATH.'/templates/layouts/svg/twitter.svg'); ?>
                @tempertemper
            </a>
        </div>
    </nav>
  <div class="copyright">
    <p>&copy;&nbsp;copyright 2009&nbsp;to&nbsp;<?php echo date ("Y"); ?></p>
    <?php
      perch_content_custom('Primary content',[
        'page'     => '/contact',
        'template' => '/footer_address.html',
      ]);
    ?>
    <p>Company number 07742506, VAT&nbsp;registration number&nbsp;219&nbsp;5624&nbsp;96</p>
    <ul>
      <li><a href="/privacy-policy" rel="privacypolicy">Privacy policy</a>, </li>
      <li><a href="/terms">Terms&nbsp;of&nbsp;business</a></li>
    </ul>
  </div>
</footer>
