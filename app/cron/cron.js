var cron

function runCron() {
    fetch('/game/host/')
    .then( (data) => data.text() )
    .then( (data) => {
        let delay = 0
        data.split("\n").forEach(
            (line) => {
                delay += Math.random()*500+100
                setTimeout(() => stdo(line+"\n"), delay)
            }
        )
    } )
}

function startCron(secs = 300) {
    runCron()
    cron = setInterval(runCron, 1000*secs)
}

function stopCron() {
    clearInterval(cron)
}

function cmdInput(e = null) {
    const temp = document.getElementById('temp')
    const cmd = document.getElementById('cmd')
    const cursor = document.querySelector('.cursor')
    const offset = 20

    if (e?.keyCode == 13) {
        stdo(cmd.value + '\n')

        let func = cmd.value.trim().match(/^cron\s+(run|start|stop)|clear$/)
        func = func?.[1] || func?.[0]
        if (func) {
            if (func == 'clear') {
                stdo('', true)
            } else {
                if (func == 'run') {
                    runCron()
                } else if (func == 'start') {
                    startCron()
                } else if (func == 'stop') {
                    stopCron()
                }

                stdo('> ' + func + '\n')
            }
        }

        cmd.value = ''
    }

    temp.textContent = cmd.value
    cursor.style.left = temp.offsetWidth + offset + "px"
}

function stdo(input, reset = false) {
    const shell = document.querySelector('pre')
    const scroller = (document.scrollingElement || document.body)

    if (reset) {
        shell.textContent = ''
    }

    shell.append(input)
    scroller.scrollTop = scroller.scrollHeight;
}

document.getElementById('cmd').addEventListener("keyup", cmdInput)
document.addEventListener("keypress", () => document.getElementById('cmd').focus())
document.querySelector('html').addEventListener("click", (e) => {
    if(e.target.nodeName == 'HTML') document.getElementById('cmd').focus()
})

cmdInput()
