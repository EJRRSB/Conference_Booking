$(document).ready(function(){ 
   
    const ul = document.querySelector("#ul_booking_agenda"), 
    tagNumb = document.querySelector(".details span");
    
    let maxTags = 100,
    tags = tags_agenda;  

    countAgendaTags();
    createAgendaTag();
    
    function countAgendaTags(){
        $('#it_requirements_input').focus();
        tagNumb.innerText = maxTags - tags_agenda.length;
    }
    
    function createAgendaTag(){   
        ul.querySelectorAll("li").forEach(li => li.remove());   
        tags_agenda.slice().reverse().forEach(tag =>{   
            let liTag = `<li class="agenda_list" id="${tag}">${tag}<i class="uit uit-multiply" style="float:right;" id="remove_agenda"></i></li>`; 
            $('#ul_booking_agenda').prepend(liTag);
        });
        countAgendaTags();
    } 
     
    
    $(document).on('click','#remove_agenda', function() {    
        let index  = tags_agenda.indexOf($(this).parent().text());  
        tags_agenda = [...tags_agenda.slice(0, index), ...tags_agenda.slice(index + 1)]; 
        $( "#" + $(this).parent().text()).remove();
        countAgendaTags();
        createAgendaTag();
    }) 
    
    $('#add_booking').on('click', function() { 
        tags_agenda.length = 0;
        ul.querySelectorAll("li").forEach(li => li.remove());
        countAgendaTags();
        createAgendaTag();
    })
    
    $("#BookingdataTable").on('click', '#edit', function () {  
        tags_agenda.length = 0;
        ul.querySelectorAll("li").forEach(li => li.remove());
        countAgendaTags();
        setTimeout(
            function() 
            {
                createAgendaTag();
            }, 1000);
    })

    $('#cancelbookingmodal').on('click', function(){ 
        tags_agenda.length = 0;
    });
 
    
    $('#booking_agenda_input').on('keydown', function(e){  
        if (e.keyCode == 13) { 
            
            let tag = $(this).val().replace(/\s+/g, ' ').replace(/,/g, ' ');  
            if(tag.length > 1 && !tags_agenda.includes(tag)){
                if(tags_agenda.length < 100){
                    tag.split(',').forEach(tag => {
                        tags_agenda.push(tag);
                        createAgendaTag(); 
                    });
                }else{
                    alert('Tags limit is ' + 100)
                }
            } 
            $('#booking_agenda_input').val('');
            $('#booking_agenda_input').focus();  
        }
        
    });
  
     
   
});