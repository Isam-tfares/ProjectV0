// const DeleteLink = document.querySelectorAll(".delete-module");
// DeleteLink.forEach(box => {
//     box.addEventListener('click', function handleClick(event) {
//         confirm("Want to delete?");
//     });
// });
/* header show navbarre */
let myspan = document.querySelector('.show');
let nav = document.querySelector('.menu');
myspan.addEventListener('click', () => {
    nav.classList.toggle('hide');
})

/* show/hide password */
let passwordInput = document.querySelector(".password #password");
let show = document.querySelector(".password .show")
show.addEventListener('click', () => {
    if (show.innerHTML == "show") {
        passwordInput.setAttribute("type", "text");
        show.innerHTML = "Hide";
    } else {
        passwordInput.setAttribute("type", "password");
        show.innerHTML = "show";
    }
})


