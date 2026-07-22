function updateScrollContainerFocus() {
  const scrollContainers = document.querySelectorAll("pre, .table-wrapper");

  scrollContainers.forEach((scrollContainer) => {
    const isOverflowing = scrollContainer.scrollWidth > scrollContainer.clientWidth;

    if (isOverflowing) {
      scrollContainer.setAttribute("tabindex", "0");
    } else {
      scrollContainer.removeAttribute("tabindex");
    }
  });
}

document.addEventListener("DOMContentLoaded", updateScrollContainerFocus);

let resizeTimer;
window.addEventListener("resize", () => {
  clearTimeout(resizeTimer);
  resizeTimer = setTimeout(() => {
    updateScrollContainerFocus();
  }, 250);
});
