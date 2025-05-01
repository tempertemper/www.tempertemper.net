// Grab each of the codeblock elements on the page
const codeblocks = document.querySelectorAll("code");

// Cycle through each codeblock in turn
codeblocks.forEach((codeblockInstance) => {

  // Get the codeblock's container and its width
  const containerWidth = codeblockInstance.parentElement.clientWidth;

  // Loop through the children
  const codeblockChildren = codeblockInstance.querySelectorAll("span");

  // Get the widest child
  let biggestWidth = -1;
  codeblockChildren.forEach((child) => {
    const right = child.getBoundingClientRect().right;
    if (right > biggestWidth) biggestWidth = right;
  })

  // If the codeblock is the same size or smaller than its container, remove the tabindex attribute
  if (biggestWidth <= containerWidth) {
    codeblockInstance.parentElement.removeAttribute("tabindex");
  }
});
