const fetchHn = async function () {
    return fetch('http://172.21.106.10:7070/dudes/venti/hn', {
        method: 'post',
        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
    }).then(res => res.json());
}

const sleep = function (ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

const grabProfile = async function (stay) {
    let profile = { found: false };
    if (stay.hn === false) {
        return profile;
    }
    let items = [...document.querySelector('div.item-list').querySelectorAll('div.item')];
    let nodes = items.filter(item => item.textContent.indexOf(stay.hn) != -1) // hn must available
    if (nodes.length == 0) {
        return profile;
    }
    let node = nodes[0];
    node.click();
    await sleep(3000);
    let events = [...document.querySelectorAll('div.event')];
    profile.found = true;
    profile.no = stay.no;
    profile.hn = document.querySelector('.bio-box > div:nth-child(2) > div:nth-child(2)').textContent.replaceAll("\n", ' | ').replace('HN : ', '').replace(' Search HN', '').trim();
    profile.en = document.querySelector('.bio-box > div:nth-child(2) > div:nth-child(3)').textContent.replaceAll("\n", ' | ').replace('EN : ', '').trim();
    profile.encountered_at = events.pop().querySelector('div.timestamp').textContent.replaceAll("\n", ' | ').trim();
    profile.insurance = document.querySelector('.scheme-box > div:nth-child(1)').textContent.replaceAll("\n", ' | '.trim())
    profile.cc = document.querySelector('.symptom-box > div:nth-child(1)').textContent.replaceAll("\n", ' | ').replace('CC : ', '').trim();
    profile.dx = document.querySelector('.symptom-box > div:nth-child(2)').textContent.replaceAll("\n", ' | ').replace('Dx : ', '').trim();
    profile.location = document.querySelector('.movement-type-box > div:nth-child(1)').textContent.replaceAll("\n", ' | ').trim();
    profile.triage = [...document.querySelector('app-card-triage-detail').querySelectorAll('p')].map(p => p.textContent.replaceAll("\n", ' | ').trim()).join(' | ').trim('|');
    profile.vital_signs = document.querySelector('.vital-sign').textContent.trim()
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
        document.querySelector('div.sidenav-item:nth-child(2)').click();
    });
}

setInterval(() => fetchHn().then(grabProfile).then(pushProfile), 60000)