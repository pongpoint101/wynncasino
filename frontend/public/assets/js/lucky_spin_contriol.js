let wheel = document.querySelector(".wheel")
let spin_btn = document.querySelector(".spin-btn")
let number = Math.ceil(Math.random() * 10000);

spin_btn.addEventListener("click", () => {
    wheel.style.transform = "rotate(" + number + "deg)";
    let deg = number % 360
    spin_btn.style.cursor = "not-allowed";
    spin_btn.style.color = "grey";
    spin_btn.disabled = true;
    setTimeout(() => {
        console.log(deg);
        if (deg >= 0 && deg <= 29) {
            console.log("คุณได้รับ ทองคำ 1 บาท");
        } else if (deg >= 30 && deg <= 59) {
            console.log("คุณได้รับ 10 บาท");
        } else if (deg >= 60 && deg <= 89) {
            console.log("คุณได้รับ 150 บาท");
        } else if (deg >= 90 && deg <= 119) {
            console.log("คุณได้รับ 5 บาท");
        } else if (deg >= 120 && deg <= 149) {
            console.log("คุณได้รับ 50 บาท");
        } else if (deg >= 150 && deg <= 179) {
            console.log("คุณได้รับ 200 บาท");
        } else if (deg >= 180 && deg <= 209) {
            console.log("คุณได้รับ 5 บาท");
        } else if (deg >= 210 && deg <= 239) {
            console.log("คุณได้รับ 10 บาท");
        } else if (deg >= 240 && deg <= 269) {
            console.log("คุณได้รับ 500 บาท");
        } else if (deg >= 270 && deg <= 299) {
            console.log("คุณได้รับ 100 บาท");
        } else if (deg >= 300 && deg <= 329) {
            console.log("คุณได้รับ 1000 บาท");
        } else if (deg >= 330 && deg <= 359) {
            console.log("คุณได้รับ 5 บาท");
        }

    }, 5000)
});