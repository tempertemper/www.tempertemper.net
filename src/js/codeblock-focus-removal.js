function shouldRemoveTabIndex(preElement) {
  const codeElement = document.querySelector("pre");
  if (!codeElement) return false;

  const lines = preElement.innerText
    .split("\n")
    .filter((line) => line.trim() !== "");

  // Get the container width
  const containerWidth = preElement.clientWidth;

  // Create a temporary span to measure text width accurately
  const measureSpan = document.createElement("span");
  measureSpan.style.position = "absolute";
  measureSpan.style.visibility = "hidden";
  measureSpan.style.whiteSpace = "pre"; // Important: preserve whitespace
  measureSpan.style.font = window.getComputedStyle(codeElement).font;
  document.body.appendChild(measureSpan);

  // Find the widest line
  let maxWidth = 0;
  let widestLine = "";

  // Iterate through each line and measure its width
  lines.forEach((line) => {
    measureSpan.textContent = line;
    const lineWidth = measureSpan.getBoundingClientRect().width;

    if (lineWidth > maxWidth) {
      maxWidth = lineWidth;
      widestLine = line;
    }
  });

  // Clean up
  document.body.removeChild(measureSpan);
  
  // Return true if we should remove the tabIndex (no scrolling needed)
  return maxWidth <= containerWidth;
}

// Process all pre elements on the page
function processAllCodeBlocks() {
  const preElements = document.querySelectorAll("pre");

  preElements.forEach((preElement) => {
    // Check if this pre element already has a tabIndex
    const hasTabIndex = preElement.hasAttribute("tabindex");

    if (hasTabIndex && shouldRemoveTabIndex(preElement)) {
      preElement.removeAttribute("tabindex");
    } else if (!hasTabIndex && !shouldRemoveTabIndex(preElement)) {
      preElement.setAttribute("tabindex", "0");
    }
  });
}

// Run when the DOM is fully loaded
document.addEventListener("DOMContentLoaded", processAllCodeBlocks);

// Also run on window resize
let resizeTimer;
window.addEventListener("resize", () => {
  clearTimeout(resizeTimer);
  resizeTimer = setTimeout(processAllCodeBlocks, 250);
});
