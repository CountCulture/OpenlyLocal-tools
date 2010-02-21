<?php
/* Widget options */
function ol_upcoming_meetings_widget_options(){
	if ($_POST['olum_submit']){
		$olum_options['title'] = htmlspecialchars($_POST['olum_title']);
		$olum_options['pre_text'] = htmlspecialchars($_POST['olum_pre_text']);
		$olum_options['authority'] = htmlspecialchars($_POST['olum_authority']);
		update_option("olum_widget", $olum_options);
	}
    
    $olum_options = get_option('olum_widget');
    
	// Load the councils XML for choosing the right authority
	$xml = simplexml_load_file('http://openlylocal.com/councils.xml');
	
	// Retrieve the currently selected council, if there is one.
	$current_council = $olum_options['authority'];
	
    ?>
	<p>
		<label for="olum_title">Title: </label>
		<input type="text" id="olum_title" name="olum_title" value="<?php echo $olum_options['title'];?>" />
	</p>
	<p>
		<label for="olum_pre_text">Text to display: </label>
		<input type="text" id="olum_pre_text" name="olum_pre_text" value="<?php echo $olum_options['pre_text'];?>" />
	</p>
	<p>
		<label for="olum_authority">Select your local authority: </label>
        <select id="olum_authority" name="olum_authority">
            <?php
                foreach ($xml->council as $council){
                    echo '<option value="'.$council->id.'"';
                    if ($current_council == $council->id){
                        echo ' selected="selected"';
                    }
                    echo '>'.$council->name.'</option>'."\n";
                }
            ?>
        </select>
		<input type="hidden" id="olum_submit" name="olum_submit" value="1" />
	</p>
    <?php
}

// Upcoming meetings widget
function ol_upcoming_meetings_widget($args){
	extract($args); // Prep to display widget code
	$olum_options = get_option('olum_widget');
	
	echo $before_widget;
	echo $before_title.$olum_options['title'].$after_title;
	ol_upcoming_meetings_widget_contents();
	echo $after_widget;
}

// Upcoming meetings DASHBOARD widget
function ol_upcoming_meetings_dbwidget(){
    ol_upcoming_meetings_widget_contents();
}

function ol_upcoming_meetings_widget_contents(){
	$olum_options = get_option('olum_widget'); // The council we're displaying
	$xml = simplexml_load_file("http://openlylocal.com/meetings.xml?council_id=".$olum_options['authority']); // Load OL XML
	
	echo "<ul>\n";
	$i = 0; //counter for number of meetings
	foreach ($xml->meeting as $meeting){
		if ($i>=5) { break; } // don't list more than 5 meetings
		$date = $meeting->{'formatted-date'};
		$url = $meeting->{'openlylocal-url'};
		echo '<li>'.$date.': <a href="'.$url.'">'.$meeting->title.'</a></li>'."\n";
		$i++; //increment the counter
	}
	echo "</ul>\n<p>Information provided by <a href='http://openlylocal.com' title='OpenlyLocal :: Opening Up Your Local Area'>OpenlyLocal :: Opening Up Your Local Area</a></p>\n";
}

?>