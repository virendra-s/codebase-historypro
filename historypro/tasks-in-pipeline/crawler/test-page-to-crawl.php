<?php 

require_once (historypro.'hpro-functions.php');
      
add_shortcode('hpro_test_shortcode', 'hpro_test_shortcode_function');


//Display Feeder Form

function hpro_test_shortcode_function (){
   ?>
        
<!-- The form to feed event details in databse -->
 
<div class="wrap">
    <a href="www.historypro.dev"><img src="http://www.histroypro.dev/wp-content/uploads/2017/06/hitler-rally.jpg"></a>
<form name="hpro_query_form" method="GET" action="../wp-content/plugins/historypro/pages/insert-event-tables.php" id="categoryform">
         
        <?php    echo "<h4>" . __( 'Please enter the event details you want to feed.', 'hpro' ) . "</h4>"; ?>
    
        <p><?php _e("Event Name: " ); ?><input type="text" name="EventName" size="20" required></p>
        
        <!--Category selector -->
        <?php echo hpro_category_drop_down_generator('EventCategory', 'categoryform');?><a href="../add-new-category">Add New Category</a>
            
            <!--City selector -->
        <?php echo hpro_location_drop_down_generator('EventLocation', 'categoryform');?><a href="../add-new-city">Add New City</a>
            
        <p><?php _e("Event Start Date: " );?></p>
        <table>
            <tr>
                <td>
            <!--Date selector -->
        <?php echo hpro_date_drop_down_generator('EventStartDate', 'categoryform');?>
                </td>
                <td>     <!--Month selector -->
        <?php echo hpro_month_drop_down_generator('EventStartMonth', 'categoryform');?>
            </td>
        <td><!--Year entry -->
        <p><input type="text" name="EventStartYear" placeholder = "Year" maxlength="4" size="4" required></p>
         </td>
         <td>
            <!--Hour selector -->
        <?php echo hpro_hour_drop_down_generator('EventStartHour', 'categoryform')?>
                </td>
                <td>     <!--Minute selector -->
        <?php echo hpro_minute_drop_down_generator('EventStartMinute', 'categoryform')?>
            </td>
        <td><!--Second entry -->
            <?php echo hpro_seconds_drop_down_generator('EventStartSecond', 'categoryform')?>
          </td>
        </tr>
        </table>
        
        <p><?php _e("Event End Date: " );?>
        <table>
            <tr>
                 <td>
            <!--Date selector -->
        <?php echo hpro_date_drop_down_generator('EventEndDate', 'categoryform');?>
                </td>
                <td>     <!--Month selector -->
        <?php echo hpro_month_drop_down_generator('EventEndMonth', 'categoryform');?>
            </td>

        <td><!--Year entry -->
        <p><input type="text" name="EventEndYear" maxlength="4" size="4" placeholder = "Year (Optional)"></p>
        </td>
         <td>
            <!--Hour selector -->
        <?php echo hpro_hour_drop_down_generator('EventEndHour', 'categoryform')?>
                </td>
                <td>     <!--Minute selector -->
        <?php echo hpro_minute_drop_down_generator('EventEndMinute', 'categoryform')?>
            </td>

        <td><!--Second entry -->
            <?php echo hpro_seconds_drop_down_generator('EventEndSecond', 'categoryform')?>
        
        </td>
        </tr>
        </table>
        
        <p><?php _e("Period and TimeZone: " ); ?>
        <table>
            <tr>
        <td>
        <p><?php _e("AD or BC: " ); ?>
        <select name="EventADBC" form="categoryform" required>
        <option value="AD">AD</option>
        <option value="BC">BC</option>
        </select></p> </td>
        
        <td>
        <p><?php _e("TimeZone: " ); ?> 
        <select name="EventTimeZone" form="categoryform" required>
        <option value="GMT">GMT</option>
        <option value="IST">ISt</option>
        </select></p> </td>
        
        </tr>
        </table>
        
           <!--Description entry -->
        <p><?php _e("Event Description: " ); ?><input type="text" name="EventDescription" maxlength="100" size="100"></p>
        <p><?php _e("Event URL: " ); ?><input type="URL" name="EventUrl" maxlength="100" size="100" placeholder="URL of the event, if any."></p>
        <p><?php _e("Event Audio File URL: " ); ?><input type="URL" name="EventAudioUrl" maxlength="100" size="100" placeholder="URL of event audio file, if any."></p>
        <p><?php _e("Event Video file URL: " ); ?><input type="URL" name="EventVideoUrl" maxlength="100" size="100" placeholder="URL of event video file, if any."></p>
        <p><?php _e("Event Image file URL: " ); ?><input type="URL" name="EventImageUrl" maxlength="100" size="100" placeholder="URL of event image file, if any."></p>
        <hr />
        <p class="submit">
        <input type="submit" name="Submit" value="<?php _e('Submit', 'hpro_text_domain' ) ?>" />
        </p>
    </form>
</div>
<?php
}
