const itnev = function () {
    let items = [...document.querySelector('div.item-list').querySelectorAll('div.item')];
    let patients = items.map(node => {
        let patient = {};

        let medBadge = node.querySelector('div.badge.med > p');
        if (medBadge && medBadge !== undefined) {
            patient.medicine = medBadge.textContent == 'M';
        } else {
            patient.medicine = false;
        }
        [
            { name: 'name'  , selector: 'span.name' },
            { name: 'hn'  , selector: 'span.en' },
            { name: 'counter'  , selector: 'div.zone > p' },
            { name: 'los'  , selector: 'p.time' },
            { name: 'remark'  , selector: 'div.round-rect > p' }
        ].forEach(field => {
            let dom = node.querySelector(field.selector);
            patient[field.name] = (dom && dom !== undefined) ? dom.textContent.replaceAll("\n", '').trim() : null;
        })

        patient.hn = patient.hn.replace('HN', '');

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
    });
}