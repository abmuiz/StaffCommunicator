function update()
{

    
    var row = $(".talktext:first").attr('id');
    //alert(row);
    if(row==undefined)
        row=0;
    //alert(row);
    $.ajax({
        url:"ChatAjax.php",
        type:"POST",
        data: {
            chat_header_id: $("#chat_header_id").val(),
            last_row:row
        },

       

        success : function(result){

            if (result!="") {
                var obj = JSON.parse(result);            
                var type_name='';
                
                for(i in obj)
                {
                    
                    var div1=$('<div />').attr({'class':'talk-bubble tri-right left-top'});
                    var div2=$('<div />').attr({'class':'talktext','id':obj[i].chat_id});
                    var span=$('<span />').html(obj[i].name+" &nbsp "+obj[i].chat_date).attr({'class':'bla'});
                    var p = $('<p />').html(obj[i].chat);
                    $('#total_chats').text(obj[i].total_chats);
                    div2.append(span,p);
                    div1.append(div2);
                    $('#screen').prepend(div1);
                   
                }                
            }            
        }       
    });
 
    setTimeout('update()', 1000);
}
 
$(document).ready(
 
function() 
{
    
    update();
 
    $("#button").click(    
        function() 
        {         
            
            $('#chat').html( tinymce.get('chat').getContent() );
            var row = $("#mytable").closest('table').find('tr:first').attr('id');
            if(row==undefined)
                row=0;

            $.post("ChatAjax.php", 
            { 
                chat: $("#chat").val(),
                chat_header_id: $("#chat_header_id").val(),
                last_row:row
            },
    
        function(data)
        { 
            tinymce.get("chat").setContent('');
            tinymce.get("chat").focus();
        }
        );
      }
     );
});
 