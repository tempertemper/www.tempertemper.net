// Grab each of the table elements on the page
const tables = document.querySelectorAll("table");

// Cycle through each table in turn
tables.forEach((tableInstance) => {

  // Get the table's container and its width
  const containerWidth = tableInstance.parentElement.offsetWidth;

  // Get the table's width
  const tableWidth = tableInstance.offsetWidth;

  // If the table is the same size or smaller than its container, remove the tabindex attribute
  if (tableWidth <= containerWidth) {
    tableInstance.parentElement.removeAttribute("tabindex");
  }
});
