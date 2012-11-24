/* JS here */
var $j = jQuery.noConflict();
var isSupportHTML5Feature = (function(){
    // Used for detacting HTML5 features for an input element.
    var input = document.createElement("input");
    var isUnsupport = (/(safari|iphone|android)/).test(navigator.userAgent.toString().toLowerCase());

    // check for placeholder attribute support
    if(isUnsupport || !('placeholder' in input)){
        return false;
    }
    return true;
})();

$j(document).ready(function(){

    // Variables
    var CONTENT = {},
        activeHref = '';

    // elements
    var menu     = $j('#main-menu a'),
        openBtn  = $j('#main-nav-open'),
        backBtn  = $j('#main-nav-back'),
        closeBtn = $j('#main-panel .close');

    // class
    var activePanel   = 'active-panel',
        activeMenu    = 'active-menu',
        activeSubMenu = 'active-sub-menu',
        loading       = 'loading';

    // Functions
    var self = {
            /**
             * Content animation
             */
            moveMenu: function(elem, option, callback){
                if($j(elem).length){
                    option = option || {};
                    if(option.move == 'hide'){
                        $j(elem).removeClass(option.cls || '').hide('slide', {'direction': 'left'}, 400, callback);
                    }else{
                        $j(elem).addClass(option.cls || '').show('slide', {'direction': 'left'}, 600, callback);
                    }
                }else if(typeof(callback) == 'function'){
                    callback();
                }
            },

            /**
             * Show menu observing
             */
            showMenuObsv: function(elem, callback){
                elem = $j(elem);
                elem.off('click');
                elem.on('click', function(){
                    // slide show menu
                    self.moveMenu($j('#main-panel'),
                        {'cls': 'active-panel', 'move': 'show'},
                        function(){
                            self.moveMenu($j('.' + activeSubMenu),
                                {'move': 'show'},
                                function(){
                                    self.moveMenu($j('#content-panel.' + activePanel),
                                        {'move': 'show'}
                                    )
                                }
                            )
                        }
                    );
                    if(typeof(callback) == 'function'){
                        callback();
                    }
                    return false;
                });
            },

            /**
             * Show menu observing
             */
            hideMenuObsv: function(elem, callback){
                elem = $j(elem);
                elem.off('click');
                elem.on('click', function(){
                    // slide hide menu
                    self.moveMenu(
                        $j('#content-panel.active-panel'),
                        {'move': 'hide'},
                        function(){
                            self.moveMenu($j('.' + activeSubMenu),
                                {'move': 'hide'},
                                function(){
                                    self.moveMenu($j('#main-panel'),
                                        {'move': 'hide'},
                                        function(){
                                            if(typeof(callback) == 'function'){
                                                callback();
                                            }
                                        }
                                    )
                                }
                            )
                        }
                    );
                    return false;
                });
            },

            /**
             * Menu Observing
             */
            menuPanelObsv: function(){
                // Observe menu
                var menu = $j('#main-menu a');
                if(menu.length){
                    menu.off('click');
                    menu.on('click', function(){
                        var link = $j(this),
                            // sub menu
                            submenu = link.next('.sub-menu');

                        if(link.parent().hasClass(activeMenu)){
                            return false;
                        }

                        // hide back button
                        backBtn.hide();

                        // reset li
                        link.parent().siblings().removeClass(activeMenu);
                        link.parent().siblings().find('.' + activeMenu).removeClass(activeMenu);
                        link.parent().addClass(loading).addClass(activeMenu);

                        // slide hide menu
                        self.moveMenu(
                            $j('#content-panel.active-panel'),
                            {'cls': activePanel, 'move': 'hide'},
                            function(){
                                self.moveMenu(link.parent().siblings().find('.' + activeSubMenu),
                                    {'cls': activeSubMenu, 'move': 'hide'},
                                    function(){
                                        self.moveMenu(submenu,
                                            {'cls': activeSubMenu, 'move': 'show'},
                                            function(){
                                                $j('.' + loading).removeClass(loading);
                                                if(!submenu.length){
                                                    activeHref = link.attr('href');
                                                    if(CONTENT[activeHref]){
                                                        self.updateContent(activeHref);
                                                    }else{
                                                        link.parent().addClass(loading).addClass(activeMenu);
                                                        self.callContent(link, { callback: function(){ link.parent().removeClass(loading); }});
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
            },

            /**
             * Getting content from url
             */
            callContent: function(elem, opt){
                var href = $j(elem).attr('href'),
                    option = opt || {};

                if(!href){
                    return;
                }

                if(CONTENT[href]){
                    self.updateContent(href);
                }else{
                    var div = $j('<div />');
                    div.load(href + ' #site-wrapper', function() {
                        CONTENT[href] = {
                            'content'    : div,
                            'type'       : div.find('#content-panel').length
                                                ? 'main'
                                                : (div.find('.detail-box').length ? 'sub' : 'image'),
                            'main'       : div.find('#content-panel'),
                            'sub'        : div.find('.detail-box'),
                            'bullet'     : div.find('.bullet'),
                            'background' : div.find('.custom-background')
                        }
                        self.updateContent(href);
                        if(option.callback && typeof(option.callback) == 'function'){
                            option.callback();
                        }
                    });
                }
            },

            /**
             * Update Content
             */
            updateContent: function(href, opt){
                var content = CONTENT[href] || {},
                    option  = opt || {};

                if(!content || !content['content']){
                    return;
                }

                try{
                    var container = $j('#site-wrapper'),
                        type = content['type'];

                    // Main content
                    if(type == 'main' && content['main'].length){
                        $j('.detail-box').hide('fade', {}, 400);
                        if(!option.back){
                            $j('#content-panel').remove(); // Need to remove and create the #content-panel to apply scroll bar

                            var mainHtml = content['main'].html(),
                                contentPanel = $j('<div id="content-panel" />');

                            container.append(contentPanel.hide().html(mainHtml));
                            if($j('.active-sub-menu').length){
                                contentPanel.css('left', $j('.active-sub-menu').outerWidth() + $j('#main-panel').outerWidth());
                            }
                            contentPanel.addClass('active-panel').show('slide', {'direction': 'left'}, 800);
                            self.applyContentScrollbar();
                            self.imageListObsv();
                            self.showBackgroundObsv();

                            self.togglingInput($j('input[placeholder]'));
                            // contact us form observe (plug in)
                            try{ observeContactFrom(); }catch(err){}
                        }

                    // Small box content
                    }else if(type == 'sub'){
                        var subHtml = content['sub'].html(),
                            boxPanel = $j('<div class="detail-box" />');

                        if($j('.detail-box').length){
                            $j('.detail-box').hide('fade', {}, 400, function(){
                                $j('.detail-box').remove();
                                container.append(boxPanel.hide().html(subHtml));
                                boxPanel.show('fade', {}, 800, self.boxNav);
                                self.applyContentScrollbar();
                            });
                        }else{
                            container.append(boxPanel.hide().html(subHtml));
                            boxPanel.show('fade', {}, 800, self.boxNav);
                            self.applyContentScrollbar();
                        }
                        if(activeHref){
                            backBtn.show('fade', {}, 200);
                        }
                        $j('#main-panel .close').trigger('click');
                    }

                    // background
                    if(content['background'].length){
                        var newSrc = content['background'].find('img').attr('src') || '',
                            bg     = $j('.custom-background'),
                            oldSrc = $j('.custom-background img').length ? $j('.custom-background img').attr('src') : '';

                        if(newSrc && newSrc != oldSrc){
                            container.append(content['background'].hide());
                            self.customBackgroundObsv();
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
                                content['bullet'].show('fade', {}, 800, self.bulletObsv);
                            }
                        });
                    }else if(content['bullet'].length){
                        container.append(content['bullet']);
                        content['bullet'].show('fade', {}, 800, self.bulletObsv);
                    }

                }catch(err){ /*console.log(err)*/ }
            },

            /**
             * Scrollbar
             */
            applyContentScrollbar: function(){
                try{
                    if(!$j('#content-panel .mCustomScrollbar').length){
                        $j('#content-panel > .content-mask').mCustomScrollbar();
                    }
                    if(!$j('.detail-box .mCustomScrollbar').length){
                        var box = $j('.detail-box .detail');
                        if(box.length){
                            box.css('height', 'auto');
                            if(box.height() >= 195){
                                box.height(195);
                            }
                            box.mCustomScrollbar();
                        }
                    }
                }catch(err){}
            },

            /**
             * Bullet observing
             */
            imageListObsv: function(){
                var images = $j('.image-list a');
                images.off('click');
                images.on('click', function(){
                    self.callContent(this);
                    $j('#main-panel .close').trigger('click');
                    return false;
                });
            },

            /**
             * Bullet observing
             */
            bulletObsv: function(){
                var bullets = $j('.bullet');
                bullets.off('click');
                bullets.on('click', function(){
                    self.callContent(this);
                    return false;
                });
            },

            /**
             * Background observing
             */
            customBackgroundObsv: function(){
                var bg = $j('.custom-background');
                bg.off('click');
                bg.on('click', function(){
                    $j('#main-panel .close').trigger('click');
                    if($j('#main-panel').css('display') == 'none'){
                        var img = $j(this).find('img[data-href]');
                        if(img.attr('data-href')){
                            window.open(img.attr('data-href'));
                        }
                    }
                    return false;
                });
            },

            /**
             * Background observing
             */
            showBackgroundObsv: function(){
                var bg = $j('.show-background');
                bg.off('click');
                bg.on('click', function(){
                    if(activeHref){
                        // update image if not a current one
                        self.updateContent(activeHref, {'back' : 1});
                        backBtn.hide();
                    }
                    $j('#main-panel .close').trigger('click');
                    return false;
                });
            },

            /**
             * Smallbox navigation observing
             */
            boxNav: function(){
                var nav = $j('#detail-nav-back a, #detail-nav-next a');
                nav.off('click');
                nav.on('click', function(){
                    self.callContent(this);
                    return false;
                });
            },

            togglingInput: function(inp) {
                if (inp && !isSupportHTML5Feature) {
                    $j(inp).each(function() {
                        var i = $j(this),
                            placeholder = i.attr('placeholder');
                        if (placeholder) {
                            if (i.val() == '') i.val(placeholder);
                            i.off('focus');
                            i.on('focus', function() {
                                if (i.val() == placeholder) this.value = '';
                            });
                            i.off('blur');
                            i.on('blur', function() {
                                if (i.val() == '') i.val(placeholder);
                            });
                        }
                    });
                }
            }
        };

    /******************************************************
     * Prepare content before appling observe and navigation
     */

    // Menu style and class
    $j('.current-menu-item, .current-menu-parent, .current-menu-ancestor').addClass('active-menu');
    $j('.current-menu-item .sub-menu, .current-menu-parent .sub-menu, .current-menu-ancestor .sub-menu').addClass('active-sub-menu').show();
    if($j('.active-sub-menu').length){
        $j('#content-panel').css('left', $j('.active-sub-menu').width() + $j('#main-panel').outerWidth());
    }

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

    /******************************************************
     * Centerize height
     */
    var wrapperHeight = $j('#site-wrapper').height(),
        screenHeight  = $j(window).height();
    if(wrapperHeight < screenHeight){
        $j('#site-wrapper').css('margin-top', (screenHeight - wrapperHeight) / 2);
    }
    $j(window).resize(function(){
        var h = $j(window).height();
        $j('#site-wrapper').css('margin-top', wrapperHeight < h ? (h - wrapperHeight) / 2 : 0);
    });

    /******************************************************
     * Prepare CONTENT
     */
    var wrapper  = $j('#site-wrapper').clone(),
        initHref = location.href;
    CONTENT[initHref] = {
        'content'    : wrapper,
        'type'       : wrapper.find('#content-panel').length
                            ? 'main'
                            : (wrapper.find('.detail-box').length ? 'sub' : 'image'),
        'main'       : wrapper.find('#content-panel'),
        'sub'        : wrapper.find('.detail-box'),
        'bullet'     : wrapper.find('.bullet'),
        'background' : wrapper.find('.custom-background')
    };
    activeHref = CONTENT[initHref].type == 'main' ? initHref : '';

    /******************************************************
     * Observe/apply to dom elements
     */
    // Scrollbar
    self.applyContentScrollbar();

    // Main Navigation
    self.showMenuObsv(openBtn);
    self.showMenuObsv(backBtn, function(){
        if(activeHref){
            self.updateContent(activeHref, {'back' : 1});
            backBtn.hide();
        }
    });
    self.hideMenuObsv(closeBtn);
    self.menuPanelObsv();

    // Background Navigation
    self.imageListObsv();
    self.showBackgroundObsv();
    self.bulletObsv();
    self.customBackgroundObsv();
    self.boxNav();

    // Form
    self.togglingInput($j('input[placeholder]'));
});
