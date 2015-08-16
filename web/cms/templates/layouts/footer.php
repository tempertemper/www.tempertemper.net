<footer role="contentinfo">

  <nav class="useful-links">

    <?php
      perch_pages_navigation(array(
        'hide-extensions'  => true,
        'hide-default-doc' => true,
        'levels'           => 1,
      ));

      perch_pages_navigation(array(
        'hide-extensions'   => true,
        'hide-default-doc'  => true,
        'levels'            => 2,
        'navgroup'          => 'footer-nav',
        'template'          => 'list.html'
      ));
    ?>

    <div>
      <a href="https://twitter.com/tempertemper">
        <svg viewBox="0 0 43 35" version="1.1" class="icon-twitter" aria-labelledby="title">
          <title>Twitter</title>
          <path id="twitter" d="M43,4.143c-1.582,0.703 -3.282,1.178 -5.067,1.391c1.821,-1.093 3.22,-2.825 3.879,-4.888c-1.705,1.013 -3.593,1.748 -5.602,2.144c-1.609,-1.717 -3.902,-2.79 -6.439,-2.79c-4.872,0 -8.822,3.956 -8.822,8.835c0,0.693 0.078,1.367 0.228,2.014c-7.332,-0.368 -13.832,-3.886 -18.183,-9.232c-0.76,1.305 -1.195,2.823 -1.195,4.442c0,3.066 1.558,5.77 3.925,7.355c-1.446,-0.046 -2.807,-0.444 -3.996,-1.105c-0.001,0.036 -0.001,0.073 -0.001,0.111c0,4.281 3.041,7.852 7.077,8.664c-0.74,0.201 -1.52,0.31 -2.324,0.31c-0.569,0 -1.121,-0.056 -1.66,-0.159c1.122,3.51 4.38,6.065 8.241,6.136c-3.019,2.37 -6.823,3.783 -10.957,3.783c-0.712,0 -1.414,-0.042 -2.104,-0.124c3.904,2.507 8.541,3.97 13.523,3.97c16.227,0 25.1,-13.464 25.1,-25.14c0,-0.383 -0.008,-0.764 -0.025,-1.143c1.724,-1.246 3.219,-2.802 4.402,-4.574Z" style="fill:#0097db;"/>
        </svg>
        @tempertemper
      </a>
    </div>

  </nav>

  <div class="copyright">

    <p>&copy;&nbsp;copyright 2009&nbsp;to&nbsp;<?php echo date ("Y"); ?></p>

    <?php
      $opts = array(
        'page'     => '/contact',
        'template' => '/footer_address.html',
      );
      perch_content_custom('Primary content',$opts);
    ?>

    <p>VAT registration number 219 5624 96</p>

    <ul>
      <li><a href="/legal/privacy-policy" rel="privacypolicy">Privacy policy</a>, </li>
      <li><a href="/legal/terms">Terms&nbsp;of&nbsp;business</a></li>
    </ul>

  </div>

</footer>