document.querySelector("button[name='action'][value='check']").addEventListener("click", function (e) {
    document.querySelector("input[name='user_pw']").removeAttribute("required","");
    document.querySelector("input[name='name']").removeAttribute("required","");
    document.querySelector("input[name='address']").removeAttribute("required","");
});

document.querySelector("button[name='action'][value='register']").addEventListener("click", function (e) {
    document.querySelector("input[name='user_pw']").setAttribute("required","");
    document.querySelector("input[name='name']").setAttribute("required","");
    document.querySelector("input[name='address']").setAttribute("required","");
});

