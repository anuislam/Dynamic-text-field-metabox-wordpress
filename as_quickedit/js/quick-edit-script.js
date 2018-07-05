jQuery(document).ready(function($){
    //Prepopulating our quick-edit post info
    var $inline_editor = inlineEditPost.edit;
    inlineEditPost.edit = function(id){        //call old copy 
        $inline_editor.apply( this, arguments);
        //our custom functionality below
        var post_id = 0;
        if( typeof(id) == 'object'){
            post_id = parseInt(this.getId(id));
        }
        //if we have our post
        if(post_id != 0){
            $asrow = $('#edit-' + post_id);
            var as_json_data = $('meta[name="as_quick_json_data"]').attr('content');
            $.each(JSON.parse(as_json_data), function(i, item) {
                $medicaid = $('#'+item+'_'+post_id).text();
                $asrow.find('#as_quick_'+item).val($medicaid);
            });
        }
    }
    var as_add_meta_form = $('#as_add_meta_box_form');
    $(document).on('click', '#as_add_meta_box_add_new', function(){       
        var as_item_li = as_add_meta_form.find('li');
        var as_item_html = as_item_li.last().html();
            as_add_meta_form.find('li:last-child').after('<li>'+as_item_html+'</li>');
            as_add_meta_form.find('li').last().find('input').val('');
    });
});
function as_remove_meta_box_item(th){
    $this = th;
    jQuery($this).closest('li').remove();
}