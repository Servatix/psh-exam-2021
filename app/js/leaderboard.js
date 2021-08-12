function getStatistics() {
    fetch('/statistics/').then( (data) => data.json() )
    .then( (data) => {
        fillBoard(data)
    } )
}

function fillBoard(data) {
    const { last_update, leaderboard } = data
    const date_holder = document.getElementById('date')
    const icon = document.querySelector('.refresh-icon')
    const board = document.querySelector('.table')
    const rows = Array.from(board.children)
    const date = new Date(last_update)

    removeAllRows(rows).then( () => {
        leaderboard.forEach((player, rank) => {
            addRow(player, rank, board)
        })
    })

    date_holder.textContent = date.getHours() + ':' + date.getMinutes() + ' hs.'
    icon.animate([{ transform: "rotate(-1turn)" }], 1000)
}

function addRow(player, rank, board) {
    let temp = document.getElementById('template-row').cloneNode(true)
    temp.removeAttribute('id')
    temp.style.setProperty('--rank', rank+1)

    temp.querySelector('img').src = player.thumbnail
    temp.querySelector('img').alt = player.nickname
    temp.querySelector('.player').textContent = player.nickname
    temp.querySelector('.score').innerHTML = '&#127775;' + player.score

    board.appendChild(temp)
}

function removeRow(row, next = null) {
    row.animate([{
        transform: "translateY( calc( 100% * calc( -12 - var(--rank) ) ) )"
    }], 1000).onfinish = (a) => {
        row.remove()
        if (typeof next == 'function') {
            next()
        }
    }
}

async function removeAllRows(rows) {
    Array.from(rows).forEach( removeRow )

    return new Promise(r => setTimeout(r, 1100))
}

getStatistics(), setInterval(getStatistics, 10000)