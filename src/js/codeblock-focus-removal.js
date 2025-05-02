// Grab each of the codeblock elements on the page (no inline code)
const codeblocks = document.querySelectorAll("pre code");

// Cycle through each codeblock in turn
codeblocks.forEach((codeblockInstance) => {

  // Codeblocks always live in a pre element; get the pre's inner width. This is the same space the codeblock takes up, but the codeblock's measurements don't take this full width into account; only the space that is actually taken up
  const codeblockWidth = codeblockInstance.parentElement.clientWidth;

  // Loop through the lines of code in the codeblock
  const codeblockChildren = codeblockInstance.querySelectorAll("span");

  // Get the widest line of code
  let widestLine = -1;
  codeblockChildren.forEach((child) => {
    const right = child.getBoundingClientRect().right;
    if (right > widestLine) widestLine = right;
  })

  // If the the widest line of code is the same size or smaller than the codeblock, remove the tabindex attribute as scrolling is not necessary
  if (widestLine <= codeblockWidth) {
    codeblockInstance.parentElement.removeAttribute("tabindex");
  }
});
