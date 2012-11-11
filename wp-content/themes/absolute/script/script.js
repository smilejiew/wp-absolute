/* JS here */
var $j = jQuery.noConflict();

$j(document).ready(function(){
    prepareContent();
    applyScrollbar();
    menuPanel();

    /**
     * TODO:
     * 1. Prepare Content
     * 2. Menu animation
     * 3. Normal content position and Ajax
     * 4. Bullet
     * 5. Box content
     * 6. Background
     */

});

function prepareContent(){
    /**
     * Prepare content before apply observe and navigation
     */

    // Menu style and class
    $j('.current-menu-item, .current-menu-parent, .current-menu-ancestor').addClass('active-menu');
    $j('.current-menu-item .sub-menu, .current-menu-parent .sub-menu, .current-menu-ancestor .sub-menu').addClass('active-sub-menu').show();
    if($j('.active-sub-menu').length){
        $j('#content-panel').css('left', $j('.active-sub-menu').outerWidth() + $j('#main-panel').outerWidth());
    }

    // Centerize height
    var wrapperHeight = $j('#site-wrapper').height(),
        screenHeight  = $j(window).height();
    if(wrapperHeight < screenHeight){
        $j('#site-wrapper').css('margin-top', (screenHeight - wrapperHeight) / 2);
    }
    $j(window).resize(function(){
        var h = $j(window).height();
        $j('#site-wrapper').css('margin-top', wrapperHeight < h ? (h - wrapperHeight) / 2 : 0);
    });

    // Check active menu to defind show/hide content
    var current = $j('#menu-main .current-menu-item');
    if(!current.length){
        // Hide all panels and navigation
        $j('#main-panel, #content-panel, #main-nav-back').hide();
    }else{
        $j('#main-nav-back').hide();
        $j('#main-panel, #content-panel').addClass('active-panel');
        if(current.parent().hasClass('sub-menu')){
            current.parent().show();
        }
    }
}

function applyScrollbar(){
    $j("#content-panel > .content-mask").mCustomScrollbar();
}

function menuPanel(){
    // elements
    var menu     = $j('#menu-main > li > a'),
        openBtn  = $j('#main-nav-open'),
        closeBtn = $j('#main-panel .close');

    // class
    var activePanel   = 'active-panel',
        activeMenu    = 'active-menu',
        activeSubMenu = 'active-sub-menu';

    // function
    var menuMove = function(elem, option, callback){
            if($j(elem).length){
                option = option || {};
                if(option.move == 'hide'){
                    $j(elem).removeClass(option.cls || '').hide('slide', {'direction': 'left'}, 500, callback);
                }else{
                    $j(elem).addClass(option.cls || '').show('slide', {'direction': 'left'}, 1000, callback);
                }
                return ;
            }else if(typeof(callback) == 'function'){
                callback();
            }
        };

    // Observe open navigation
    openBtn.click(function(){
        menuMove( $j('#main-panel'), {'cls': activePanel, 'move': 'show'});
        return false;
    });

    // Observe close navigation
    closeBtn.click(function(){
        // slide hide menu
        menuMove(
            $j('#content-panel.active-panel'),
            {'cls': activePanel, 'move': 'hide'},
            menuMove.bind(this,
                $j('.' + activeSubMenu),
                {'cls': activeSubMenu, 'move': 'hide'},
                menuMove.bind(this,
                    $j('#main-panel'),
                    {'cls': activePanel, 'move': 'hide'},
                    function(){
                        $j('#main-panel').find('.' + activeMenu).removeClass(activeMenu);
                    }
                )
            )
        );

        return false;
    });

    // Observe menu
    if(menu.length){
        menu.click(function(){
            var link = $j(this),
                // sub menu
                submenu = link.next('.sub-menu');

            if(link.parent().hasClass(activeMenu)){
                return false;
            }

            // reset li
            link.parent().siblings().removeClass(activeMenu);
            link.parent().siblings().find('.' + activeMenu).removeClass(activeMenu);
            link.parent().addClass(activeMenu);

            // slide hide menu
            menuMove(
                $j('#content-panel.active-panel'),
                {'cls': activePanel, 'move': 'hide'},
                menuMove.bind(this,
                    $j('.' + activeSubMenu),
                    {'cls': activeSubMenu, 'move': 'hide'},
                    menuMove.bind(this,
                        submenu,
                        {'cls': activeSubMenu, 'move': 'show'}
                    )
                )
            );
            // TODO: Ajax call for content
            return false;
        });
    }
}