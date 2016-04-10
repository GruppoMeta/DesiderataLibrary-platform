GlizyApp.pages[ 'desiderataLibrary.modules.importEPUB.views.Admin' ] = function( state, routing ) {
    $(function(){
        if ('index'==state) {
            Glizy.module('desiderataLibrary.modules.importEPUB.views.Admin@Index').run();
        }
    });
}

