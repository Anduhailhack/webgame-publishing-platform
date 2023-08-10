const mainContainer = document.querySelector(".main-container")

const uploadsTab = document.querySelector("#uploads-tab")
const gamesTab = document.querySelector("#games-tab")
const settingsTab = document.querySelector("#settings-tab")

const uploadsForm = document.querySelector("#upload-form")

uploadsTab.addEventListener("click", function () {
    document.querySelector(".uploads-menu").style = "display: block;";
    document.querySelector(".games-menu").style = "display: none;";
    document.querySelector(".settings-menu").style = "display: none;";
})
gamesTab.addEventListener("click", function () {
    document.querySelector(".games-menu").style = "display: block;"; 
    document.querySelector(".uploads-menu").style = "display: none;";
    document.querySelector(".settings-menu").style = "display: none;";
})
settingsTab.addEventListener("click", function () {
    document.querySelector(".uploads-menu").style = "display: none;";
    document.querySelector(".games-menu").style = "display: none;";
    document.querySelector(".settings-menu").style = "display: block;";
})



document.querySelector(".game-banner").addEventListener("click", function () {
    document.querySelector("#game-banner-btn").click()
    document.querySelector("#game-banner-btn").addEventListener("change", function (event) {

        document.querySelector(".game-banner").classList.remove("warning-pic-container")
        document.querySelector("#game-banner-svg").classList.remove("warning-pic-uploader")

        let file = event.currentTarget.files[0];
        if (file) {
          let reader = new FileReader();
          reader.onload = (event) => {
            document.querySelector(".game-banner-preview").src = event.target.result
          };

          reader.readAsDataURL(file);
        }
    })
})

document.querySelector("#game-file").addEventListener("change", function (event) {
    document.querySelector("#game-file").classList.remove("warning-file")
})

uploadsForm.addEventListener("submit", function (event) {
    event.preventDefault()

    const gameTitle = document.querySelector("#game-title")
    gameTitle.classList.remove("warning-in-txt")

    const bannerContainer = document.querySelector(".game-banner")
    const bannerFile = document.querySelector("#game-banner-btn")
    const uploadBannerSvg = document.querySelector("#game-banner-svg")
    uploadBannerSvg.classList.remove("warning-pic-uploader")
    bannerContainer.classList.remove("warning-pic-container")

    const gameAuthor = document.querySelector("#game-author")
    gameAuthor.classList.remove("warning-in-txt")

    const gameFile = document.querySelector("#game-file")
    gameFile.classList.remove("warning-file")

    if (!isValidInputStr(gameTitle.value))
    {
        gameTitle.classList.add('warning-in-txt')
        gameTitle.focus()
        return
    }

    if (!isValidGameBanner(bannerFile.value))
    {
        bannerContainer.classList.add("warning-pic-container")
        uploadBannerSvg.classList.add("warning-pic-uploader")
        return
    }

    if (!isValidInputStr(gameAuthor.value))
    {
        gameAuthor.classList.add("warning-in-txt")
        gameAuthor.focus()
        return
    }

    if (!isValidGameFile(gameFile.value))
    {
        gameFile.classList.add("warning-file")
        return
    }

    const formData = new FormData()
    formData.append("game-title", gameTitle.value)
    formData.append("game-banner", bannerFile.files[0])
    formData.append("game-author", gameAuthor.value)
    formData.append("game-file", gameFile.files[0])

    const progressContainer = document.createElement("div")
    progressContainer.setAttribute("class", "popup-container")

    const progressCaptionContainer = document.createElement("div")
    progressCaptionContainer.setAttribute("class", "form-control-container")

    const progressCaption = document.createElement("p")
    progressCaption.setAttribute("class", "caption")
    progressCaption.innerText = "Uploading .... 0%"

    const progressCaptionContainer2 = document.createElement("div")
    progressCaptionContainer2.setAttribute("class", "form-control-container")

    const progressBar = document.createElement("progress")
    progressBar.setAttribute("class", "progress-bar")
    progressBar.setAttribute("value", "0")
    progressBar.setAttribute("min", "0")
    progressBar.setAttribute("max", "100")

    const progressCaptionContainer3 = document.createElement("div")
    progressCaptionContainer3.setAttribute("class", "form-control-container")
    
    const doneBtn = document.createElement("button")
    doneBtn.innerText = "Done"
    doneBtn.classList.add("btn")

    progressCaptionContainer.appendChild(progressCaption)
    progressCaptionContainer2.appendChild(progressBar)
    progressCaptionContainer3.appendChild(doneBtn)

    progressContainer.append(
        progressCaptionContainer, 
        progressCaptionContainer2
    )

    doneBtn.addEventListener("click", function (event) {
        gameTitle.value = ""
        document.querySelector(".game-banner-preview").removeAttribute("src")
        gameAuthor.value = ""
        gameFile.value = ""
        progressContainer.remove()
    })

    document.body.append(progressContainer)
    
    axios.post("./routes/upload-games.php", formData, {
        onUploadProgress : function (progressEvent) {
            let calc = Math.round((progressEvent.loaded / progressEvent.total * 100))
            progressBar.value = calc
            progressCaption.innerText = `Uploading .... ${calc}%`

            if (calc >= 100)
            {
                progressCaption.innerText = "Uploaded successfully!"
            }
        }
    })
    .then(function (res) {
        if (res.data && res.data.status) {
            progressCaption.innerText = "Uploaded successfully!"
            progressContainer.appendChild(progressCaptionContainer3)
        }else {
            progressCaption.innerText = res.data.error
            progressContainer.appendChild(progressCaptionContainer3)
        }
    })
    .catch(function (err) {
        progressCaption.classList.add("warning")
        progressCaption.innerText = "Upload failed!"
        progressContainer.appendChild(progressCaptionContainer3)
        console.log(err)
    })
})

function isValidInputStr (gameTitle)
{
    if (gameTitle && gameTitle != '')
        return true
    else
        return false
}

function isValidGameBanner (gameBanner)
{
    let allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif|\.webp|\.JPG|\.JPEG|\.PNG|\.GIF|\.WEBP)$/i;
             
    if (!allowedExtensions.exec(gameBanner)) {
        return false;
    }
    
    return true
}

function isValidGameFile (gameFile)
{
    let allowedExtensions = /(\.zip)$/i;
             
    if (!allowedExtensions.exec(gameFile)) {
        return false;
    }
    
    return true
}

function removeAfterHow(urlStr, searchTerm) {
    urlStr = urlStr.trim();
    const indexOfHow = urlStr.lastIndexOf(searchTerm);
    return indexOfHow === -1 ? urlStr : urlStr.slice(0, indexOfHow);
}

function yesOrNoPrompt() {
    const result = window.confirm("Do you want to continue?")
    return result
}
  