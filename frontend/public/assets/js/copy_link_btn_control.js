const copyLinkBtns = document.querySelectorAll(".copy-btn");

for (const copyBtn of copyLinkBtns) {
    copyBtn.addEventListener("click", () => {
        const linkText = copyBtn.previousElementSibling.innerText;
        console.log(linkText)
        navigator.clipboard.writeText(linkText);
        copyBtn.innerHTML = '<img src="./images/affiliate_links/Icon-copy.svg" alt="copy link"> <span> Copied! </span>'
        setTimeout(() => {
            console.log("time out!")
            copyBtn.innerHTML = '<img src="./images/affiliate_links/Icon-copy.svg" alt="copy link"> <span> Copy </span>'
        }, 3000)

    });

}