{{ Html::script('admin/plugins/nestable/jquery.nestable.js') }}
{{ Html::script('admin/js/pages/ui/modals.js') }}
<script>
    var url = "{{ url('admin/update/menuItem')  }}";
    var id = "{{ $item->id }}";
    var _token = "{{ csrf_token() }}";
    $('.dd').nestable({
        maxDepth:2
    });
    $('.dd').on('change', function () {
        var $this = $(this);
        var serializedData = window.JSON.stringify($($this).nestable('serialize'));
        $this.parents('div.body').find('textarea').val(serializedData);
        $.post(url , {id:id,_token:_token,data:serializedData} , function(result){
            swal("Done!", "You have been update Items position!", "success");
        });
    });
    $('#defaultModal').on('shown.bs.modal', function(evt) {
        clearFields();
        if($(evt.relatedTarget).data('url') && $(evt.relatedTarget).data('id')){
            var id = $(evt.relatedTarget).data('id');
            $.get("{{ url('admin/getItemInfo/') }}/"+id , function(result){
                var json = JSON.parse(result);
                $('#itemName').val(json.name);
                $('#itemIcon').val(json.icon);
                $('#itemLink').val(json.link);
                $("#type").val(json.type == undefined ? 'self' : json.type).change();
                $('#menu_id').val(json.id);
                $('#actionBtn').attr('onclick' , 'UpdateItem();return false;');
                $('#actionBtn').html('Save Item');
            });
        }else{
            clearFields();
            $('#actionBtn').removeAttr('onclick');
            $('#menu_id').val({{$item->id}});
            $('#actionBtn').html('Add Item');
        }
    });
    function clearFields(){
        $('#itemName , #itemLink , #itemIcon').val(' ');
    }
    function UpdateItem(){
        $(this).preventDefault;
        var id = $('#menu_id').val();
        var name = $('#itemName').val();
        var icon = $('#itemIcon').val();
        var link = $('#itemLink').val();
        var type = $('#type').val();
        var data = {
            id:id,
            name:name,
            icon:icon,
            link:link,
            type:type,
            _token:"{{ csrf_token() }}"
        };
        $.post("{{ url('admin/updateOneMenuItem/') }}/",data, function(result){
            swal("Done!", "You have been update this item!", "success");
        });
    }
</script>