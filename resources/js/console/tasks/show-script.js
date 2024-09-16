document.addEventListener("DOMContentLoaded", function () {
    const elements = document.querySelectorAll(".task-details li");
    elements.forEach((el, index) => {
        el.style.opacity = 0;
        el.style.transition = `opacity 0.5s ease ${index * 0.1}s`;
        setTimeout(() => {
            el.style.opacity = 1;
        }, 100);
    });
});
