$(document).ready(function(){
    makeATripPopupShow()
})

function makeATripNextTab(tabNum){
    var val = -(tabNum * 100);
    $('.popup_that_shows_on_startup #make_a_trip_popup .tabs').css("transition", "all 0.5s")
    $('.popup_that_shows_on_startup #make_a_trip_popup .tabs').css("transform", `translateX(${val}%)`)
}
$('#make_a_trip_popup .tab .trip_categories > span').click(function(){
    $(this).toggleClass('active')
})

function makeATripNextTab(tabNum){
    var val = -(tabNum * 100);
    $('.popup_that_shows_on_startup #make_a_trip_popup .tabs').css("transition", "all 0.5s")
    $('.popup_that_shows_on_startup #make_a_trip_popup .tabs').css("transform", `translateX(${val}%)`)
}
$('#make_a_trip_popup .tab .trip_categories > span').click(function(){
    $(this).toggleClass('active')
})

// *******************************
// *******************************

function makeATripPopupShow(){
    setTimeout(() => {
        $('.popup_that_shows_on_startup #make_a_trip_popup').removeClass('hide')
        $('.popup_that_shows_on_startup #popup_background').removeClass('hide')
    }, 200);
    $('.popup_that_shows_on_startup #popup_background').click(function(){
        // alert('hi')
        $('.popup_that_shows_on_startup #make_a_trip_popup').addClass('hide')
        $('.popup_that_shows_on_startup #popup_background').addClass('hide')
    })
}