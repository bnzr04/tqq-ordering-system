const dateDisplay = document.getElementById("dateDisplay");
const timeDisplay = document.getElementById("timeDisplay");

function updateDateTime() {
    const now = new Date();
    const day = now.toLocaleDateString("en-US", {
        month: "2-digit",
        day: "2-digit",
        year: "2-digit",
    });
    const time = now.toLocaleTimeString("en-US", {
        hour: "2-digit",
        minute: "2-digit",
        hour12: true,
    });

    const date = `${day}`;
    const currentTime = `${time}`;

    dateDisplay.innerHTML = date;
    timeDisplay.innerHTML = currentTime;
}

updateDateTime();
setInterval(updateDateTime, 1000);
