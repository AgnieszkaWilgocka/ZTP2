const button = document.querySelector(".my-button");
const form_appear = document.querySelector(".my-appear");

button.addEventListener("click", function() {
    form_appear.classList.toggle("my-active");
})