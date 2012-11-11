/* JS here */
var $j = jQuery.noConflict(),
    CONTENT = {};

$j(document).ready(function(){
    prepareContent();
    applyScrollbar();
    menuPanel();
    bulletObsv();
    boxNav();
});

function prepareContent(){
    /**
     * Prepare content before apply observe and navigation
     */

    // Menu style and class
    $j('.current-menu-item, .current-menu-parent, .current-menu-ancestor').addClass('active-menu');
    $j('.current-menu-item .sub-menu, .current-menu-parent .sub-menu, .current-menu-ancestor .sub-menu').addClass('active-sub-menu').show();
    if($j('.active-sub-menu').length){
        $j('#content-panel').css('left', $j('.active-sub-menu').width() + $j('#main-panel').outerWidth());
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
    var current = $j('#main-menu .current-menu-item');
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
    if(!$j('#content-panel .mCustomScrollbar').length){
        $j("#content-panel > .content-mask").mCustomScrollbar();
    }
}

function bulletObsv(){
    var bullets = $j('.bullet');
    bullets.off('click');
    bullets.on('click', function(){
        callContent(this);
        return false;
    });
}

function boxNav(){
    var nav = $j('#detail-nav-back a, #detail-nav-next a');
    nav.off('click');
    nav.on('click', function(){
        callContent(this);
        return false;
    });
}

function menuPanel(){
    // elements
    var menu     = $j('#main-menu a'),
        openBtn  = $j('#main-nav-open'),
        closeBtn = $j('#main-panel .close');

    // class
    var activePanel   = 'active-panel',
        activeMenu    = 'active-menu',
        activeSubMenu = 'active-sub-menu',
        loading       = 'loading';

    // function
    var menuMove = function(elem, option, callback){
            if($j(elem).length){
                option = option || {};
                if(option.move == 'hide'){
                    $j(elem).removeClass(option.cls || '').hide('slide', {'direction': 'left'}, 400, callback);
                }else{
                    $j(elem).addClass(option.cls || '').show('slide', {'direction': 'left'}, 800, callback);
                }
            }else if(typeof(callback) == 'function'){
                callback();
            }
        };

    // Observe open navigation
    openBtn.off('click');
    openBtn.on('click', function(){
        menuMove( $j('#main-panel'), {'cls': activePanel, 'move': 'show', 'from': 'openBtn'});
        return false;
    });

    // Observe close navigation
    closeBtn.off('click');
    closeBtn.on('click', function(){
        // slide hide menu
        menuMove(
            $j('#content-panel.active-panel'),
            {'cls': activePanel, 'move': 'hide', 'from': 'closeBtn 1'},
            function(){
                menuMove($j('.' + activeSubMenu),
                    {'cls': activeSubMenu, 'move': 'hide', 'from': 'closeBtn 2'},
                    function(){
                        menuMove($j('#main-panel'),
                            {'cls': activePanel, 'move': 'hide', 'from': 'closeBtn 3'},
                            function(){
                                $j('#main-panel').find('.' + activeMenu).removeClass(activeMenu);
                            }
                        )
                    }
                )
            }
        );

        return false;
    });

    // Observe menu
    if(menu.length){
        menu.off('click');
        menu.on('click', function(){
            var link = $j(this),
                // sub menu
                submenu = link.next('.sub-menu');

            if(link.parent().hasClass(activeMenu)){
                return false;
            }

            // reset li
            link.parent().siblings().removeClass(activeMenu);
            link.parent().siblings().find('.' + activeMenu).removeClass(activeMenu);
            link.parent().addClass(loading).addClass(activeMenu);

            // slide hide menu
            menuMove(
                $j('#content-panel.active-panel'),
                {'cls': activePanel, 'move': 'hide', 'from': 'menu 1'},
                function(){
                    menuMove(link.parent().siblings().find('.' + activeSubMenu),
                        {'cls': activeSubMenu, 'move': 'hide', 'from': 'menu 2'},
                        function(){
                            menuMove(submenu,
                                {'cls': activeSubMenu, 'move': 'show', 'from': 'menu 3'},
                                function(){
                                    $j('.' + loading).removeClass(loading);
                                    if(!submenu.length){
                                        var href = link.attr('href');
                                        if(CONTENT[href]){
                                            updateContent(href);
                                        }else{
                                            link.parent().addClass(loading).addClass(activeMenu);
                                            callContent(link, { callback: function(){ link.parent().removeClass(loading); }});
                                        }
                                    }
                                }
                            )
                        }
                    )
                }
            );
            return false;
        });
    }
}

function callContent(elem, opt){
    var href = $j(elem).attr('href'),
        option = opt || {};

    if(!href){
        return;
    }

    if(CONTENT[href]){
        updateContent(href);
    }else{
        var div = $j('<div />');
        div.load(href + ' #site-wrapper', function() {
            CONTENT[href] = {
                'content'    : div,
                'type'       : div.find('#content-panel').length ? 'main' : 'sub',
                'main'       : div.find('#content-panel'),
                'sub'        : div.find('.detail-box'),
                'bullet'     : div.find('.bullet'),
                'background' : div.find('.custom-background')
            }
            updateContent(href);
            if(option.callback && typeof(option.callback) == 'function'){
                option.callback();
            }
        });
    }
}

function updateContent(href){
    var content = CONTENT[href] || {};
    if(!content || !content['content']){
        return;
    }

    try{
        var container = $j('#site-wrapper'),
            type = content['type'];

        // Content
        if(type == 'main' && content['main'].length){
            $j('#content-panel').remove();
            $j('.detail-box').hide('fade', {}, 400);

            container.append(content['main'].hide());
            if($j('.active-sub-menu').length){
                content['main'].css('left', $j('.active-sub-menu').outerWidth() + $j('#main-panel').outerWidth());
            }
            applyScrollbar();
            content['main'].addClass('active-panel').show('slide', {'direction': 'left'}, 800);

        }else if(type == 'sub'){
            if($j('.detail-box').length){
                $j('.detail-box').hide('fade', {}, 400, function(){
                    $j('.detail-box').remove();
                    container.append(content['sub'].hide());
                    content['sub'].show('fade', {}, 800, boxNav);
                });
            }else{
                container.append(content['sub'].hide());
                content['sub'].show('fade', {}, 800, boxNav);
            }
            $j('#main-panel .close').trigger('click');
        }

        // background
        if(content['background'].length){
            var newSrc = content['background'].find('img').attr('src') || '',
                bg     = $j('.custom-background'),
                oldSrc = $j('.custom-background img').length ? $j('.custom-background img').attr('src') : '';

            container.append(content['background'].hide());
            if(newSrc && newSrc != oldSrc){
                if(bg.length){
                    bg.hide('fade', {}, 400, function(){
                        bg.remove();
                        content['background'].show('fade', {}, 1000);
                    });
                }else{
                    content['background'].show('fade', {}, 1000);
                }
            }
        }

        // bullet
        if($j('.bullet').length){
            $j('.bullet').hide('fade', {}, 400, function(){
                $j('.bullet').remove();
                if(content['bullet'].length){
                    container.append(content['bullet']);
                    content['bullet'].show('fade', {}, 800, bulletObsv);
                }
            });
        }else if(content['bullet'].length){
            container.append(content['bullet']);
            content['bullet'].show('fade', {}, 800, bulletObsv);
        }

    }catch(err){ console.log(err) }
}