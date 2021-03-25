const itnev = function () {
    let list = document.querySelector('div.item-list');
    let spanTags = [...list.querySelectorAll('span')].map(node => node.textContent);
    let pTags = [...list.querySelectorAll('p')].map(node => node.textContent);
    fetch('http://172.21.106.10:7070/dudes/venti', {
        method: 'post',
        headers: headers,
        body: JSON.stringify({"p_tags":pTags, "span_tags":spanTags}) })
    .then(response => response.json())
    .then(data => console.log(data))
}