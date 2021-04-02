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
    await sleep(2000);
    let pages = document.querySelector('div.mat-select-content').querySelectorAll('mat-option');
    let pageNo = stay.pageStart - 1;
    let found = false;
    let foundNode;
    let outcome;

    pages[pageNo].click();
    await sleep(2000);
    let firstRow = document.querySelector('mat-row');
    let firstDate = firstRow.querySelector('mat-cell.mat-column-Check-in').textContent.trim();
    let firstTime = firstRow.querySelector('mat-cell.mat-column-Check-in-time').textContent.trim();
    let dateRef = new Date(stay.timestamp);
    let dateStart = new Date(firstDate + ' ' + firstTime);

    if (dateRef < dateStart) {
        while(! found && pageNo < stay.pageStart + 20) {
            pages[pageNo].click();
            await sleep(2000);
            let list = document.querySelectorAll('mat-row');
            for(i = 0; i < list.length; i++) {
                if (list[i].querySelector('mat-cell.mat-column-hn').textContent == stay.hn) {
                    found = true;
                    foundNode = list[i];
                    outcome = list[i].querySelector('mat-cell.mat-column-dispose').textContent;
                    break;
                }
            }
            pageNo++;
        }
    } else {
        while(! found && pageNo > stay.pageStart - 20) {
            pages[pageNo].click();
            await sleep(2000);
            let list = document.querySelectorAll('mat-row');
            for(i = 0; i < list.length; i++) {
                if (list[i].querySelector('mat-cell.mat-column-hn').textContent == stay.hn) {
                    found = true;
                    foundNode = list[i];
                    outcome = list[i].querySelector('mat-cell.mat-column-dispose').textContent;
                    break;
                }
            }
            pageNo--;
            if (pageNo < 0) {
                break;
            }
        }
    }
    if (! found) {
        return profile;
    }

    foundNode.click();
    await sleep(3000);
    let events = [...document.querySelectorAll('div.event')];
    profile.found = true;
    profile.no = stay.no;
    profile.outcome = outcome;
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