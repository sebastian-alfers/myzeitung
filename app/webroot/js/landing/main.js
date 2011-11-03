$(document).ready(
    function(){
        $( "#modal-popup-bundestag" ).dialog({
                resizable: false,
                height:600,
                width:1100,
                draggable:false,
                modal: true,
                autoOpen: true,
                close: function(){
                    goTo('http://www.myzeitung.de');
                }
        });
    }
);
