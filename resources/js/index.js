


$(document).ready(function() {
  $(".izbr").click(function(e){
      e.preventDefault();
      izbrannoe($(this).data("pois_id"));
      $(this).toggleClass('fa-star-o');
      $(this).toggleClass('starred');
      $(this).toggleClass('fa-star');

  });

  var izbr=readCookie('izbr');
  var izbr_arr= izbr.split('\|');
  for (var i = 0; i < izbr_arr.length; i++) {
    $('#izbr'+izbr_arr[i]).toggleClass('fa-star-o');
    $('#izbr'+izbr_arr[i]).toggleClass('starred');
    $('#izbr'+izbr_arr[i]).toggleClass('fa-star');
  }


  $('.owl-carousel').owlCarousel({
    loop:false,
    margin:10,
    nav:true,
    items:4,
    autoWidth:true,
    autoHeight: false,
    responsive:{
        0:{
            items:1
        },
        600:{
            items:2
        },
        1000:{
            items:4
        }
    }
})


});




function readCookie(cookieName) {
   var re = new RegExp('[; ]'+cookieName+'=([^\\s;]*)');
   var sMatch = (' '+document.cookie).match(re);
   if (cookieName && sMatch) return unescape(sMatch[1]);
}

function setCookie(name,value,days) {
   if (days) {
       var date = new Date();
       date.setTime(date.getTime() + (days*24*60*60*1000));
       var expires = "; expires=" + date.toGMTString();
   }
   else var expires = "";
   document.cookie = name + "=" + escape(value) + expires + "; path=/";

}

function in_array(value, array)
{
   for(var i = 0; i < array.length; i++)
   {
       if(array[i] == value) return true;
   }
   return false;
}

function izbrannoe (id) {

var izbr=readCookie('izbr');
if (!izbr) izbr=""; izbr_arr= izbr.split('\|');
if (in_array(id,izbr_arr))   {
  for (var i = 0; i < izbr_arr.length; i++)
  if (izbr_arr[i]==id) izbr_arr.splice(i,1)
}
else izbr_arr.push(id);

var topaste= izbr_arr.join('\|');
setCookie('izbr',topaste,30);


}
