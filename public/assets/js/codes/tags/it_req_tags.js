$(document).ready(function(){ 
   
   

    const ul = document.querySelector("#ul_it_requirements"), 
    tagNumb = document.querySelector(".details span");
    
    let maxTags = 100,
    tags = []; 
    tags = tags_it_req; 
    
    countITReqTags();
    createITReqTag();
    
    function countITReqTags(){
        $('#it_requirements_input').focus();
        tagNumb.innerText = maxTags - tags_it_req.length;
    }
    
    function createITReqTag(){    
        ul.querySelectorAll("li").forEach(li => li.remove()); 
        tags_it_req.slice().reverse().forEach(tag =>{
            let liTag = `<li class="it_list" id="${tag}">${tag}<i class="uit uit-multiply" id="remove_it_req"></i></li>`; 
            $('#ul_it_requirements').prepend(liTag);
        }); 
        countITReqTags();
    } 
     
    
    $(document).on('click','#remove_it_req', function() {    
        let index  = tags_it_req.indexOf($(this).parent().text());  
        tags_it_req = [...tags_it_req.slice(0, index), ...tags_it_req.slice(index + 1)]; 
        $( "#" + $(this).parent().text()).remove();
        countITReqTags();
        createITReqTag();
    }) 
    
    $('#add_booking').on('click', function() {  
        tags_it_req.length = 0;
        ul.querySelectorAll("li").forEach(li => li.remove());
        countITReqTags();
        createITReqTag();
    })

    $("#BookingdataTable").on('click', '#edit', function () {  
        tags_it_req.length = 0;
        ul.querySelectorAll("li").forEach(li => li.remove());
        countITReqTags(); 
        
        setTimeout(
            function() 
            {
                createITReqTag(); 
            }, 1000);
    });

    $('#cancelbookingmodal').on('click', function(){ 
        tags_it_req.length = 0;
    });

 
    
    $('#it_requirements_input').on('keydown', function(e){  
        if (e.keyCode == 13) { 
            
            let tag = $(this).val().replace(/\s+/g, ' ').replace(/,/g, ' ');   
            
            if(tag.length > 1 && !tags_it_req.includes(tag)){
                if(tags_it_req.length < 100){
                    tag.split(',').forEach(tag => {
                        tags_it_req.push(tag);
                        createITReqTag();  
                    });
                }else{
                    alert('Tags limit is ' + 100)
                }
            } 
            $('#it_requirements_input').val('');
            $('#it_requirements_input').focus();  
        }
        
    });
 
     
   
});