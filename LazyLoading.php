<script>
//Is called after clicking the 'Show More' or 'Show_All' button for the 'Basics', 'WD Details', 'PE Details', and 'EC Details' tables. The button only exists if there's more rows to display that aren't already shown. It will append the next 10 rows to the original display. If the is_all parameter is true, all rows will be displayed. If there are less than 10 rows left to display, all remaining rows will be displayed. 
//@param is_all: Checks if the user clicked the 'Show All' button. If so, the function will display all rows in the table.
//@return: None
var row_added = false
function showMore(is_all){
  if(is_all){
    $('#more_loading').css("display", "block");
  }

  if(!row_added){
    wo.tr_count++ //Basics
    wo2.tr_count++ //WD Details
    wo4.tr_count++ //PE Details
    wo5.tr_count++ //EC Details
    row_added = true
  }

  var report_num = $('#WeeklySummaryId tr:last-child td:first-child').html()
  report_num = report_num - 1

  axio.post('init.php',{
    options:{
        FullPath: 'Projects/main',
        RMethod: 'GetMoreWeeks',
    },
    params:{
      report_num : report_num,
      is_all: is_all,
    },
  }).then((response)=>{
    var nextDisplayedReports = response.data['params']['weeks']
    var nextDailyReports = response.data['params']['days']
    var nextWeeklyDetails = response.data['params']['details']
    nextCount = nextDisplayedReports.length
    for(let i = 0; i < nextCount; i++){
      try{
        weekCurrStr = JSON.parse(nextDisplayedReports[i]['json_format']);
      }catch{}
      try{
        weekPrevStr = JSON.parse(nextDisplayedReports[i+1]['json_format']);
      }catch{}
      try{
        weekNextStr = JSON.parse(nextDisplayedReports[i-1]['json_format']);
      }catch{}
      try{
        weekCurr = weekCurrStr['date_0']
      }catch{
        weekCurr = '' 
      }
      try{
        weekNext = weekNextStr['date_0']
      }catch{
        weekNext = '' 
      }
      try{
        weekPrev = weekPrevStr['date_0']
      }catch{
        weekPrev = '' 
      }
      wo.CreateTableOneRow([     
        nextDisplayedReports[i]['report_number'],
        nextDisplayedReports[i]['wd_working_days_thisweek'],
        nextDisplayedReports[i]['wd_nonworking_days_thisweek'],
        nextDisplayedReports[i]['wd_change_order_thisweek'],
        nextDisplayedReports[i]['pe_working_days_thisweek'],
        nextDisplayedReports[i]['ec_working_days_thisweek'],
        YDMtoMDY((nextDisplayedReports[i]['created_at']).substring(0,10)), "<select style='width:70%;display:inline-block;' class ='form-control' id='contractor_sent_select_"+nextDisplayedReports[i]['report_number']+"'><option id='cont_select_yes_"+nextDisplayedReports[i]['report_number']+"' value='1'>Yes</option><option id='cont_select_no_"+nextDisplayedReports[i]['report_number']+"' value='0' selected>No</option> </select> <span id='sent_warn_"+nextDisplayedReports[i]['report_number']+"' class='hidden'  style=' display:inline-block;font-size:12px'><span id='sent_ok_"+nextDisplayedReports[i]['report_number']+"' style='cursor:pointer; font-size:14px;font-weight:bold; ' onclick='assign_sent_to_contractor("+nextDisplayedReports[i]['report_number']+","+ nextDisplayedReports[i]['report_number']+")></span></span>",
        "<div class='btn-group' role='group'> \
          <button onclick='editButtonHandler(this)' id="+editLastProjectId+"                               style='display:none' type='button' value='0' class='btn page-btn'>Edit</button> \
          <button onclick='ConfirmActionSwal_clickbutton(`Are you sure you want to Delete this report?`,`Week-delete`)'          id="+deleteLastProjectId+"                           style='display:none' type='button' class='btn page-btn'>Delete</button> \
          <button value='"+JSON.stringify(nextDisplayedReports[i])+"' onclick='showWeeklyNotes_sweetalert(this.value)' 'type='button' class='btn page-btn'>Notes</button> \
          <button onclick=window.open('<?php echo PROOT."wswd/pdf?"; ?>"+ nextDisplayedReports[i]['report_number'] +"','mywin','width=900,height=900')                                                       type='button' class='btn page-btn'>PDF</button> \ \
          <button onclick='reportHandler(`"+nextDisplayedReports[i]['report_number']+"`, `<?php echo PROOT."wswd?SendAPI"; ?>`, `"+i.toString()+"`)' type='button' name='SendAPI' id='sendPDF_"+nextDisplayedReports[i]['report_number']+"' value='0' class='btn page-btn'>Email PDF</button>\
        </div>",
      ]);

      is_all ? nextWeeklyDetailsObj = nextWeeklyDetails[i][0] : nextWeeklyDetailsObj = nextWeeklyDetails[i]

      wo2.CreateTableOneRow([
        nextDisplayedReports[i]['report_number'],
        nextWeeklyDetailsObj['wd_Working_days_todate'],
        nextWeeklyDetailsObj['wd_nonworking_days_todate'],
        nextWeeklyDetailsObj['wd_change_order_todate'],
        YDMtoMDY(nextWeeklyDetailsObj['wd_EDforC']),
        YDMtoMDY(nextWeeklyDetailsObj['wd_EDforC_Engineer']),
        nextWeeklyDetailsObj['wd_RWDforC'],
        nextWeeklyDetailsObj['wd_Working_days_remaining'],
        JSON.parse(nextDisplayedReports[i]['json_format'])['date_6'] ? YDMtoMDY(JSON.parse(nextDisplayedReports[i]['json_format'])['date_6']) : '',                
        (nextWeeklyDetailsObj['wd_Working_days_ld']),
      ]);

      for(let q=0; q<nextDailyReports.length; q++){
        if(nextDailyReports[q]['isRevisied'] === 'Yes'){
          if (  (weekPrev <= nextDailyReports[q]['date'] && weekCurr<=nextDailyReports[q]['date'] && weekNext>=nextDailyReports[q]['date']) || (weekPrev == '' && weekCurr<=nextDailyReports[q]['date'] && weekNext>=nextDailyReports[q]['date']) || (weekPrev <= nextDailyReports[q]['date'] && weekCurr<=nextDailyReports[q]['date'] && weekNext=='' ) || (weekPrev >= nextDailyReports[q]['date'] && weekCurr == '' && weekNext >= nextDailyReports[q]['date']) ){
            $('[data-toggle="popover"]').popover()  
            $(wo.GetCreatedDynRow()).css('background', '#ff00001c');
            $(wo.GetCreatedDynRow()).attr('data-trigger', 'hover');
            $(wo.GetCreatedDynRow()).attr('data-toggle', 'popover');
            $(wo.GetCreatedDynRow()).attr('title', 'Week Revised')
            $(wo.GetCreatedDynRow()).attr('data-content', 'This week has been revised. Please see the Revised Days table for more details.');
            $(wo.GetCreatedDynRow()).attr('data-original-title', 'title');
            $(wo.GetCreatedDynRow()).attr('data-placement', 'top');
            $(wo2.GetCreatedDynRow()).css('background', '#ff00001c');
            $(wo2.GetCreatedDynRow()).attr('data-trigger', 'hover');
            $(wo2.GetCreatedDynRow()).attr('data-toggle', 'popover');
            $(wo2.GetCreatedDynRow()).attr('title', 'Week Revised')
            $(wo2.GetCreatedDynRow()).attr('data-content', 'This week has been revised. Please see the Revised Days table for more details.');
            $(wo2.GetCreatedDynRow()).attr('data-original-title', 'title');
            $(wo2.GetCreatedDynRow()).attr('data-placement', 'top');
          }
        }
      }

      let pe_ld_days_todate = nextWeeklyDetailsObj['pe_ld_days_todate'];

      if (pe_ld_days_todate > 0) pe_ld_days_todate = pe_ld_days_todate;
      else pe_ld_days_todate = "$0";

      let pe_working_days_todate = nextWeeklyDetailsObj['pe_working_days_todate'];
      let pe_working_days_remaining = nextWeeklyDetailsObj['pe_working_days_remaining'];
      let pe_working_days_ld = 0;

      if (pe_working_days_remaining < 0){
        pe_working_days_ld = pe_working_days_remaining * -1;
        pe_working_days_remaining = 0;
      }

      wo4.CreateTableOneRow([
        nextDisplayedReports[i]['report_number'],
        pe_working_days_todate,
        pe_working_days_remaining,
        pe_working_days_ld,
        pe_ld_days_todate
      ]);

      let ec_ld_days_todate = nextWeeklyDetailsObj['ec_ld_days_todate'];

      if (ec_ld_days_todate > 0) ec_ld_days_todate = ec_ld_days_todate;
      else ec_ld_days_todate = "$0";

      let ec_working_days_todate = nextWeeklyDetailsObj['ec_working_days_todate'];
      let ec_working_days_remaining = nextWeeklyDetailsObj['ec_working_days_remaining'];
      let ec_working_days_ld = 0;

      if (ec_working_days_remaining < 0){
        ec_working_days_ld = ec_working_days_remaining * -1;
        ec_working_days_remaining = 0;
      }

      wo5.CreateTableOneRow([
        nextDisplayedReports[i]['report_number'],
        ec_working_days_todate,
        ec_working_days_remaining,
        ec_working_days_ld,
        ec_ld_days_todate
      ]);
}

axio.post('init.php' ,{
  options:{
      FullPath: 'Projects/main',
      RMethod: 'GetMoreSentToContractor',
  },
  params:{
    nextReports : nextDisplayedReports,
  },
  }).then((response)=>{
    for(let i = 0; i < nextCount; i++){
      var nextReportsSent = JSON.parse(response.data['params'])
    
      if(nextReportsSent[i][0] != null){
        if (nextReportsSent[i][0]['sent_to_contractor'] == 1 ){
          $("#contractor_sent_select_"+nextDisplayedReports[i]['report_number']+" option[value='1']").prop('selected', true);
        }else{
          $("#contractor_sent_select_"+nextDisplayedReports[i]['report_number']+" option[value='']").prop('selected', true);
        }
      }
    }
  })

$('#more_loading').css("display", "none");
$('#show_less_text').css("display", "block");
$('#more_count').text(parseInt($('#WeeklySummaryId tr:last-child td:first-child').html())-1);

if($('#more_count').text() == 0){
  $('#show_more_text').css("display", "none");
  $('#show_all_text').css("display", "none");
}else{
  $('#show_more_text').css("display", "block");
  $('#show_all_text').css("display", "block");
}

checkStatus()

$('select[id^="contractor_sent_select_"]').change(function() {
  check_sent_to_contractor(this)
});

if(pe!= "Yes"){
  $('#WeeklySummaryId tr td:nth-child(5)').hide()
}
if(ec != "Yes"){
  $('#WeeklySummaryId tr td:nth-child(6)').hide()
}
})
}

//Is called after clicking the 'Show Less' button for the 'Basics', 'WD Details', 'PE Details', and 'EC Details' tables. The button only exists after there has been more rows displayed using 'Show More' button. It will hide only the rows that were appended to the original display and return the view in WeeklySummaryC2.php to it's original state.
//@param: None
//@return: None
function showLess(){
  var first_report = $('#WeeklySummaryId tr:first-child td:first-child').html()
  var last_report = $('#WeeklySummaryId tr:last-child td:first-child').html()
  var first_display = first_report - 9 
  var remove_rows = first_display - last_report

  for(let i=0; i < remove_rows; i++){
    wo.RemoveTableOneRow()
    wo2.RemoveTableOneRow()
    wo4.RemoveTableOneRow()
    wo5.RemoveTableOneRow()
  }

  $('#show_less_text').css("display", "none");
  $('#more_count').text(parseInt($('#WeeklySummaryId tr:last-child td:first-child').html())-1);

  if($('#more_count').text() > 0){
    $('#show_more_text').css("display", "block")
    $('#show_all_text').css("display", "block");
  }
}

//Is called after clicking the 'Show More' or 'Show_All' button for the 'WD Change Order' table. The button only exists if there's more rows to display that aren't already shown. It will append the next 10 rows to the original display. If the is_all parameter is true, all rows will be displayed. If there are less than 10 rows left to display, all remaining rows will be displayed.  
//@param is_all: Checks if the user clicked the 'Show All' button. If so, the function will display all rows in the table.
//@return: None
var row_added_co = false
var change_order_id = '<?php echo Controller::create_object_of('WSWD\add\ChangeOrderA', 'change_order')->get_Last_ChangeOrder_Id() ?>'
function showMoreChangeOrder(is_all){

if(is_all){
  $('#co_loading').css("display", "block");
}

change_order_id -= 10

if(!row_added_co){
  wo3.tr_count++ 
  row_added_co = true
}

  axio.post('init.php',{
    options:{
        FullPath: 'Projects/main',
        RMethod: 'GetMoreChangeOrders',
    },
    params:{
      change_order_id : change_order_id,
      is_all: is_all,
    },
  }).then((response)=>{
    var nextChangeOrders = response.data['params'][0]
    var remainingCount = response.data['params'][1]
    var nextCount = nextChangeOrders.length

    for(let i = 0; i < nextCount ; i++){
      changeCount--
      if(nextChangeOrders[i]['change_order_supplement']){
        supp = "-" + nextChangeOrders[i]['change_order_supplement']
      }else{
        supp = ""
      }

      wo3.CreateTableOneRow([
        nextChangeOrders[i]['change_order_number'] + supp,
        nextChangeOrders[i]['report_number'],
        nextChangeOrders[i]['Change_order_days_number'],
      ]);
    }

    $('#change_count').text(changeCount)
    $('#show_less_text_co').css("display", "block");

    if($('#change_count').text() == 0){
      $('#show_more_text_co').css("display", "none");
      $('#show_all_co').css("display", "none");
    }else{
      $('#show_more_text_co').css("display", "block");
      $('#show_all_co').css("display", "block");
    }
  })
}

//Is called after clicking the 'Show Less' button for the 'WD Change Order' table. The button only exists after there has been more rows displayed using 'Show More' button. It will hide only the rows that were appended to the original display and return the view in WeeklySummaryC2.php to it's original state.
//@param: None
//@return: None
function showLessChangeOrder(){
  showCount = wo3.tr_count - 10

  for(let i=0; i < showCount; i++){
    changeCount++
    wo3.RemoveTableOneRow()
  }
  var change_num_top = changeCount

  if(change_num_top > 10){
    $("#show_all_co").css("display", "block");
  }

  $('#show_less_text_co').css("display", "none");
  $('#change_count').text(changeCount);

  if(changeCount > 0){
    $('#show_more_text_co').css("display", "block");
  }
  change_order_id = '<?php echo Controller::create_object_of('WSWD\add\ChangeOrderA', 'change_order')->get_Last_ChangeOrder_Id() ?>'

  
}

/////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////
//CHANGE FUNCTION TO SHOW TEN WEEKS AT A TIME? CURRENT IS TEN DAYS AT A TIME///////////////////////////////////////////////
/////////////////////////////////////////////////////////////
//Is called after clicking the 'Show More' or 'Show_All' button for the 'WD Daily Details' table. The button only exists if there's more rows to display that aren't already shown. It will append the next 10 rows to the original display. If the is_all parameter is true, all rows will be displayed. If there are less than 10 rows left to display, all remaining rows will be displayed.  
//@param is_all: Checks if the user clicked the 'Show All' button. If so, the function will display all rows in the table.
//@return: None
function showMoreDailyDetails(is_all){

  if(is_all){
    $('#daily_loading').css("display", "block");
  }

  daily_id = $('#daily_id').text()-1

  if(!row_added_daily){
    dailyObj.tr_count++ 
    row_added_daily = true
  }

  axio.post('init.php',{
      options:{
          FullPath: 'Projects/main',
          RMethod: 'GetMoreDays',
      },
      params:{
        daily_id : daily_id,
        is_all: is_all,
      },

    }).then((response)=>{

      nextDailyDetails = response.data['params'][0]
      remainingCountDaily = response.data['params'][1]
      holidays = response.data['params'][2]

      nextCount = nextDailyDetails.length;

      for(let i = 0; i < nextCount ; i++){
        dailyCount--

        if (nextDailyDetails[i]['isRevisied'] === 'Yes'){
          totalRevisedDays++;
        }
        var p,e,dayStatus,work_on_cop;
        nextDailyDetails[i]['pe_work_status'] ? p=0 : p=1;
        nextDailyDetails[i]['ec_work_status'] ? e=0 : e=1;
        nextDailyDetails[i]['work_status'] == "Work"? dayStatus="Work Day" : dayStatus="Non Working Day";

        if(nextDailyDetails[i]['work_status'] == "Work") dayStatus ="Work Day";
        else if(nextDailyDetails[i]['work_status'] == "NoWork") dayStatus = "Non Working Day"
        else dayStatus = "  ---  ";
          
        if(nextDailyDetails[i]['work_status'] == "LD") dayStatus="Liquidated Damages";
        if(nextDailyDetails[i]['work_status'] == "CA") dayStatus="LD + Contract Accepted";

        if(nextDailyDetails[i]['work_on_cop'] == "Work") work_on_cop="Yes";
        else if(nextDailyDetails[i]['work_on_cop'] == "NoWork") work_on_cop="No";
        else work_on_cop="N/A";

        dailyObj.CreateTableOneRow([
          nextDailyDetails[i]['report_number'],
          nextDailyDetails[i]['day'],
          YDMtoMDY(nextDailyDetails[i]['date']),
          dayStatus,
          nextDailyDetails[i]['controlling_op'],
          work_on_cop,
          nextDailyDetails[i]['weather'],
          nextDailyDetails[i]['other'],
          YDMtoMDY((nextDailyDetails[i]['created_at']).substring(0,10))
        ]);

        if(nextDailyDetails[i]['isRevisied'] === 'Yes'){
          $('[data-toggle="popover"]').popover() 
          $(dailyObj.GetCreatedDynRow()).attr('data-trigger', 'hover');
          $(dailyObj.GetCreatedDynRow()).attr('data-toggle', 'popover');
          $(dailyObj.GetCreatedDynRow()).attr('title', 'Day Revised')
          $(dailyObj.GetCreatedDynRow()).attr('data-content', 'This day has been revised. Please see the Revised Days table for more details.');
          $(dailyObj.GetCreatedDynRow()).attr('data-original-title', 'title');
          $(dailyObj.GetCreatedDynRow()).attr('data-placement', 'top');
          $(dailyObj.GetCreatedDynRow()).css("background", '#ff00001c');
          
        }
      

        if((nextDailyDetails[i]['day'] === 'Saturday'  ||  nextDailyDetails[i]['day'] === 'Sunday') && (nextDailyDetails[i]['work_status'] != 'Work') ){
          $('[data-toggle="popover"]').popover()  
          $(dailyObj.GetCreatedDynRow()).attr('data-trigger', 'hover');
          $(dailyObj.GetCreatedDynRow()).attr('data-toggle', 'popover');
          $(dailyObj.GetCreatedDynRow()).attr('title', 'Not A Valid Working Day (Weekend)')
          $(dailyObj.GetCreatedDynRow()).attr('data-content', 'Weekends do not count toward working days in a 5 day calendar.');
          $(dailyObj.GetCreatedDynRow()).attr('data-original-title', 'title');
          $(dailyObj.GetCreatedDynRow()).attr('data-placement', 'top');
          $(dailyObj.GetCreatedDynRow()).css("background", '#a2a2a2');
        }
        <?php if(Controller::create_object_of('WSWD\projects\projectA', 'Project')->GetCurrentProject()[0]['calendar_type'] == 5) :?>
          if( holidays.includes(i) ){
            $('[data-toggle="popover"]').popover() 
            $(dailyObj.GetCreatedDynRow()).attr('data-trigger', 'hover');
            $(dailyObj.GetCreatedDynRow()).attr('data-toggle', 'popover');
            $(dailyObj.GetCreatedDynRow()).attr('title', 'Not A Valid Working Day (Holiday)')
            $(dailyObj.GetCreatedDynRow()).attr('data-content', 'Holidays do not count toward working days in a 5 day calendar.');
            $(dailyObj.GetCreatedDynRow()).attr('data-original-title', 'title');
            $(dailyObj.GetCreatedDynRow()).attr('data-placement', 'top');
            $(dailyObj.GetCreatedDynRow()).css("background", '#f8ffc2');
            }
        <?php endif; ?>
      }
      $('#daily_count').text(dailyCount)
      $('#show_less_text_daily').css("display", "block");

      if($('#daily_count').text() == 0){
        $('#show_more_text_daily').css("display", "none");
        $('#show_all_daily').css("display", "none");
      }else{
        $('#show_more_text_daily').css("display", "block");
        $('#show_all_daily').css("display", "block");
      }

      $('#daily_id').text(nextDailyDetails[nextDailyDetails.length-1]['daily_id']);

      $('#daily_loading').css("display", "none");
    })
}

//Is called after clicking the 'Show Less' button for the 'WD Daily Details' table. The button only exists after there has been more rows displayed using 'Show More' button. It will hide only the rows that were appended to the original display and return the view in WeeklySummaryC2.php to it's original state.
//@param: None
//@return: None
function showLessDailyDetails(){
  showCount = dailyObj.tr_count - 10
  var daily_num_top = $('#DailyDetailsId tr:first-child td:first-child').html()

  if(daily_num_top > 10){
    $("#show_all_daily").css("display", "block");
  }

  for(let i=0; i < showCount; i++){
    dailyCount++
    dailyObj.RemoveTableOneRow()
  }

  $('#show_less_text_daily').css("display", "none");
  $('#daily_count').text(dailyCount);

  if(dailyCount > 0){
    $('#show_more_text_daily').css("display", "block");
  }

  $('#daily_id').text(daily[daily.length-1]['daily_id']);
}


//Is called after clicking the 'Show More' or 'Show_All' button for the 'Revised Days' table. The button only exists if there's more rows to display that aren't already shown. It will append the next 10 rows to the original display. If the is_all parameter is true, all rows will be displayed. If there are less than 10 rows left to display, all remaining rows will be displayed. 
//@param is_all: Checks if the user clicked the 'Show All' button. If so, the function will display all rows in the table instead of the default of 10 displayed rows.
//@return: None
var row_added_revised = false
function showMoreRevised(is_all){

  if(is_all){
    $('#revised_loading').css("display", "block");
  }

  revised_id = $('#revised_id').text()

  if(!row_added_revised){
    RevisiedObj.tr_count++ 
    row_added_revised = true
  }

  axio.post('init.php',{
      options:{
          FullPath: 'Projects/main',
          RMethod: 'GetMoreRevised',
      },
      params:{
        revised_id : revised_id,
        is_all: is_all,
      },
    }).then((response)=>{
      nextRevisedDays = response.data['params'][0]
      for(let i = 0; i < nextRevisedDays.length ; i++){
        revisedCount--
        var p,e,dayStatus,work_on_cop;
        nextRevisedDays[i]['pe_work_status'] ? p=0 : p=1;
        nextRevisedDays[i]['ec_work_status'] ? e=0 : e=1;

        nextRevisedDays[i]['work_status'] == "Work"? dayStatus="Work Day" : dayStatus="Non Working Day";
        if(nextRevisedDays[i]['work_status'] == "LD") dayStatus="Liquidated Damages";
        if(nextRevisedDays[i]['work_status'] == "CA") dayStatus="Contract Accepted";

        if(nextRevisedDays[i]['work_on_cop'] == "Work") work_on_cop="Yes";
        else if(nextRevisedDays[i]['work_on_cop'] == "NoWork") work_on_cop="No";
        else work_on_cop="N/A";

        RevisiedObj.CreateTableOneRow([
          nextRevisedDays[i]['report_number'],
          nextRevisedDays[i]['day'],
          YDMtoMDY(nextRevisedDays[i]['date']),
          dayStatus,
          nextRevisedDays[i]['controlling_op'],
          work_on_cop,
          nextRevisedDays[i]['Justification'],
          nextRevisedDays[i]['weather'],
          nextRevisedDays[i]['other'],
          p,
          e,
          YDMtoMDY((nextRevisedDays[i]['created_at']).substring(0,10)),
        ]);

        if(nextRevisedDays[i]['isRevisied'] === 'Yes'){
          $(RevisiedObj.GetCreatedDynRow()).css("background", '#ff00001c');
          $(RevisiedObj.GetCreatedDynRow()).attr('title', 'This day has been revised. The new data for this day should be in here.');
        }else{
          $(RevisiedObj.GetCreatedDynRow()).attr('title', 'This day is up to date.');
        }
      }

      $('#revised_count').text(revisedCount)
      $('#show_less_text_revised').css("display", "block");

      if($('#revised_count').text() <= 0){
        $('#show_more_text_revised').css("display", "none");
        $('#show_all_revised').css("display", "none");
      }else{
        $('#show_more_text_revised').css("display", "block");
        $('#show_all_revised').css("display", "block");
      }

      $('#revised_id').text(nextRevisedDays[nextRevisedDays.length-1]['id']);
      $('#revised_loading').css("display", "none");
    })
}

//Is called after clicking the 'Show Less' button for the 'Revised Days' table. The button only exists after there has been more rows displayed using 'Show More' button. It will hide only the rows that were appended to the original display and return the view in WeeklySummaryC2.php to it's original state.
//@param: None
//@return: None
function showLessRevised(){
  showCount = RevisiedObj.tr_count - 10

  for(let i=0; i < showCount; i++){
    revisedCount++
    RevisiedObj.RemoveTableOneRow()
  }

  $('#show_less_text_revised').css("display", "none");
  $('#revised_count').text(revisedCount);

  if(revisedCount > 0){
    $('#show_more_text_revised').css("display", "block");
  }
  if(revisedCount > 10){
  $('#show_all_revised').css("display", "block");
  }

  $('#revised_id').text(RevisiedDays[RevisiedDays.length-1]['id']);
}

</script>


