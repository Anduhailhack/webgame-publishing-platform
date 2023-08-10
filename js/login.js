const loginForm = document.querySelector("#login-form")

loginForm.addEventListener("submit", function(event) {
    event.preventDefault()

    const warning = document.querySelector("#warning")
    warning.classList.remove("warning")
    warning.innerText = ""

    const email = document.querySelector("#email")
    email.classList.remove("warning-in-txt")

    const pass = document.querySelector("#pass")
    pass.classList.remove("warning-in-txt")

    if(!isValidEmail(email.value)){
        warning.classList.add("warning")
        warning.innerText = "Invalid Email."
        email.focus()
        email.classList.add("warning-in-txt")
        return 
    }
    
    if(!isValidPass(pass.value)){
        warning.classList.add("warning")
        warning.innerText = "Invalid Password."
        pass.focus()
        pass.classList.add("warning-in-txt")
        return 
    }

    loginForm.submit()
})

function isValidEmail(email)
{
    return (/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(email))
}

function isValidPass(pass){
    return (pass.length >= 6)
}