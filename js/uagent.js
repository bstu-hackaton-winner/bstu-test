function get_device(userAgent) {
    userAgent = userAgent.toLowerCase();
    let os = "Unknown OS Platform";

    let match = ["windows nt 10","windows nt 6.3","windows nt 6.2","windows nt 6.1","windows nt 6.0","windows nt 5.2","windows nt 5.1","windows xp","windows nt 5.0","windows me","win98","win95","win16","iphone","ipod","ipad","macintosh","mac os x","mac_powerpc","android","linux","ubuntu","blackberry","webos"];
    let result = [
        "<i class=\"fab fa-windows\" style='color: dodgerblue;'></i> Windows 10",
        "<i class=\"fab fa-windows\" style='color: dodgerblue;'></i> Windows 8.1",
        "<i class=\"fab fa-windows\" style='color: dodgerblue;'></i> Windows 8",
        "<i class=\"fab fa-windows\" style='color: dodgerblue;'></i> Windows 7",
        "<i class=\"fab fa-windows\" style='color: dodgerblue;'></i> Windows Vista",
        "<i class=\"fab fa-windows\" style='color: dodgerblue;'></i> Windows Server 2003/XP x64",
        "<i class=\"fab fa-windows\" style='color: dodgerblue;'></i> Windows XP",
        "<i class=\"fab fa-windows\" style='color: dodgerblue;'></i> Windows XP",
        "<i class=\"fab fa-windows\" style='color: dodgerblue;'></i> Windows 2000",
        "<i class=\"fab fa-windows\" style='color: dodgerblue;'></i> Windows ME",
        "<i class=\"fab fa-windows\" style='color: dodgerblue;'></i> Windows 98",
        "<i class=\"fab fa-windows\" style='color: dodgerblue;'></i> Windows 95",
        "<i class=\"fab fa-windows\" style='color: dodgerblue;'></i> Windows 3.11",
        "<i class=\"fab fa-apple\" style='color: black;'></i> iPhone",
        "<i class=\"fab fa-apple\" style='color: black;'></i> iPod",
        "<i class=\"fab fa-apple\" style='color: black;'></i> iPad",
        "<i class=\"fab fa-apple\" style='color: black;'></i> Mac OS X",
        "<i class=\"fab fa-apple\" style='color: black;'></i> Mac OS X",
        "<i class=\"fab fa-apple\" style='color: black;'></i> Mac OS 9",
        "<i class=\"fab fa-android\" style='color: green;'></i> Android",
        "<i class=\"fab fa-linux\" style='color: black;'></i> Linux",
        "<i class=\"fab fa-ubuntu\" style='color: orangered;'></i> Ubuntu",
        "<i class=\"fab fa-blackberry\" style='color: black;'></i> BlackBerry",
        "<i class=\"fab fa-mobile\" style='color: dodgerblue;'></i> Mobile"
    ];

    let browsers = {
        "chrome": ""
    }

    for (let i = 0; i < match.length; i++) {
        if (userAgent.indexOf(match[i]) !== -1) {
            os = result[i];
            break;
        }
    }
    return os;
}

function get_browser(ua)
{
    ua = ua.toLowerCase();
    if (ua.search(/msie/) > -1) return '<i class=\"fa fa-internet-explorer\" style=\'color: dodgerblue;\'></i> Internet Explorer';
    if (ua.search(/firefox/) > -1) return '<i class=\"fab fa-firefox-browser\" style=\'color: orangered;\'></i> Firefox';
    if (ua.search(/opera/) > -1) return '<i class=\"fa fa-opera\" style=\'color: red;\'></i> Opera';
    if (ua.search(/chrome/) > -1) return '<i class=\"fa fa-chrome\" style=\'color: green;\'></i> Google Chrome';
    if (ua.search(/safari/) > -1) return '<i class=\"fa fa-safari\" style=\'color: dodgerblue;\'></i> Safari';
    if (ua.search(/konqueror/) > -1) return '<i class=\"fa fa-globe\" style=\'color: gray;\'></i> Konqueror';
    if (ua.search(/iceweasel/) > -1) return '<i class=\"fa fa-globe\" style=\'color: gray;\'></i> Debian Iceweasel';
    if (ua.search(/seamonkey/) > -1) return '<i class=\"fa fa-globe\" style=\'color: gray;\'></i> SeaMonkey';

    // Браузеров очень много, все вписывать смысле нет, Gecko почти везде встречается
    if (ua.search(/gecko/) > -1) return '<i class=\"fa fa-globe\" style=\'color: gray;\'></i> Gecko';

    // а может это вообще поисковый робот
    return '<i class=\"fa fa-globe\" style=\'color: gray;\'></i> Unknown Browser';
}