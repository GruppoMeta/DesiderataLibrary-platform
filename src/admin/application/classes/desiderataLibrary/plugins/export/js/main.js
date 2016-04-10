GlizyApp.pages[ 'desiderataLibrary.plugins.export.views.PluginView' ] = function( state, routing ) {
    $(function(){
        if ('index'==state) {
            Glizy.module('desiderataLibrary.plugins.export.views.PluginView@Index').run();
        }
    });
}

