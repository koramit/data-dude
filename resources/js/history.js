const sleep = function (ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

const fetchHnHistory = async function () {
    return fetch('http://172.21.106.10:7070/dudes/venti/hn/history', {
        method: 'post',
        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
    }).then(res => res.json());
}

const searchHistory = async function(stay) {
    let profile = { found: false };

    if (stay.hn === false) {
        return profile;
    }

    [...document.querySelectorAll('div.mat-select-trigger')].pop().click();
    await sleep(5000);
    let pages = document.querySelector('div.mat-select-content').querySelectorAll('mat-option');
    let pageNo = stay.pageStart - 1;
    let found = false;
    let foundNode;
    let outcome;
    let dismissedAt;

    pages[pageNo].click();
    await sleep(6000);
    let maxPage = 120;
    let minPage = 0;
    let iterations = 1;
    let firstRow;
    let firstDate;
    let firstTime;
    let dateRef;
    let dateStart;

    while (true) {
        let list = document.querySelectorAll('mat-row');
        for(i = 0; i < list.length; i++) {
            if (list[i].querySelector('mat-cell.mat-column-hn').textContent == stay.hn) {
                found = true;
                foundNode = list[i];
                outcome = list[i].querySelector('mat-cell.mat-column-dispose').textContent;
                break;
            }
        }
        if (found || iterations > 20) {
            break;
        }

        console.log('iterations: ' + iterations + ', page# ' + pageNo);

        iterations++;
        firstRow = document.querySelector('mat-row');
        firstDate = firstRow.querySelector('mat-cell.mat-column-Check-in').textContent.trim();
        firstTime = firstRow.querySelector('mat-cell.mat-column-Check-in-time').textContent.trim();
        dateRef = new Date(stay.timestamp);
        dateStart = new Date(firstDate + ' ' + firstTime);
        if (dateRef < dateStart) { // next
            pageNo += parseInt((maxPage - pageNo) / 2);
            maxPage = pageNo + 1;
        } else { // previous
            pageNo -= parseInt((pageNo - minPage) / 2);
            minPage = pageNo - 1;
        }
        pages[pageNo].click();
        await sleep(6000);
    }

    if (! found) {
        return profile;
    }

    foundNode.click();
    await sleep(10000);
    let events = [...document.querySelectorAll('div.event')];
    profile.found = true;
    profile.no = stay.no;
    profile.outcome = outcome;
    profile.dismissed_at = dismissedAt;
    profile.hn = document.querySelector('.bio-box > div:nth-child(2) > div:nth-child(2)').textContent.replaceAll("\n", ' | ').replace('HN : ', '').replace(' Search HN', '').trim();
    profile.en = document.querySelector('.bio-box > div:nth-child(2) > div:nth-child(3)').textContent.replaceAll("\n", ' | ').replace('EN : ', '').trim();
    profile.encountered_at = events.pop().querySelector('div.timestamp').textContent.replaceAll("\n", ' | ').trim();
    profile.insurance = document.querySelector('.scheme-box > div:nth-child(1)').textContent.replaceAll("\n", ' | ').trim();
    profile.cc = document.querySelector('.symptom-box > div:nth-child(1)').textContent.replaceAll("\n", ' | ').replace('CC :', '').trim();
    profile.dx = document.querySelector('.symptom-box > div:nth-child(2)').textContent.replaceAll("\n", ' | ').replace('Dx :', '').trim();
    profile.location = document.querySelector('.movement-type-box > div:nth-child(1)').textContent.replaceAll("\n", ' | ').trim();
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
        document.querySelector('div.sidenav-item:nth-child(9)').click();
        console.log('no case to update');
        return 0;
    }

    fetch('http://172.21.106.10:7070/dudes/venti/profile', {
        method: 'post',
        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
        body: JSON.stringify({ "profile": profile })
    }).then(res => res.json())
    .then(data => {
        console.log(data);
        document.querySelector('div.sidenav-item:nth-child(9)').click();
        return 1;
    });
}

// const clearHistory = setInterval(() => fetchHnHistory().then(searchHistory).then(pushProfile).catch(() => document.querySelector('div.sidenav-item:nth-child(9)').click()), 180000);
fetchHnHistory().then(searchHistory).then(pushProfile).catch(() => document.querySelector('div.sidenav-item:nth-child(9)').click());