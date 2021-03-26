const itnev = function () {
    const task = function () {
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
    if (window.location.path != '/er-queue') {
        document.querySelector('.navbar > div:nth-child(2) > a:nth-child(1)').click();
        setTimeout(() => task(), 500);
    } else {
        task();
    }
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
const clearSwitch = setInterval(switchPage, 60000);

clearInterval(clearItnev);
clearInterval(clearSwitch);