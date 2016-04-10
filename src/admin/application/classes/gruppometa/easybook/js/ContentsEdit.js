GlizyApp.pages[ 'gruppometa.easybook.views.ContentsEdit' ] = function( state, routing ) {
    $(function(){
        if ('index'==state) {
            var tree = new GlizycmsSiteTree("#js-glizycmsSiteTree", "#js-glizycmsSiteTreeAdd");
            // Glizy.module('cms.SiteTree').run();
            Glizy.module('cms.PageEditIframe').run();
        }
    });
}
