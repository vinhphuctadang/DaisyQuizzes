timeLeft = 15
intervalHandler = null

function render() {
    document.getElementById('time').innerText = timeLeft
}

function setEllapsedTime(time) {
    if (intervalHandler != null) clearInterval(intervalHandler)
    intervalHandler = setInterval('onTimingInterval ()', 1000)
    timeLeft = time
    render()
}

function onTimingInterval() {
    if (timeLeft > 0) {
        render()
        timeLeft -= 1
    } else {
        timeLeft = 0
        render()
    }
}

// setInterval ("onTimingInterval ()", 1000);
