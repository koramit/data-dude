const sleep = function (ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}


const timeEquivalent = function (a, b, exactly) {
    console.log([a,b].join(' => '));
    if (a === b) {
        return true;
    }

    if (exactly) {
        return false;
    }

    let timeA = a.split(':');
    let timeB = b.split(':');

    if (parseInt(timeA[0]) !== parseInt(timeB[0])) {
        return false;
    }

    let minuteA = parseInt(timeA[1]);
    let minuteB = parseInt(timeB[1]);

    if (minuteA === minuteB || Math.abs(minuteA - minuteB) <= 5) {
        return true;
    }

    return false;
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

    // pseudo refresh
    document.querySelector('div.sidenav-item:nth-child(2)').click();
    await sleep(10000);
    document.querySelector('div.sidenav-item:nth-child(9)').click();
    await sleep(10000);

    [...document.querySelectorAll('div.mat-select-trigger')].pop().click();
    await sleep(10000);
    let pages = document.querySelector('div.mat-select-content').querySelectorAll('mat-option');
    let pageNo = stay.pageStart - 1;
    let found = false;
    let pageVisited = [];
    let maxPage = stay.maxPage;
    let minPage = 0;
    let dateRef = new Date(stay.timestamp);
    let iterations = 1;

    let foundNode;
    let outcome;
    let firstRow;
    let firstDate;
    let firstTime;
    let dateStart;

    console.log('Search for HN ' + stay.hn + ' @ ' + dateRef);
    pages[pageNo].click();
    pageVisited.push(pageNo);
    console.log('iterations: ' + iterations + ', page# ' + (pageNo+1));
    await sleep(10000);

    while (true) {
        let list = document.querySelectorAll('mat-row');
        for(i = 0; i < list.length; i++) {
            if (list[i].querySelector('mat-cell.mat-column-hn').textContent.trim() == stay.hn &&
                timeEquivalent(list[i].querySelector('mat-cell.mat-column-Check-in-time').textContent.trim(), stay.timer, stay.medicine)
            ) {
                found = true;
                foundNode = list[i];
                outcome = list[i].querySelector('mat-cell.mat-column-dispose').textContent.trim();
                break;
            }
        }
        if (found || iterations > 12) {
            break;
        }
        iterations++;
        firstRow = document.querySelector('mat-row');
        firstDate = firstRow.querySelector('mat-cell.mat-column-Check-in').textContent.trim();
        firstTime = firstRow.querySelector('mat-cell.mat-column-Check-in-time').textContent.trim();
        dateStart = new Date(firstDate + ' ' + firstTime);
        if (dateRef < dateStart) { // next
            minPage = pageNo + 1;
            pageNo += (parseInt((maxPage - pageNo) / 2) !== 0 ? parseInt((maxPage - pageNo) / 2) : 1);
        } else { // previous
            maxPage = pageNo - 1;
            pageNo -= (parseInt((pageNo - minPage) / 2) !== 0 ? parseInt((pageNo - minPage) / 2) : 1);
        }
        if (pageVisited.indexOf(pageNo) !== -1) {
            break;
        }
        pages[pageNo].click();
        pageVisited.push(pageNo);
        console.log('iterations: ' + iterations + ', page# ' + (pageNo+1));
        await sleep(10000);
    }

    if (! found) {
        return profile;
    }

    foundNode.click();
    await sleep(10000);

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
        } else if (events[i].textContent.indexOf('Check-out Time') !== -1) {
            profile.dismissed_at = events[i].querySelector('.timestamp').textContent.replaceAll("\n", '').trim();
        }

        if (profile.encountered_at !== undefined && profile.dismissed_at !== undefined) {
            break;
        }
    }

    profile.found = true;
    profile.no = stay.no;
    profile.outcome = outcome;
    profile.hn = document.querySelector('.bio-box > div:nth-child(2) > div:nth-child(2)').textContent.replaceAll("\n", ' | ').replace('HN : ', '').replace(' Search HN', '').trim();
    profile.en = document.querySelector('.bio-box > div:nth-child(2) > div:nth-child(3)').textContent.replaceAll("\n", ' | ').replace('EN : ', '').trim();
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

const pushProfile = async function (profile) {
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
        return 1;
    });
}

const clearHistory = setInterval(() => fetchHnHistory().then(searchHistory).then(pushProfile).catch((error) => console.log(error)), 210000);