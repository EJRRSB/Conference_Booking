
// $(document).ready(function(){  

    const ul = document.querySelector("#ul_pariticipants"), 
    tagNumb = document.querySelector(".details span");
    
    let maxTags = 100,
    tags = tags_participants;
     
    
    countTags();
    createTag();
    
    function countTags(){
        $('#participants_input').focus();
        tagNumb.innerText = maxTags - tags_participants.length;
    }
    
    function createTag(){ 
        ul.querySelectorAll("li").forEach(li => li.remove()); 
        tags_participants.slice().reverse().forEach(tag =>{ 
            let liTag = `<li class="lis" id="${searchable_id[searchable.indexOf(tag)] ? searchable_id[searchable.indexOf(tag)] : '0'}">${tag}<i class="uit uit-multiply" id="remove"></i></li>`; 
            $('#ul_pariticipants').prepend(liTag);
        });
        countTags();
    }
    
    // function remove(element, tag){
    //     let index  = tags.indexOf(tag);
    //     tags = [...tags.slice(0, index), ...tags.slice(index + 1)];
    //     element.parentElement.remove();
    //     countTags();
    // }
 
    
    // function addTag(e){
    //     if(e.key == "Enter"){ 
    //         let tag = e.target.value.replace(/\s+/g, ' ');
    //         if(tag.length > 1 && !tags.includes(tag)){
    //             if(tags.length < 100){
    //                 tag.split(',').forEach(tag => {
    //                     tags.push(tag);
    //                     createTag(); 
    //                 });
    //             }else{
    //                 alert('Tags limit is ' + 100)
    //             }
    //         }
    //         e.target.value = "";
    //     }
    // }
     
    
    $(document).on('click','#remove', function() {    
        let index  = tags_participants.indexOf(searchable[searchable_id.indexOf( parseInt($(this).parent().attr('id'), 10))] ? searchable[searchable_id.indexOf( parseInt($(this).parent().attr('id'), 10))] : $(this).parent().text());  
        tags_participants = [...tags_participants.slice(0, index), ...tags_participants.slice(index + 1)]; 
        $( "#" + $(this).parent().attr('id') ).remove();
        countTags();
        createTag();
    }) 
    
    $('#add_booking').on('click', function() { 
        tags_participants.length = 0;
        ul.querySelectorAll("li").forEach(li => li.remove()); 
        tags_participants.push($('#auth_email').text());
        countTags();
        createTag();
    })
    
    $("#BookingdataTable").on('click', '#edit', function () {  
        tags_participants.length = 0;
        ul.querySelectorAll("li").forEach(li => li.remove());
        countTags();
        // setTimeout(
        //     function() 
        //     {
        //         createTag();
        //     }, 800);
    })
 
    // setInterval(function () {
    //     ul.querySelectorAll("li").forEach(li => li.remove());
    //     countTags();
    //     createTag();
    // }, 700);   


    $('#cancelbookingmodal').on('click', function(){ 
        tags_participants.length = 0;
        $('#participants_input').val('');
    });


    $('#participants_input').on('keyup', function(e){ 
        // addTag(e)
        getSuggestions(this.value)
    });

    
    $('#participants_input').on('keydown', function(e){ 
        if (e.keyCode == 13) { 
            
            let tag = $(this).val().replace(/\s+/g, ' ');
            if(!isEmail($(this).val().replace(/\s+/g, ' '))){
                noti('Input must be a valid email', 'Error!', 'error');
                return;
            } 
            
            if(tag.length > 1 && !tags_participants.includes(tag)){
                if(tags_participants.length < 100){
                    tag.split(',').forEach(tag => {
                        tags_participants.push(tag);
                        createTag(); 
                    });
                }else{
                    alert('Tags limit is ' + 100)
                }
            } 
            $('#participants_input').val('');
            $('#participants_input').focus(); 
            getSuggestions('');
        }
        
    });

    function isEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }
    
     













    let searchable = [ ];
    let searchable_id = [ ];
      
      const searchInput = document.getElementById('search');
      const searchWrapper = document.querySelector('.wrapper');
      const resultsWrapper = document.querySelector('.results'); 

      function renderResults(results) { 
        if (!results.length) {
        //   return searchWrapper.classList.remove('show');
        }
      
        const content = results
          .map((item) => {
            return `<li id="${searchable_id[searchable.indexOf(item)]}">${item}</li>`;
          })
          .join('');
      
        // searchWrapper.classList.add('show');
        resultsWrapper.innerHTML = `<ul id="result_ul">${content}</ul>`;
      }
   
      $(document).on('click','#result_ul li', function(){ 
        
        let tag = $(this).text().replace(/\s+/g, ' ');
        if(tag.length > 1 && !tags_participants.includes(tag)){
            if(tags_participants.length < 100){
                tag.split(',').forEach(tag => {
                    tags_participants.push(tag);
                    createTag(); 
                });
            }else{
                alert('Tags limit is ' + 100)
            }
        }
        $('#participants_input').val('');
        $('#participants_input').focus(); 
        getSuggestions('');
    });
 

    


    function getSuggestions(value){
    
        let results = [];
        let input = value;
        if (input.length) {
            results = searchable.filter((item) => { 
            return item.toLowerCase().includes(input.toLowerCase());
            });
        }  
        renderResults(results);
    }


    getParticipants('');
    function getParticipants(search_value){
 

        $.ajax({
            url: 'bookings/getParticipants',
            type: "GET", 
            cache: false,
            data: {
                search_value: search_value
            },
            beforeSend: function () {
                // $('#modal_process').show();
            },
            complete: function () {
                // $('#modal_process').hide();
            },
            success: function (dataResult) {
                var dataResult = JSON.parse(dataResult);
                if (dataResult.statusCode == 200) {   
                    for (var i = 0; i < dataResult.message.length; i++) { 
                        searchable.push(dataResult.message[i].email);
                        searchable_id.push(dataResult.message[i].id);
                        
                    }  
                //    console.log( searchable.indexOf('elton.romero@rsb-consulting.com'));
                //    console.log( searchable );
                }else if (dataResult.statusCode == 201) {  
                    noti(dataResult.message, 'Error!', 'error'); 
                }
            },
            error: function (e) {
                noti('An error occured, please try again', 'Error!', 'error');
            }
        });

        
    }

    

    

    function noti(message, title, icons) {
        Swal.fire({
            icon: icons,
            title: title, 
            text: message 
        })

    }
// });