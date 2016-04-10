GlizyApp.pages[ 'desiderataLibrary.modules.importPDF.views.Admin' ] = function( state, routing ) {
    $(function(){
        if ('index'==state) {
            Glizy.module('desiderataLibrary.modules.importPDF.views.Admin@Index').run();
        }
    });
}

