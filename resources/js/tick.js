function tick () {
    const tick = function () {
        console.log('hello tick ' + Date());
    }
    setInterval(tick, 500);
}