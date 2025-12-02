document.addEventListener("DOMContentLoaded", () => {
  const utilityList = document.querySelector(".header .navigation ul");
  if (!utilityList) return;

  const li = document.createElement("li");
  li.innerHTML = '<a href="/search">Search</a>';

  const items = utilityList.querySelectorAll("li");

  if (items.length >= 1) {
    utilityList.insertBefore(li, items[items.length - 1]);
  } else {
    utilityList.append(li);
  }
});
