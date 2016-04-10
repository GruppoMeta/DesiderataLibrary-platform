Glizy.module('desiderataLibrary.modules.importPDF.views.Admin@Index', function(){
    var self = this;
    this.nodePicker;

    this.run = function() {
        $("#sourceFile").change(function(e){
            var sourceFile = $("#sourceFile").val();
            if (sourceFile) {
                $("#btnNext").removeAttr('disabled');
            } else {
                $("#btnNext").attr("disabled", true);
            }
        });
        $("#btnNext").attr("disabled", true);
    };
});