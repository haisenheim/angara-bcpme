 <script>
    var _url = "{{ route('util.produits.list') }}"

        var combo;
        var items = [];
        var item = null;
        $(document).ready(function($){
            var cb = $('#cc').combotree('tree')
            console.log(cb)
            /*cb.onSelect(function(){
                console.log('changement ...')
                console.log(cb.getValues())
            })*/

            $('#btn-test').click(function(){
                        var val = $('#cc').combotree('tree');
                        console.log(val.tree._selectedItems);
                    })

         $.ajax({
             'url':"{{ route('util.produits.list') }}",
             'type':'get',
             'dataType':'json',
             success:function(arr){
                 console.log(arr);
                combo = $('#ct').comboTree({
                         source : arr,
                         collapse: true,
                         isMultiple:true,
                         editable:true,
                     });
                     $('#btn-test').click(function(){
                        //var val = $combo;
                        console.log(combo);
                    })
                     $('.ct-arrow-btn').html('<i class="pli-arrow-down"></i>');
                     combo.onChange(function(){
                         var elts = combo._selectedItems;
                         items = elts;
                         //$('#category_id').val(id);
                         //var ids = combo.getSelectedItemsIds();
                     });
             }
         });
     });

     function build(){
        $('#produits').html('')
        if(item!=null){
            var li = `<li class="list-group-item">${item.name}</li>`
            $('#produits').append(li)
        }
        items.forEach(element => {
            var li = `<li class="list-group-item">${item.name}</li>`
            $('#produits').append(li)
        });
     }



 </script>
