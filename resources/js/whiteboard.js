const fetchHn = async function () {
    fetch('http://172.21.106.10/dudes/venti/hn', {
        method: 'post',
        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
    }).then(res => res.json())
    .then(data => data.hn);
}

const sleep = function (ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

const getProfile = async function (hn) {
    let profile = { found: false };
    let items = [...document.querySelector('div.item-list').querySelectorAll('div.item')];
    let nodes = items.filter(item => item.textContent.indexOf(hn) != -1) // hn must available
    if (nodes.length == 0) {
        document.querySelector('div.sidenav-item:nth-child(2)').click();
        console.log(profile);
        return profile;
    }
    let node = nodes[0];
    node.click();
    await sleep(3000);
    let events = [...document.querySelectorAll('div.event')];
    profile.found = true;
    profile.hn = document.querySelector('.bio-box > div:nth-child(2) > div:nth-child(2)').textContent.replaceAll("\n", ' | ').replace('HN : ', '').replace(' Search HN', '').trim();
    profile.en = document.querySelector('.bio-box > div:nth-child(2) > div:nth-child(3)').textContent.replaceAll("\n", ' | ').replace('HN : ', '').replace(' Search HN', '').trim();
    profile.encountered_at = events.pop().querySelector('div.timestamp').textContent.replaceAll("\n", ' | ').trim();
    profile.insurance = document.querySelector('.scheme-box > div:nth-child(1)').textContent.replaceAll("\n", ' | '.trim())
    profile.cc = document.querySelector('.symptom-box > div:nth-child(1)').textContent.replaceAll("\n", ' | ').replace('CC : ', '').trim();
    profile.dx = document.querySelector('.symptom-box > div:nth-child(2)').textContent.replaceAll("\n", ' | ').replace('Dx : ', '').trim();
    profile.location = document.querySelector('.movement-type-box > div:nth-child(1)').textContent.replaceAll("\n", ' | ').trim();
    profile.triage = [...document.querySelector('app-card-triage-detail').querySelectorAll('p')].map(p => p.textContent.replaceAll("\n", ' | ').trim()).join(' | ');
    profile.vitalsigns = document.querySelector('.vital-sign').textContent
                                .replace(' Edit ', '')
                                .replace('T', ' T: ')
                                .replace('PR', ' PR: ')
                                .replace('RR', ' RR: ')
                                .replace('BP', ' BP: ')
                                .replace('O2', ' O2: ')
                                .trim();
    console.log(profile);
    return profile;
}