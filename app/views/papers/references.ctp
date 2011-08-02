<div id="ref-chooser">
    <?php echo $this->Form->create('Type');?>
    <?php echo $this->Form->input('types', array('type'=>'select','options'=>$types, 'selected' =>$type, 'label' => false));?>
    <?php echo $this->Form->end(); ?>
</div>
<div id="ref-content" class="modal-content">
    <?php echo $this->element('papers/references'); ?>
</div>

<script type="text/javascript">

$(document).ready(function() {
    //bind listener to filer references
    $('#TypeReferencesForm').change(function() {
        //get the selected value
        var type = $('#TypeReferencesForm option:selected').val();

        //remove the prefix and generate url
        var sub = type.substring(5);
        var all = '';
        if(sub == 'all'){
            //if all -> add post param to json request#
            all = 'all';
            var url = 'papers/references/paper/<?php echo $paper_id; ?>';
        }else{
            var url = 'papers/references/' + sub;
        }

        var req = $.post(base_url + '/' + url + '.json', {all:all})
           .success(function( string ){
               $('#ref-content').html(string);
           })
           .error(function(){
               alert('error');
        });


    });

});

</script>