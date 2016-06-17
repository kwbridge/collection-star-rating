(function() {
    tinymce.create("tinymce.plugins.star_button_plugin", {

        //url argument holds the absolute url of our plugin directory
        init : function(ed, url) {

            //add new button    
            ed.addButton("star", {
                title : "My Star Rating",
                cmd : "star_command",
                image : url + '/star.png',
            });

            //button functionality.
            ed.addCommand("star_command", function() {
                
                var return_text = "[star]";
                ed.execCommand("mceInsertContent", 0, return_text);
            });

        },

        createControl : function(n, cm) {
            return null;
        },

        getInfo : function() {
            return {
                longname : "Extra Buttons",
                author : "Narayan Prusty",
                version : "1"
            };
        }
    });

    tinymce.PluginManager.add("star_button_plugin", tinymce.plugins.star_button_plugin);
})();