const sleep = function (ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

const grabWhiteboard = async function () {
    let items = [...document.querySelector('div.item-list').querySelectorAll('div.item')];
    let patients = items.map(node => {
        let patient = {};

        let medBadge = node.querySelector('div.badge.med > p');
        if (medBadge && medBadge !== undefined) {
            patient.medicine = medBadge.textContent == 'M';
        } else {
            patient.medicine = false;
        }
        let fields = [
            { name: 'name'  , selector: 'span.name' },
            { name: 'hn'  , selector: 'span.en' },
            { name: 'counter'  , selector: 'div.zone > p' },
            { name: 'los'  , selector: 'p.time' },
            { name: 'remark'  , selector: 'div.round-rect > p' }
        ];

        for(i = 0; i < fields.length; i++) {
            let dom = node.querySelector(fields[i].selector);
            patient[fields[i].name] = (dom && dom !== undefined) ? dom.textContent.replaceAll("\n", '').trim() : null;
        }

        patient.hn = patient.hn.replace('HN', '');

        if (! patient.medicine && patient.counter == 'C4') {
            patient.medicine = true;
        }
        return patient;
    });

    console.log('cases count : ' + patients.length);

    fetch('http://172.21.106.10:7070/dudes/venti', {
        method: 'post',
        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
        body: JSON.stringify({ "patients": patients })
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
    });

    document.querySelector('div.sidenav-item:nth-child(9)').click();
    await sleep(20000);
    document.querySelector('div.sidenav-item:nth-child(2)').click();
}

const clearWhiteboard = setInterval(grabWhiteboard, 60000);