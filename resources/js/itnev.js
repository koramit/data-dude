const newnev = function () {
    let list = document.querySelector('div.item-list');
    let items = [...list.querySelectorAll('div.item')];
    let patients = items.map(node => {
        let patient = {};

        // [med] div.badge.med > p => "M" | not for all
        let medBadge = node.querySelector('div.badge.med > p');
        if (medBadge && medBadge !== undefined) {
            patient.medicine = medBadge.textContent == 'M';
        } else {
            patient.medicine = false;
        }

        // [bed] span.position-number | not for all
        let bed = node.querySelector('span.position-number');
        patient.bed =  (bed && bed !== undefined) ? bed.textContent.trim() : null;

        // [name] span.name | **ALL**
        let pname = node.querySelector('span.name');
        patient.name =  (pname && pname !== undefined) ? pname.textContent.trim() : null;

        // [HN] span.en | **ALL**
        let hn = node.querySelector('span.en');
        patient.hn =  (hn && hn !== undefined) ? hn.textContent.trim().replace('HN', '') : null;

        // [dx] p.value | **ALL**
        let dx = node.querySelector('p.value');
        patient.dx =  (dx && dx !== undefined) ? dx.textContent.trim() : null;

        // [couter] div.zone > p  | **ALL**
        let counter = node.querySelector('div.zone > p');
        patient.counter =  (counter && counter !== undefined) ? counter.textContent.trim() : null;

        // [los] p.time  | **ALL**
        let los = node.querySelector('p.time');
        patient.los =  (los && los !== undefined) ? los.textContent.trim() : null;

        // [remark] div.round-rect > p   | **ALL**
        let remark = node.querySelector('div.round-rect > p');
        patient.remark =  (remark && remark !== undefined) ? remark.textContent.trim() : null;

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
    .then(data => console.log(data));

    console.log(patients);
}

const itnev = function () {
    let list = document.querySelector('div.item-list');
    let spanTags = [...list.querySelectorAll('span')].map(node => node.textContent);
    let pTags = [...list.querySelectorAll('p')].map(node => node.textContent);
    fetch('http://172.21.106.10:7070/dudes/venti', {
        method: 'post',
        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
        body: JSON.stringify({"p_tags":pTags, "span_tags":spanTags}) })
    .then(response => response.json())
    .then(data => console.log(data));
}

const switchPage = function () {
    let wait = (Math.floor(Math.random() * Math.floor(10)) + 5) * 1000; // [5 - 14]
    console.log('wait ' + (wait/1000) + ' seconds before switch');
    let page = Math.floor(Math.random() * Math.floor(4)) + 3; // [3 - 6]
    setTimeout(() => {
        document.querySelector('.navbar > div:nth-child(' + page + ') > a:nth-child(1)').click(); // menu Q doctor
        let stay = (Math.floor(Math.random() * Math.floor(10)) + 5) * 1000; // [5 - 14]
        console.log('stay for ' + (stay/1000) + ' seconds');
        setTimeout(
            () => document.querySelector('.navbar > div:nth-child(2) > a:nth-child(1)').click() // menu Whiteboard
        , stay);
    }, wait);
}

const clearItnev = setInterval(itnev, 60000);
const clearSwitch = setInterval(switchPage, 600000);

clearInterval(clearItnev);
clearInterval(clearSwitch);