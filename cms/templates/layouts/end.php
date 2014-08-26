
  <?php perch_get_javascript(); ?>

  <script>
    var navigation = responsiveNav(".nav-collapse", {
      animate: true,
      transition: 284,
      label: "Menu",
      insert: "before",
      customToggle: "nav-toggle",
      closeOnNavClick: false,
      openPos: "relative",
      navClass: "nav-collapse",
      navActiveClass: "js-nav-active",
      jsClass: "js",
    });
  </script>

  <?php perch_content('Analytics'); ?>

</body>
</html>