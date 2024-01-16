$(document).ready(function(){
    row_class = ["btn btn-primary row_btn", "btn btn-secondary row_btn"]
    more_class = ["btn btn-primary more_btn", "btn btn-secondary more_btn"]
    less_class = ["btn btn-primary less_btn", "btn btn-secondary less_btn"]

    toggle_class(".more_btn", false, more_class)
    toggle_class(".less_btn", false, less_class)
    $(".more_input").attr("disabled", true)
    $(".less_input").attr("disabled", true)

})

function toggle_class(txt, val, set){
    if(val){
        $(txt).attr("class", set[0])
        $(txt).css("cursor", "pointer");
        $(txt).attr("disabled", false)
        return true
    }
    $(txt).attr("class", set[1])
    $(txt).css("cursor", "default");
    $(txt).attr("disabled", true)
}


function get_rows(){
    $.ajax({
        url: './classes/Bridge.php',
        method: 'POST',
        data: { action: 'get_initial' },
        success: function(response){
            console.log(response)
            data = JSON.parse(response)
            display_initial(data)
        },
        error: function(xhr, status, error){
            console.error('Error:', error);
        }
    })
}


function display_initial(data){
    string = $(".data").html()
    ids = ["name", "calories", "category", "ingredients", "prep_time", "prep_cost"]
    row_count = get_count_ui()
    for(i=0; i<data.length; i++){
        row_count++
        string = string + "<div class='row data_row' id='"+row_count+"'>"
        for(j=1; j<data[i].length-1; j++){
            string = string + "<div class='col-md-2'><span id='"+ids[j-1]+"'name='"+ids[j-1]+"'>"+data[i][j]+"</span></div>"
        }
        string = string + "</div>"
    }

    $(".data").html(string)
    toggle_class(".row_btn", false, row_class)
    toggle_class(".more_btn", true, more_class)
    toggle_class(".less_btn", true, less_class)

    $(".more_input").val("1")
    $(".less_input").val("1")
    $(".more_input").attr("disabled", false)
    $(".less_input").attr("disabled", false)
    $(".more_message").html("")
    $(".less_message").html("")
}


function get_more(){
    $.ajax({
        url: './classes/Bridge.php',
        method: 'POST',
        data: { action: 'get_more', variableName: $('.more_input').val() },
        success: function(response){
            data = JSON.parse(response)
            display_more(data)

            if(get_count_ui() == 35){
                toggle_class(".more_btn", false, more_class)
                $(".more_message").html("All rows displayed")
            }else{
                $(".more_message").html($('.more_input').val() + " rows displayed")
                $(".less_message").html("")
            }

        },
        error: function(xhr, status, error){
            console.error('Error:', error);
        }
    })   
}


function display_more(data){
    string = $(".data").html()
    ids = ["name", "calories", "category", "ingredients", "prep_time", "prep_cost"]
    row_count = get_count_ui()
    for(i=0; i<data.length; i++){
        row_count++
        string = string + "<div class='row data_row' id='"+row_count+"'>"
        for(j=1; j<data[i].length-1; j++){
            string = string + "<div class='col-md-2'><span id='"+ids[j-1]+"'name='"+ids[j-1]+"'>"+data[i][j]+"</span></div>"
        }
        string = string + "</div>"
    }
    $(".data").html(string)
}


function show_less(){
    $(".more_message").html("")
    $(".less_message").html("Removed " + $(".less_input").val() + " rows")
    if($(".less_input").val()){
        remove = $(".less_input").val()
        count = get_count_ui()
        total_count = count
        new_count = (count-remove)
        for(i=get_count_ui(); i>total_count-remove; i--){
            remove_id = "#" + i
            $(remove_id).remove();
        }
    }
    set_count(new_count)

    if(get_count_ui() < 1){
        toggle_class(".row_btn", true, row_class)
        toggle_class(".more_btn", false, more_class)
        toggle_class(".less_btn", false, less_class)
        $(".more_input").val("1")
        $(".less_input").val("1")
        $(".more_input").attr("disabled", true)
        $(".less_input").attr("disabled", true)
        $(".less_message").html("Removed all rows")
    }else{
        $(".less_message").html("Removed " + $(".less_input").val() + " rows")
    }

    if(get_count_ui() > 0 && get_count_ui() < 35){
        toggle_class(".more_btn", true, more_class)
    }
}


function get_count_ui(){
    return $(".data").children().length-1
}


function set_count(count){
    $.ajax({
        url: './classes/Bridge.php',
        method: 'POST',
        data: { action: 'set_count', variableName: count },
        success: function(response){
            console.log("set count success: " + response)
            get_count()
        },
        error: function(xhr, status, error){
            console.error('Error:', error);
        }
    })   
}


function get_count(){
    $.ajax({
        url: './classes/Bridge.php',
        method: 'POST',
        data: { action: 'get_count'},
        success: function(response){
            console.log("get count success: " + response)
        },
        error: function(xhr, status, error){
            console.error('Error:', error);
        }
    })   
}