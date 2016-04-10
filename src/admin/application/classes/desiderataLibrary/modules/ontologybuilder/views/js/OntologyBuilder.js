GlizyApp.pages[ 'desiderataLibrary.modules.ontologybuilder.views.OntologyBuilder' ] = function( state, routing ) {

	if ( state == 'edit' ) {
        OntologyBuilderEditor.start();
        OntologyBuilderEditor.loadEntity(routing["id"]);
    }
    else {

    }
}