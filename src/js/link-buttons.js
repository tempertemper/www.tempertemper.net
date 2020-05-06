// Adapted from @jonhurrel's code on this GitHub Issue: https://github.com/alphagov/govuk_elements/pull/272
(function () {
  'use strict';
  function a11yClick(link) {
    // If the spacebar is pressed on a focussed button, don't scroll the page down
    link.addEventListener('keydown', function(event) {
      var code = event.charCode || event.keyCode;
      if (code === 32) {
        event.preventDefault();
      }
    });
    // When the spacebar is released on a focussed button, click the button
    link.addEventListener('keyup', function(event) {
      var code = event.charCode || event.keyCode;
      if (code === 32) {
        event.preventDefault();
        link.click();
      }
    });
  }
  // Apply this to every link with a role of "button" on the page
  var a11yLink = document.querySelectorAll('a[role="button"]');
  for ( var i = 0; i < a11yLink.length; i++ ) {
    a11yClick( a11yLink[i] );
  }
})();
