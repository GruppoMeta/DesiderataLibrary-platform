Glizy.module('desiderataLibrary.plugins.export.views.PluginView@Index', function(){
    var self = this;
    var menuIds = [];

    this.run = function() {
        $('.publication li').click(function(){
            $(this).toggleClass('button-selected');
            self.validate();
        });

        self.validate();
    };


    this.validate = function() {
        menuIds = [];
        $('.publication li.button-selected').each(function(index, el) {
            menuIds.push($(el).data('menuid'));
        });

        $("#btnNext").attr("disabled", menuIds.length==0);
        $("#btnRemove").attr("disabled", menuIds.length==0);
        $('#menuId').val(JSON.stringify(menuIds));
    }
});