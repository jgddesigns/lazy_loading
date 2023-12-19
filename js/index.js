$(document).ready(function(){
    $(".more_btn").hide()
    $(".less_btn").hide()
    $(".less").hide()
})

function get_rows(){
    $.ajax({
        url: './classes/Connect.php',
        method: 'POST',
        data: { action: 'get_initial' },
        success: function(response){
            data = JSON.parse(response)
            display_initial(data)
        },
        error: function(xhr, status, error){
            console.error('Error:', error);
        }
    })
}


function get_count(){
    return $(".data").children().length-1
}


function display_initial(data){
    string = $(".data").html()
    ids = ["name", "calories", "category", "ingredients", "prep_time", "prep_cost"]
    row_count = get_count()
    for(i=0; i<data.length; i++){
        row_count++
        string = string + " <div class='row data_row' id='"+row_count+"'>"
        for(j=1; j<data[i].length-1; j++){
            string = string + "<div class='col-md-2'><span id='"+ids[j-1]+"'name='"+ids[j-1]+"'>"+data[i][j]+"</span></div>"
        }
        string = string + "</div>"
        
    }
    $(".data").html(string)
    $(".more_btn").show()
    $(".less_btn").show()
    $(".less").show()
}


function get_more(){
    $.ajax({
        url: './classes/Connect.php',
        method: 'POST',
        data: { action: 'get_more' },
        success: function(response){
            data = JSON.parse(response)
            display_more(data)
        },
        error: function(xhr, status, error){
            console.error('Error:', error);
        }
    })   
}


function display_more(data){
    string = $(".data").html()
    ids = ["name", "calories", "category", "ingredients", "prep_time", "prep_cost"]
    row_count = get_count()
    for(i=0; i<data.length; i++){
        row_count++
        string = string + " <div class='row data_row' id='"+row_count+"'>"
        for(j=1; j<data[i].length-1; j++){
            string = string + "<div class='col-md-2'><span id='"+ids[j-1]+"'name='"+ids[j-1]+"'>"+data[i][j]+"</span></div>"
        }
        string = string + "</div>"
    
    }
    $(".data").html(string)
}


function show_less(){
    if($(".less").val()){
        remove = $(".less").val()
        count = get_count()
        total_count = count
        for(i=get_count(); i>total_count-remove; i--){
            remove_id = "#" + i
            $(remove_id).remove();
        }
    }
    set_count(count)
}


function set_count(count){
    $.ajax({
        url: './classes/Connect.php',
        method: 'POST',
        data: { action: 'set_count', variableName: count },
        success: function(response){
            console.log("set count success: " + response)
        },
        error: function(xhr, status, error){
            console.error('Error:', error);
        }
    })   
}