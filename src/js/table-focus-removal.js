function tableOverflow() {
  // Grab each of the table wrappers on the page
  const tableWrappers = document.querySelectorAll(".table-wrapper");

  // Cycle through each table wrapper in turn
  tableWrappers.forEach((tableWrapper) => {
    // Compare the scrollWidth (total content width) to the clientWidth (visible width)
    const isOverflowing = tableWrapper.scrollWidth > tableWrapper.clientWidth;

    // If the table wrapper is overflowing, include it in the page's tab order
    if (isOverflowing) {
      tableWrapper.setAttribute("tabindex", "0");
    } else {
      tableWrapper.removeAttribute("tabindex");
    }
  });
}

// Run on page load
document.addEventListener("DOMContentLoaded", tableOverflow);

// Also run on window resize
let resizeTimerTables;
window.addEventListener("resize", () => {
  clearTimeout(resizeTimerTables);
  resizeTimerTables = setTimeout(() => {
    tableOverflow();
  }, 250);
});
