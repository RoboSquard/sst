function processAdvert(advert) {
    
    let beginingHtml =
        '<li rel="1" class="dcsns-li dcsns-facebook dcsns-twitter dcsns-google dcsns-youtube dcsns-flickr dcsns-pinterest dcsns-rss dcsns-tumblr dcsns-instagram dcsns-dribbble dcsns-feed-1" style="position: absolute; left: 560px; top: 714px; width:260px;"><div style="padding-top: 0px; margin-bottom:-5px" class="inner">';
    let EndingHtml = '</div></li>';
    let newAdvert = beginingHtml + advert + EndingHtml;

    return newAdvert;
}

// function addAdvert(advert) {
//     setTimeout(function () {
//         let beginingHtml =
//             '<li rel="1" class="dcsns-li dcsns-facebook dcsns-twitter dcsns-google dcsns-youtube dcsns-flickr dcsns-pinterest dcsns-rss dcsns-tumblr dcsns-instagram dcsns-dribbble dcsns-feed-1" style="position: absolute; left: 560px; top: 714px; width:300px; height:300px"><div style="padding-top: 0px; margin-bottom:-5px" class="inner">';
//         let EndingHtml = '</div></li>';
//         var $newItems = $(beginingHtml + advert + EndingHtml);
//         $('.stream').isotope('insert', $newItems).isotope('layout');
//     }, 3000);
// }


