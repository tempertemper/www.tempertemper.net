function findWidestRight(element) {
  let maxRight = 0;
  let widestElement;

  function traverse(node) {
    if (node.nodeType === Node.ELEMENT_NODE) {
      const style = window.getComputedStyle(node);
      const rightValue = node.getBoundingClientRect().right;

      if (rightValue > maxRight) {
        maxRight = rightValue;
        widestElement = node;
      }

      for (let i = 0; i < node.childNodes.length; i++) {
        traverse(node.childNodes[i]);
      }
    } else if (node.nodeType === Node.TEXT_NODE) {
      return;
    }
  }

  traverse(element);

  return { maxRight, widestElement };
}

const codeblocks = document.querySelectorAll("pre code");

codeblocks.forEach((codeblock, i) => {

  // console.log(codeblock.getBoundingClientRect());
  const codeblockWidth = codeblock.getBoundingClientRect().width;
  const codeblockLeft = codeblock.getBoundingClientRect().left;

  // console.log(`codeblock ${i + 1} is ${codeblockWidth} wide`);

  // console.log(`Codeblock ${i + 1}’s left is ${codeblockLeft}px`);

  const { maxRight: widestRight, widestElement: element } =
    findWidestRight(codeblock);
  // console.log(`Widest child of ${i + 1} is ${widestRight}px`);

  const actualWidestRight = widestRight - codeblockLeft;
  // console.log(`Actual width of ${i + 1} is ${actualWidestRight}px`);

  console.log(element)
  if (actualWidestRight <= codeblockWidth) {
    codeblock.parentElement.removeAttribute("tabindex");
  }
});
