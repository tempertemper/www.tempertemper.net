// Grab each of the codeblock elements on the page
const codeblocks = document.querySelectorAll("pre");

// Cycle through each codeblock in turn
codeblocks.forEach((codeblockInstance) => {

  // Get the codeblock's container and its width
  const containerWidth = codeblockInstance.parentElement.offsetWidth;

  // Get the codeblock's width
  const codeblockWidth = codeblockInstance.offsetWidth;

  // If the codeblock is the same size or smaller than its container, remove the tabindex attribute
  if (codeblockWidth <= containerWidth) {
    codeblockInstance.parentElement.removeAttribute("tabindex");
  }
});
