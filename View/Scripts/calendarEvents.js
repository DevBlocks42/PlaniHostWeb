function nextPeriod()
{
    var currentPeriod = parseInt(window.location.href.split('/')[7]);
    currentPeriod++;
    document.location.href = "./" + currentPeriod;
}
function previousPeriod()
{
    var currentPeriod = parseInt(window.location.href.split('/')[7]);
    if(currentPeriod > 1)
    {
        currentPeriod--;
        document.location.href = "./" + currentPeriod;
    }
}