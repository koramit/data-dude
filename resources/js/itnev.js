const switchPage = function () {
    let wait = (Math.floor(Math.random() * Math.floor(8)) + 5) * 1000; // [5 - 12]
    console.log('wait ' + (wait/1000) + ' seconds before switch');
    let page = Math.floor(Math.random() * Math.floor(4)) + 3; // [3 - 6]
    setTimeout(() => {
        document.querySelector('.navbar > div:nth-child(' + page + ') > a:nth-child(1)').click(); // menu Q doctor
        let stay = (Math.floor(Math.random() * Math.floor(8)) + 5) * 1000; // [5 - 12]
        console.log('stay for ' + (stay/1000) + ' seconds');
        setTimeout(
            () => document.querySelector('.navbar > div:nth-child(2) > a:nth-child(1)').click() // menu Whiteboard
        , stay);
    }, wait);
}

const itnev = function () {
    if (window.location.pathname != '/er-queue') {
        console.log('not the page');
        return;
    }
    let list = document.querySelector('div.item-list');
    let items = [...list.querySelectorAll('div.item')];
    let patients = items.map(node => {
        let patient = {};

        let medBadge = node.querySelector('div.badge.med > p');
        if (medBadge && medBadge !== undefined) {
            patient.medicine = medBadge.textContent == 'M';
        } else {
            patient.medicine = false;
        }
        [
            { name: 'bed'  , selector: 'span.position-number' },
            { name: 'name'  , selector: 'span.name' },
            { name: 'hn'  , selector: 'span.en' },
            { name: 'dx'  , selector: 'p.value' },
            { name: 'counter'  , selector: 'div.zone > p' },
            { name: 'los'  , selector: 'p.time' },
            { name: 'remark'  , selector: 'div.round-rect > p' }
        ].forEach(field => {
            let dom = node.querySelector(field.selector);
            patient[field.name] = (dom && dom !== undefined) ? dom.textContent.replaceAll("\n", '').trim() : null;
        })

        if (! patient.medicine && patient.counter == 'C4') {
            patient.medicine = true;
        }
        return patient;
    });

    fetch('http://172.21.106.10:7070/dudes/venti', {
        method: 'post',
        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
        body: JSON.stringify({ "patients": patients })
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        document.querySelector('div.sidenav-item:nth-child(9)').click();
        setTimeout(() => {
            let patients = [...document.querySelectorAll('mat-row')].map(row => {
                let patient = {
                    'hn': row.querySelector('mat-cell.mat-column-hn').textContent.trim(),
                    'movement': row.querySelector('mat-cell.mat-column-movementType').textContent.trim(),
                    'cc': row.querySelector('mat-cell.mat-column-cc').textContent.replaceAll("\n", '').trim(),
                    'dx': row.querySelector('mat-cell.mat-column-diag').textContent.replaceAll("\n", '').trim(),
                    'insurance': row.querySelector('mat-cell.mat-column-scheme').textContent.trim(),
                    'encountered_at': row.querySelector('mat-cell.mat-column-Check-in').textContent + ' ' + row.querySelector('mat-cell.mat-column-Check-in-time').textContent,
                    'dismissed_at': row.querySelector('mat-cell.mat-column-Check-out').textContent + ' ' + row.querySelector('mat-cell.mat-column-Check-out-time').textContent,
                    'outcome': row.querySelector('mat-cell.mat-column-dispose').textContent.trim(),
                };
                return patient;
            });
            fetch('http://172.21.106.10:7070/dudes/venti/history', {
                method: 'post',
                headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
                body: JSON.stringify({ "patients": patients })
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                document.querySelector('div.sidenav-item:nth-child(2)').click();
            });
        }, 8000);
    });
}

const clearItnev = setInterval(itnev, 60000);
const clearSwitch = setInterval(switchPage, 600000);