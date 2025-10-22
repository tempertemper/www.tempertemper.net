document.addEventListener("DOMContentLoaded", () => {
  const utilityList = document.querySelector(".footer .utility:last-of-type");
  if (!utilityList) return;

  const li = document.createElement("li");
  li.innerHTML = '<a href="/search">Search</a>';
  utilityList.prepend(li);
});
