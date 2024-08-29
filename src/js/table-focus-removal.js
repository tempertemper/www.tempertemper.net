// Get the table's width
const container = document.querySelector(".table-wrapper");
const containerWidth = container.offsetWidth;
// console.log("The table is " + tableWidth + " pixels wide.");

// Get the table's container's width
const table = document.querySelector("table");
const tableWidth = table.offsetWidth;
// console.log("The container is " + containerWidth + " pixels wide.");

// Compare the table's width and its container's width; if the table is smaller (i.e. the screen is nice and large), remove the tabindex attribute so that keyboard users don't tab onto it when they don't need to
(function() {
  if (tableWidth < containerWidth) {
    // console.log("The table is smaller than its container");
    container.removeAttribute("tabindex");
  // } else {
  //   console.log("The table is bigger than its container");
  }
})();
