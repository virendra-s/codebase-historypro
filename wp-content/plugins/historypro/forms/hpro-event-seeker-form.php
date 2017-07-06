<?php 

require_once (historypro.'hpro-functions.php');
  
  add_shortcode ('hpro_event_seeker_form', 'hpro_show_event_seeker_form_short');
  
   
  //Display seeker form Long
function hpro_show_event_seeker_form (){
    
   ?>
        
<!-- The form to get user's location, user's queried date and events around it. 
User's default location is hhis device geolocation and default date time is current time.
All he needs to do is submit if does not want to change defaults.-->
<div class="wrap">

<?php    echo "<h2>" . __( 'Event Seeker Form Heading', 'hpro_text_domain' ) . "</h2>"; ?>

    <form name="hpro_query_form" method="GET" action="../wp-content/plugins/historypro/frontend-pages/hpro-display-map.php" id="seekerForm">
         
        <!--Category selector -->
        <p><?php _e("Show: " ); ?><select name="seekCategory" form="seekerForm" required> 
        <option value="allEvents">allEvents</option>
        <option value="Accident">Accident</option>
        <option value="War">War</option>
        <option value="Movie">Movie</option>
        <option value="Sport">Sport</option>
        </select> </p>
            
            <!--City selector -->
        <p><?php _e("Around: " ); ?><select name="seekLocation" form="seekerForm" required>
        <option value="MyLocation">MyLocation</option>
        <option value="Mumbai">Mumbai</option>
        <option value="New Delhi">New Delhi</option>
        <option value="New York">New York</option>
        <option value="Paris">Paris</option>
        <option value="London">London</option>
        </select> </p>
        
        <p><?php _e("Between Date: " );?></p>
        <table>
            <tr>
                <td>
            <!--Date selector -->
        <p><?php _e("Date: " ); ?><select name="seekStartDate" form="seekerForm" required>
        <?php $counter = 1; while ($counter <32){ if ($counter < 10) {$temp = '0';} else {$temp = NULL;}?>        
        <option value="<?php echo $temp.$counter?>"><?php echo $temp.$counter?></option><?php $counter++; }?>
        </select> </p>
                </td>
                <td>     <!--Month selector -->
        <p><?php _e("Month: " ); ?><select name="seekStartMonth" form="seekerForm" required>
        <?php $counter = 1; while ($counter <13){ if ($counter < 10) {$temp = '0';} else {$temp = NULL;}?>        
        <option value="<?php echo $temp.$counter?>"><?php echo $temp.$counter?></option><?php $counter++; }?>
        </select> </p>
            </td>

        <td><!--Year entry -->
        <p><input type="text" name="seekStartYear" placeholder = "Year" maxlength="4" size="4" required></p>
        </td>
        
        
        
            </tr>
            <tr>
                <td>
            <!--Hour selector -->
        <p><?php _e("Hour: " ); ?><select name="seekStartHour" form="seekerForm" required>
         <?php $counter = 00; while ($counter <24){ if ($counter < 10) {$temp = '0';} else {$temp = NULL;}?>        
        <option value="<?php echo $temp.$counter?>"><?php echo $temp.$counter?></option><?php $counter++; }?>
        </select> </p>
                </td>
                <td>     <!--Minute selector -->
        <p><?php _e("Minute: " ); ?><select name="seekStartMinute" form="seekerForm" required>
         <?php $counter = 00; while ($counter <60){ if ($counter < 10) {$temp = '0';} else {$temp = NULL;}?>        
        <option value="<?php echo $temp.$counter?>"><?php echo $temp.$counter?></option><?php $counter++; }?>
        </select></p>
            </td>

        <td><!--Second entry -->
        <p><?php _e("Second: " ); ?><select name="seekStartSecond" form="seekerForm" required>
        <?php $counter = 00; while ($counter <60){ if ($counter < 10) {$temp = '0';} else {$temp = NULL;}?>        
        <option value="<?php echo $temp.$counter?>"><?php echo $temp.$counter?></option><?php $counter++; }?>
        </select></p>
        </td>
        
               
            </tr>
        </table>
        
        <p><?php _e("And Date: " );?>
        <table>
            <tr>
                <td>
            <!--Date selector -->
        <p><?php _e("Date: " ); ?><select name="seekEndDate" form="seekerForm" required>
        <?php $counter = 1; while ($counter <32){ if ($counter < 10) {$temp = '0';} else {$temp = NULL;}?>        
        <option value="<?php echo $temp.$counter?>"><?php echo $temp.$counter?></option><?php $counter++; }?>
        </select> </p>
                </td>
                <td>     <!--Month selector -->
        <p><?php _e("Month: " ); ?><select name="seekEndMonth" form="seekerForm" required>
        <?php $counter = 1; while ($counter <13){ if ($counter < 10) {$temp = '0';} else {$temp = NULL;}?>        
        <option value="<?php echo $temp.$counter?>"><?php echo $temp.$counter?></option><?php $counter++; }?>
        </select></p>
            </td>

        <td><!--Year entry -->
        <p><input type="text" name="seekEndYear" maxlength="4" size="4" placeholder = "Year (Optional)"></p>
        </td>
        
         
        
            </tr>
            <tr>
                <td>
            <!--Hour selector -->
        <p><?php _e("Hour: " ); ?><select name="seekEndHour" form="seekerForm" required>
         <?php $counter = 00; while ($counter <24){ if ($counter < 10) {$temp = '0';} else {$temp = NULL;}?>        
        <option value="<?php echo $temp.$counter?>" selected><?php echo $temp.$counter?></option><?php $counter++; }?>
        </select> </p>
                </td>
                <td>     <!--Minute selector -->
        <p><?php _e("Minute: " ); ?><select name="seekEndMinute" form="seekerForm" required>
        <?php $counter = 00; while ($counter <60){ if ($counter < 10) {$temp = '0';} else {$temp = NULL;}?>        
        <option value="<?php echo $temp.$counter?>" selected><?php echo $temp.$counter?></option><?php $counter++; }?>
        </select></p>
            </td>

        <td><!--Second entry -->
        <p><?php _e("Seconds: " ); ?><select name="seekEndSecond" form="seekerForm" required></p>
        <?php $counter = 00; while ($counter <60){ if ($counter < 10) {$temp = '0';} else {$temp = NULL;}?>        
        <option value="<?php echo $temp.$counter?>" selected><?php echo $temp.$counter?></option><?php $counter++; }?>
        </select></p>
        </td>
      
            </tr>
        </table>
        
        <p><?php _e("In Period and TimeZone: " ); ?>
        <table>
            <tr>
        <td>
        <p><?php _e("AD or BC: " ); ?>
        <select name="seekADBC" form="seekerForm" required>
        <option value="AD">AD</option>
        <option value="BC">BC</option>
        </select></p> </td>
        
        <td>
        <p><?php _e("TimeZone: " ); ?> 
        <select name="seekTimeZone" form="seekerForm" required>
        <option value="GMT">GMT</option>
        <option value="IST">ISt</option>
        </select></p> </td>
        
        </tr>
        </table>
        
         <hr />
        <p class="submit">
        <input type="submit" name="Submit" value="<?php _e('Submit', 'hpro_text_domain' ) ?>" />
        </p>
    </form>
</div>

<?php
   }
   
   //Show seeker form short
   
 //Display seeker form Short
function hpro_show_event_seeker_form_short (){
    
   ?>
<div class="wrap">
    <form name="hpro_query_form" method="GET" action="../wp-content/plugins/historypro/frontend-pages/hpro-display-map.php" id="seekerForm">
         
        <!--Category selector -->
       <?php echo   hpro_category_drop_down_generator('seekCategory', 'seekerForm');?>
        
        <p><?php _e("Select a Date: " );?></p>
        <table>
            <tr>
                <td>
            <!--Date selector -->
        <?php echo hpro_date_drop_down_generator('seekStartDate', 'seekerForm');?>
                </td>
                <td>     <!--Month selector -->
        <?php echo hpro_month_drop_down_generator('seekStartMonth', 'seekerForm');?>
            </td>

        <td><!--Year entry -->
        <p><input type="text" name="seekStartYear" placeholder = "Year" maxlength="4" size="4" required></p>
        </td>
        </tr>
        </table>
        
        <hr />
        <p class="submit">
        <input type="submit" name="Submit" value="<?php _e('Submit', 'hpro_text_domain' ) ?>" />
        </p>
    </form>
</div>

<?php
   }
