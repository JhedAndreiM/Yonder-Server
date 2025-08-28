document.querySelectorAll(".box").forEach((box) => {
  box.addEventListener("click", () => {
    const content = box.querySelector(".box-content");

    if (box.classList.contains("active")) {
      // Collapse
      content.style.height = content.scrollHeight + "px";
      requestAnimationFrame(() => {
        content.style.height = "0";
        content.style.opacity = "0";
      });
      box.classList.remove("active");
    } else {
      // Expand
      content.style.height = content.scrollHeight + "px";
      content.style.opacity = "1";
      box.classList.add("active");

      content.addEventListener("transitionend", function clearHeight(e) {
        if (e.propertyName === "height") {
          content.style.height = "auto";
          content.removeEventListener("transitionend", clearHeight);
        }
      });
    }
  });
});
