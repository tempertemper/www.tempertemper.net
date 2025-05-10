function checkCodeOverflow() {
  // Get all code elements inside pre elements
  const codeElements = document.querySelectorAll("pre > code");

  // If none are found, log an error and return
  if (!codeElements || codeElements.length === 0) {
    return null;
  }

  // Loop through all code elements
  codeElements.forEach((codeElement) => {
    // Get the parent pre element
    const preElement = codeElement.closest("pre");
    // If no parent pre element is found, log an error and return
    if (!preElement) {
      return null;
    }

    // Compare the scrollWidth (total content width) of `pre` to the clientWidth (visible width) of `pre`
    const isOverflowing = preElement.scrollWidth > preElement.clientWidth;

    // If the pre element is overflowing, set the tabindex to 0
    preElement.setAttribute("tabindex", isOverflowing ? 0 : -1);
  });
}

// Run on page load
document.addEventListener("DOMContentLoaded", checkCodeOverflow);
// document.addEventListener("load", checkCodeOverflow());

// Also run on window resize
let resizeTimer;
window.addEventListener("resize", () => {
  clearTimeout(resizeTimer);
  resizeTimer = setTimeout(() => {
    checkCodeOverflow();
  }, 250);
});
