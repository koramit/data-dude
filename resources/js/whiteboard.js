const sleep = function (ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

const counts = [];

const grabWhiteboard = async function () {
    console.log('iteration started.');
    document.querySelector('div.sidenav-item:nth-child(2)').click();
    await sleep(15000);
    if (document.querySelector('div.item-list') === null) {
        console.log('abort, document not ready');
        return [];
    }

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
    await sleep(15000); // array.map() is a sync so, wait for it
    console.log('cases count : ' + patients.length);

    if (counts.length > 5) {
        counts.shift();
    }
    counts.push(patients.length);

    let avg = 0;
    for(let i = 0; i < counts.length; i++) {
        avg += counts[i];
    }
    avg = parseInt(avg / counts.length);

    if ((avg - patients.length) > 5) {
        console.log('Breaker !!!');
        return [];
    }
    return patients;
}

const pushWhiteboard = async function (patients) {
    if (patients.length) {
        return fetch('http://172.21.106.10:7070/dudes/venti', {
                    method: 'post',
                    headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
                    body: JSON.stringify({ "patients": patients })
                })
                .then(response => response.json())
                .then(data => console.log(data));
    }
    return {};
}

const fetchHn = async function () {
    return fetch('http://172.21.106.10:7070/dudes/venti/hn', {
        method: 'post',
        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
    }).then(res => res.json());
}

const grabProfile = async function (stay) {
    let profile = { found: false };
    if (stay.hn === false) {
        return profile;
    }
    if (document.querySelector('div.item-list') === null) {
        console.log('abort, document not ready');
        return profile;
    }
    let items = [...document.querySelector('div.item-list').querySelectorAll('div.item')];
    let nodes = items.filter(item => item.textContent.indexOf(stay.hn) != -1) // hn must available
    if (nodes.length == 0) {
        return profile;
    }
    let node = nodes[0];
    node.click();
    await sleep(15000);

    let events = document.querySelectorAll('div.event');
    if (events === undefined || events.length === 0 ||
        ! document.querySelector('.bio-box > div:nth-child(2) > div:nth-child(2)') ||
        ! document.querySelector('.bio-box > div:nth-child(2) > div:nth-child(3)')
    ) {
        console.log('abort, document not ready');
        return profile;
    }

    for(i = 0; i < events.length; i++) {
        if (events[i].textContent.indexOf('Check-in Time') !== -1) {
            profile.encountered_at = events[i].querySelector('.timestamp').textContent.replaceAll("\n", '').trim();
            break;
        }
    }

    profile.found = true;
    profile.no = stay.no;
    profile.hn = document.querySelector('.bio-box > div:nth-child(2) > div:nth-child(2)').textContent.replaceAll("\n", '').replace('HN : ', '').replace(' Search HN', '').trim();
    profile.en = document.querySelector('.bio-box > div:nth-child(2) > div:nth-child(3)').textContent.replaceAll("\n", '').replace('EN : ', '').trim();
    profile.insurance = document.querySelector('.scheme-box > div:nth-child(1)').textContent.replaceAll("\n", '').trim();
    profile.cc = document.querySelector('.symptom-box > div:nth-child(1)').textContent.replaceAll("\n", ' | ').replace('CC :', '').trim();
    profile.dx = document.querySelector('.symptom-box > div:nth-child(2)').textContent.replaceAll("\n", ' | ').replace('Dx :', '').trim();
    profile.location = document.querySelector('.movement-type-box > div:nth-child(1)').textContent.replaceAll("\n", '').trim();
    profile.triage = [...document.querySelector('app-card-triage-detail').querySelectorAll('p')].map(p => p.textContent.replaceAll("\n", ' | ').trim()).join(' | ').trim('|');
    profile.vital_signs = document.querySelector('.vital-sign').textContent.trim().replaceAll("\n", ' | ')
                                .replace(' Edit', '')
                                .replace('T', 'T: ')
                                .replace('PR', ' | PR: ')
                                .replace('RR', ' | RR: ')
                                .replace('BP', ' | BP: ')
                                .replace('O2', ' | O2: ');
    return profile;
}

const pushProfile = function (profile) {
    if (! profile.found) {
        document.querySelector('div.sidenav-item:nth-child(2)').click();
        return;
    }

    fetch('http://172.21.106.10:7070/dudes/venti/profile', {
        method: 'post',
        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
        body: JSON.stringify({ "profile": profile })
    }).then(res => res.json())
    .then(data => {
        console.log(data);
    });
}

const clearWhiteboard = setInterval(() => grabWhiteboard().then(pushWhiteboard).then(fetchHn).then(grabProfile).then(pushProfile).catch(error => console.log(error)), 60000);