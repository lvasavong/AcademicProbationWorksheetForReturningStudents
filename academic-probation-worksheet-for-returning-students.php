<?php
//*******************************************************************************************************
//	academic-probation-worksheet-for-returning-students.php 
//
//	Author: Linda Vasavong
//	Date Created: ????
//	Date Modified: Linda Vasavong
//*******************************************************************************************************


require_once('form_processors/m_academic-probation-worksheet-for-returning-students.php');

// Setup the stock Responsive header and the page container
$html .= "<div class='page row'>";

if(!isset($_SESSION['LoggedIn']))
{
  if($status == "OK")
  {
		$html .= "<br/><div class='row--with-column-borders'><div class='columns small-12 medium-10 large-10 text-center alert_panel_succ medium-centered section--thick'>Thank you for submitting your Academic Probation Worksheet for Returning Students, a copy has been sent to your listed email address.</div></div>";
  }
  
  $html .= $loginForm->GetRiverBankInputDisplay();
}
else
{
	ob_start();
	
    if($status == "DB_ERR")
		$html .= "<br/><div class='row--with-column-borders'><div class='columns small-12 medium-10 large-10 text-center alert_panel_succ medium-centered section--thick'>There was a database error while processing your form, the database could currently be offline. Contact the College Center for Advising Services (585) 275-2354 for further assistance.</div></div>";
        
    if(!$validTest && !empty($errors))
		$html .= "<br/><div class='row--with-column-borders'><div class='columns small-12 medium-10 large-10 text-center alert_panel_fail medium-centered section--thick'>One or more required fields indicated below have been left blank!</div></div>"; 
	
    if(!$validTest && !empty($error_messages))
        echo $common->GetErrorDisplay($error_messages); 
    //echo var_dump($dump);
  ?>
<br/>
<fieldset class="formField">
<br>
    <form action="?" method="POST">
    <div class="row--with-column-borders">
      <div class="columns small-12 medium-12 large-12">
        <p><b>Instructions:</b> Answer these questions as fully as possible.  Your responses will be the starting point for the discussion with your probation advisor.</p>
      </div>
    </div>
    <div class="row--with-column-borders">
    	<div class="columns small-12 medium-10 end">
    		<p><b>NOTE:</b> Fields marked with a <span class="required">*</span> are <b>required</b>.</p>
       	</div>
    </div>
    <div class="row--with-column-borders text-center">
      	<div class="columns medium-12">
        	<h3>Student Information</h3>
      	</div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-6 large-6">
            <label for="studentFirstName" <?php if(in_array('studentFirstName',$errors)) echo "class='error'";?>><span class="required">*</span>First Name</label>
            <input type="text" name="studentFirstName" readonly maxlength="70" value="<?php echo $userData['firstName'];?>"/>
        </div>
        <div class="columns small-12 medium-6 large-6">
            <label for="studentLastName" <?php if(in_array('studentLastName',$errors)) echo "class='error'";?>><span class="required">*</span>Last Name</label>
            <input type="text" name="studentLastName" readonly maxlength="70" value="<?php echo $userData['lastName'];?>"/>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12 medium-6 large-6">
            <label for="studentID" <?php if(in_array('studentID',$errors)) echo "class='error'";?>><span class="required">*</span>University ID Number</label>
            <input type="text" name="studentID" readonly maxlength="8" value="<?php echo $userData['studentID'];?>"/>
        </div>
        <div class="columns small-12 medium-6 large-6">
            <label for="studentEmail" <?php if(in_array('studentEmail',$errors)) echo "class='error'";?>><span class="required">*</span>Email Address</label>
            <input type="text" name="studentEmail" maxlength="70" readonly value="<?php echo $userData['emailAddress'];?>"/>
        </div>
    </div>
    <br/>
    <div class="row--with-column-borders text-center">
        <div class="columns medium-12">
            <h3>Worksheet Questions</h3>
        </div>
    </div>
    <br/>
    <br/>
    <div class="row--with-column-borders">
        <div class="columns small-12 medium-12 large-12">
            <p <?php if(in_array('majors',$errors)) echo "class='error'";?>><span class="required">*</span><b>1.</b> What is your plan for completing the Rochester Curriculum degree requirements?</p>
            <ul style="list-style-type:none">
                <li><b>Major(s)</b>
                    <input type='text' size='40' maxlength='150' name='majors' value='<?php echo $formData['majors'];?>'/>
                </li>
                <li><b>Minor(s)</b>
                    <input type='text' size='40' maxlength='150' name='minors' value='<?php echo $formData['minors'];?>'/>
                </li>
                <li><b>Cluster 1</b>
                    <input type='text' size='40' maxlength='100' name='cluster1' value='<?php echo $formData['cluster1'];?>'/>
                </li>
                <li><b>Cluster 2</b>
                    <input type='text' size='40' maxlength='100' name='cluster2' value='<?php echo $formData['cluster2'];?>'/>
                </li>
            </ul>
        </div>
    </div>
    <br/>
    <br/>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p <?php if(in_array('problem',$errors)) echo "class='error'";?>><span class="required">*</span><b>2.</b> Describe the particular difficulties you encountered when you were previously enrolled.</p>
            <ul style="list-style-type:none">
                <li>
                    <p align="center"><textarea name="problem" rows="5" cols="70" id="problem" onkeydown="textCounter(this.form.problem,this.form.problemCount,500);" onkeyup="textCounter(this.form.problem,this.form.problemCount,500);"><?php echo $formData['problem'];?></textarea></p>
                </li>
            </ul>
        </div>
    </div>
    <div class="row--with-borders">
        <ul style="list-style-type:none">
            <li>
                <div class="columns small-3 medium-1 large-1">
                    <input class="text-center" readonly type="text" id="problemCount" name="problemCount" value="500">
                </div>
                <br><br> 
                <div class="columns small-8 medium-3 end">
                    <label for="charactersRemaining" class="postfix radius">Characters Remaining</label>
                </div>
            </li>
        </ul>
    </div>
    <div class="row--with-column-borders">
        <div class="columns small-12 medium-12 large-12">
            <p>&nbsp;&nbsp;<b>3. </b>Prior to taking time off, was your academic performance influenced by any of the following? Check all that apply.</p>
            <ul style="list-style-type:none">
                <input type="checkbox" name="influences[]" value="Disability" <?php if(isset($influences['Disability'])) echo " checked"; ?>/>
                Disability<br/>
                <input type="checkbox" name="influences[]" value="Finances" <?php if(isset($influences['Finances'])) echo " checked"; ?>/>
                Finances<br/>
                <input type="checkbox" name="influences[]" value="Physical or Emotional health" <?php if(isset($influences['Physical or Emotional health'])) echo " checked"; ?>/>
                Physical or Emotional health<br/>
                <input type="checkbox" name="influences[]" value="Alcohol and/or other substances" <?php if(isset($influences['Alcohol and/or other substances'])) echo " checked"; ?>/>
                Alcohol and/or other substances<br/>
                <input type="checkbox" name="influences[]" value="Work" <?php if(isset($influences['Work'])) echo " checked"; ?>/>
                Work or student activities<br/>
                <input type="checkbox" name="influences[]" value="Death or illness in family" <?php if(isset($influences['Death or illness in family'])) echo " checked"; ?>/>
                Death or illness in family<br/>
                <input type="checkbox" name="influences[]" value="Relationship/Peer/Roommates" <?php if(isset($influences['Relationship/Peer/Roommates'])) echo " checked"; ?>/>
                Relationship/Peer/Roommates<br/>
                <input type="checkbox" name="influences[]" value="Stress" <?php if(isset($influences['Stress'])) echo " checked"; ?>/>
                Stress<br/>
                <input type="checkbox" name="influences[]" value="Student Activities/Clubs" <?php if(isset($influences['Student Activities/Clubs'])) echo " checked"; ?>/>
                Student Activities/Clubs<br/>
                <input type="checkbox" name="influences[]" value="Other" <?php if(isset($influences['Other'])) echo " checked"; ?>/>
                Other:
                <input type="text" name='influences_other' value='<?php echo $formData['influences_other']; ?>'/><br/>
            </ul>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <ul style="list-style-type:none">
                If any extenuating circumstances influenced your previous academic performance, have they been resolved?
                <li>
                    <p align="center"><textarea name="resolve" rows="5" cols="70" id="resolve" onkeydown="textCounter(this.form.resolve,this.form.resolveCount,500);" onkeyup="textCounter(this.form.resolve,this.form.resolveCount,500);"><?php echo $formData['resolve'];?></textarea></p>
                </li>
            </ul>
        </div>
    </div>
    <div class="row--with-borders">
        <ul style="list-style-type:none">
            <li>
                <div class="columns small-3 medium-1 large-1">
                    <input class="text-center" readonly type="text" id="resolveCount" name="resolveCount" value="500">
                </div>
                <br><br> 
                <div class="columns small-8 medium-3 end">
                    <label for="charactersRemaining" class="postfix radius">Characters Remaining</label>
                </div>
            </li>
        </ul>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p <?php if(in_array('four',$errors)) echo "class='error'";?>><span class="required">*</span><b>4.</b> Describe the types of academic support you sought out during your previous enrollment. Please check any and all resources you used previously.</p>
            <ul style="list-style-type:none">
                <input type="checkbox" name="resources[]" value="Study Zone" <?php if(isset($resources['Study Zone'])) echo " checked"; ?>/>
                Study Zone<br/>
                <input type="checkbox" name="resources[]" value="Tutoring" <?php if(isset($resources['Tutoring'])) echo " checked"; ?>/>
                Tutoring<br/>
                <input type="checkbox" name="resources[]" value="Study Groups" <?php if(isset($resources['Study Groups'])) echo " checked"; ?>/>
                Study Groups<br/>
                <input type="checkbox" name="resources[]" value="CETL Study Skills Consultant" <?php if(isset($resources['CETL Study Skills Consultant'])) echo " checked"; ?>/>
                CETL Study Skills Consultant<br/>
                <input type="checkbox" name="resources[]" value="Office of Disability Resources" <?php if(isset($resources['Office of Disability Resources'])) echo " checked"; ?>/>
                Office of Disability Resources<br/>
                <input type="checkbox" name="resources[]" value="Undergraduate Advisor/Faculty Advisor" <?php if(isset($resources['Undergraduate Advisor/Faculty Advisor'])) echo " checked"; ?>/>
                Undergraduate Advisor/Faculty Advisor<br/>
                <input type="checkbox" name="resources[]" value="CCAS Advisor (Lattimore 312)" <?php if(isset($resources['CCAS Advisor (Lattimore 312)'])) echo " checked"; ?>/>
                CCAS Advisor (Lattimore 312)<br/>
                <input type="checkbox" name="resources[]" value="University Health Services (UHS)" <?php if(isset($resources['University Health Services (UHS)'])) echo " checked"; ?>/>
                University Health Services (UHS)<br/>
                <input type="checkbox" name="resources[]" value="University Counseling Center (UCC)" <?php if(isset($resources['University Counseling Center (UCC)'])) echo " checked"; ?>/>
                University Counseling Center (UCC)<br/>
                <input type="checkbox" name="resources[]" value="David T. Kearns Center for Leadership" <?php if(isset($resources['David T. Kearns Center for Leadership'])) echo " checked"; ?>/>
                David T. Kearns Center for Leadership<br/>
                <input type="checkbox" name="resources[]" value="Office of Minority Student Affairs (OMSA)" <?php if(isset($resources['Office of Minority Student Affairs (OMSA)'])) echo " checked"; ?>/>
                Office of Minority Student Affairs (OMSA)<br/>
                <input type="checkbox" name="resources[]" value="Professor and/or TA Office Hours" <?php if(isset($resources['Professor and/or TA Office Hours'])) echo " checked"; ?>/>
                Professor and/or TA Office Hours<br/>
                <input type="checkbox" name="resources[]" value="Writing Fellow and/or Writing Consultant" <?php if(isset($resources['Writing Fellow and/or Writing Consultant'])) echo " checked"; ?>/>
                Writing Fellow and/or Writing Consultant<br/>
                <input type="checkbox" name="resources[]" value="CARE Network" <?php if(isset($resources['CARE Network'])) echo " checked"; ?>/>
                CARE Network<br/>
                <input type="checkbox" name="resources[]" value="International Services Office (ISO)" <?php if(isset($resources['International Services Office (ISO)'])) echo " checked"; ?>/>
                International Services Office (ISO)<br/>
                <input type="checkbox" name="resources[]" value="Financial Aid" <?php if(isset($resources['Financial Aid'])) echo " checked"; ?>/>
                Financial Aid<br/>
                <input type="checkbox" name="resources[]" value="Athletic (coach or other individual)" <?php if(isset($resources['Athletic (coach or other individual)'])) echo " checked"; ?>/>
                Athletic (coach or other individual)<br/>
                <input type="checkbox" name="resources[]" value="Other" <?php if(isset($resources['Other'])) echo " checked"; ?>/>
                Other (including off campus):
                <input type="text" name='resources_other' value='<?php echo $formData['resourcesOther']; ?>'/><br/>
            </ul>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <ul style="list-style-type:none">
                <li>
                    Now that you are returning to school after time away, how will you approach your studies differently? For example, what resources do you plan to use upon your return? How do you expect to engage differently than when you were last enrolled? Have you decided to change your program of study? Are there other changes you would like to make?
                </li>
                <li>
                    <p align="center"><textarea name="plan" rows="5" cols="70" id="plan" onkeydown="textCounter(this.form.plan,this.form.planCount,500);" onkeyup="textCounter(this.form.plan,this.form.planCount,500);"><?php echo $formData['plan'];?></textarea></p>
                </li>
            </ul>
        </div>
    </div>
    <div class="row--with-borders">
        <ul style="list-style-type:none">
            <li>
                <div class="columns small-3 medium-1 large-1">
                    <input class="text-center" readonly type="text" id="planCount" name="planCount" value="500">
                </div>
                <br><br> 
                <div class="columns small-8 medium-3 end">
                    <label for="charactersRemaining" class="postfix radius">Characters Remaining</label>
                </div>
            </li>
        </ul>
    </div>
    <br/>
    <br/>
    <div class="row--with-column-borders">
        <div class="columns small-12 medium-12 large-12">
            <p <?php if(in_array('studyHabits',$errors)) echo "class='error'";?>><span class="required">*</span><b>5. </b>Describe your study habits, including where you studied, how you organized your time, etc.</p>
            <ul style="list-style-type:none">
                <textarea row='10' name='studyHabits' cols='70' style="height:8em;" wrap='physical' onKeyDown='textCounter(this.form.studyHabits, this.form.studyHabitsCount, 500);' onKeyUp='textCounter(this.form.studyHabits, this.form.studyHabitsCount, 500);' ><?php echo $formData['studyHabits']; ?></textarea>
            </ul>
        </div>
    </div>
    <div class="row--with-borders">
        <ul style="list-style-type:none">
            <li>
                <div class="columns small-3 medium-1 large-1">
                    <input class="text-center" readonly type="text" id="studyHabitsCount" name="studyHabitsCount" value="500">
                </div>
                <br><br> 
                <div class="columns small-8 medium-3 end">
                    <label for="charactersRemaining" class="postfix radius">Characters Remaining</label>
                </div>
            </li>
        </ul>
    </div>
    <br/>
    <br/>
    <div class="row--with-column-borders">
        <div class="columns small-12 medium-12 large-12">
            <p <?php if(in_array('six',$errors)) echo "class='error'";?>><span class="required">*</span><b>6. </b>Do you have any I/N grades in any course(s)?</p>
        </div>
    </div>
    <div class="row--with-column-borders">
        <div class="columns small-12 medium-12 large-12">
            <ul style="list-style-type:none">
                <li>
                    <p>
                        <input type="checkbox" name="inYes" id="inYes" onclick="checkInYes()" size='3' value='Yes' <?php if($formData['inYes'] == 'Yes') echo ' checked';?>/>&nbsp;&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="inNo" id="inNo" onclick="checkInNo()" size='3' value='Yes' <?php if($formData['inNo'] == 'Yes') echo ' checked';?>/>&nbsp;&nbsp;No
                    </p>  
                </li>
            </ul>
        </div>
    </div>
    <div class="row--with-column-borders">
        <div class="columns small-12 medium-12 large-12">
            <ul id="incompletesInfo" style="list-style-type:none">
                <li>
                    How much work do you estimate you have left to complete in each class, and how long do you determine you will need to complete the assignments for each course?
                </li>
                <li>
                    <textarea row='10' name='incompletes' id='incompletesText' cols='70' style="height:8em;" wrap='physical' onKeyDown='textCounter(this.form.incompletes, this.form.incompletesCount, 500);' onKeyUp='textCounter(this.form.incompletes, this.form.incompletesCount, 500);' ><?php echo $formData['incompletes']; ?></textarea>
                </li>
            </ul>
        </div>
    </div>
    <div class="row--with-borders">
        <ul id="incompletesCountInfo" style="list-style-type:none">
            <li>
                <div class="columns small-3 medium-1 large-1">
                    <input class="text-center" readonly type="text" id="incompletesCount" name="incompletesCount" value="500">
                </div>
                <br><br> 
                <div class="columns small-8 medium-3 end">
                    <label for="charactersRemaining" class="postfix radius">Characters Remaining</label>
                </div>
            </li>
        </ul>
    </div>
    <div class="row--with-column-borders">
        <div class="columns small-12 medium-12 large-12">
            <ul style="list-style-type:none">
                <li>
                    <p id="instructorInfo">Have you been in communication with the Instructor?</p>
                </li>
                <li>
                    <p id="instructorAnswersInfo">
                        <input type="checkbox" name="instructorYes" id="instructorYes" onclick="checkInstructorYes()" size='3' value='Yes' <?php if($formData['instructorYes'] == 'Yes') echo ' checked';?>/>&nbsp;&nbsp;Yes&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" name="instructorNo" id="instructorNo" onclick="checkInstructorNo()" size='3' value='Yes' <?php if($formData['instructorNo'] == 'Yes') echo ' checked';?>/>&nbsp;&nbsp;No
                    </p>  
                </li>
            </ul>
        </div>
    </div>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p <?php if(in_array('schedule',$errors)) echo "class='error'";?>><span class="required">*</span><b>7. </b>What is your tentative course schedule for the upcoming semester?</p>
            <ul style="list-style-type:none">
                <li>
                    <p align="center"><textarea name="schedule" rows="5" cols="70" id="schedule" onkeydown="textCounter(this.form.schedule,this.form.scheduleCount,500);" onkeyup="textCounter(this.form.schedule,this.form.scheduleCount,500);"><?php echo $formData['schedule'];?></textarea></p>
                </li>
            </ul>
        </div>
    </div>
    <div class="row--with-borders">
        <ul style="list-style-type:none">
            <li>
                <div class="columns small-3 medium-1 large-1">
                    <input class="text-center" readonly type="text" id="scheduleCount" name="scheduleCount" value="500">
                </div>
                <br><br> 
                <div class="columns small-8 medium-3 end">
                    <label for="charactersRemaining" class="postfix radius">Characters Remaining</label>
                </div>
            </li>
        </ul>
    </div>
    <br/>
    <br/>
    <div class="row--with-borders">
        <div class="columns small-12">
            <p <?php if(in_array('help',$errors)) echo "class='error'";?>><span class="required">*</span><b>8. </b>How can your CCAS Advisor help you achieve your goals for the upcoming semester, and with your re-entry into the University of Rochester?</p>
            <ul style="list-style-type:none">
                <li>
                    <p align="center"><textarea name="help" rows="5" cols="70" id="help" onkeydown="textCounter(this.form.help,this.form.helpCount,500);" onkeyup="textCounter(this.form.help,this.form.helpCount,500);"><?php echo $formData['help'];?></textarea></p>
                </li>
            </ul>
        </div>
    </div>
    <div class="row--with-borders">
        <ul style="list-style-type:none">
            <li>
                <div class="columns small-3 medium-1 large-1">
                    <input class="text-center" readonly type="text" id="helpCount" name="helpCount" value="500">
                </div>
                <br><br> 
                <div class="columns small-8 medium-3 end">
                    <label for="charactersRemaining" class="postfix radius">Characters Remaining</label>
                </div>
            </li>
        </ul>
    </div>
    <br><br>
    <div class="row--with-borders">
        <div class="text-center columns small-12">
            <input class="small button secondary button-pop" name="Save" type="submit" value="Submit"/>
        </div>
    </div>
    </form>
</fieldset>
<br/>
</article>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script>

function textCounter(field, countfield, maxlimit)
{
  if(field.value.length > maxlimit)
  {
    field.value = field.value.substring(0, maxlimit);
  }
  else
  {
    countfield.value = maxlimit - field.value.length;
  }
}

function checkInYes()
{
    if(document.getElementById("inYes").checked == true)
    {
        document.getElementById("inNo").checked = false;
    }
}
function checkInNo()
{
    if(document.getElementById("inNo").checked == true)
    {
        document.getElementById("inYes").checked = false;
    }
}

function checkInstructorYes()
{
    if(document.getElementById("instructorYes").checked == true)
    {
        document.getElementById("instructorNo").checked = false;
    }
}
function checkInstructorNo()
{
    if(document.getElementById("instructorNo").checked == true)
    {
        document.getElementById("instructorYes").checked = false;
    }
}

$(document).ready(function () {
    $("#instructorInfo").hide();
    $("#instructorAnswersInfo").hide();
    $("#incompletesInfo").hide();
    $("#incompletesCountInfo").hide();

    if(document.getElementById('inYes').checked == true) {
        showIncompletes();
        showInstructor();
    }

    document.getElementById('inYes').addEventListener('change', function () {
        if(this.checked) {
            showIncompletes();
            showInstructor();
        }
        else {
            hideInstructor();
            hideIncompletes();
            if(document.getElementById("instructorYes").checked == true) {
                document.getElementById("instructorYes").checked = false;
            }
            else {
                document.getElementById("instructorNo").checked = false;
            }
            $('#incompletesText').val('');
            $('#incompletesCount').val(500);
        }
    });

    document.getElementById('inNo').addEventListener('change', function () {
        if(this.checked) {
            hideIncompletes();
            hideInstructor();
            if(document.getElementById("instructorYes").checked == true) {
                document.getElementById("instructorYes").checked = false;
            }
            else {
                document.getElementById("instructorNo").checked = false;
            }
            $('#incompletesText').val('');
            $('#incompletesCount').val(500);
        }
    });

    function showInstructor() {
        $("#instructorInfo").show();
        $("#instructorAnswersInfo").show();
    }

    function hideInstructor() {
        $("#instructorInfo").hide();
        $("#instructorAnswersInfo").hide();
    }

    function showIncompletes() {
        $("#incompletesInfo").show();
        $("#incompletesCountInfo").show();
    }

    function hideIncompletes() {
        $("#incompletesInfo").hide();
        $("#incompletesCountInfo").hide();
    }
});

</script>

<?php		
	$html .= ob_get_contents();
	ob_end_clean();
}

$html .= "</div>";	//Make sure we close the page container.

$style = "style_riverbank.css";
$pageTitle = "Academic Probation Worksheet for Returning Students";
$pageHeader = "Academic Probation Worksheet for Returning Students";
$pageContent = $html;


include_once('templates/responsive_riverbank.php');
?>