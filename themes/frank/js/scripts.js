$(document).ready(function() {
    $("a[rel=gallery]").fancybox({
        'transitionIn'		: 'none',
        'transitionOut'		: 'none',
        'titlePosition' 	: 'over',
        'titleFormat'		: function(title, currentArray, currentIndex, currentOpts) {
            return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
        }
    });

    updateNav();
});


function updateNav(){
    var location = window.location.pathname.split( '/' );
    var name = location[location.length-1].replace(/\..*(?!.*\.)/, "").toLowerCase();
    if(name == 'index'){ name = 'home' };
    navText = $("ul.nav").children('li').find('a');
    $(navText).each(function(index) {
        var linkText = $(this).text().toLowerCase();
        if(linkText.indexOf(name) != -1)
        {
            $(this).parent().attr('class', 'active');
        }
    });
   
}
