<div class="content-container unit size3of4 lastUnit">
	<article>
		<div id="Banner">
		  <img src="http://www.silverstripe.org/themes/silverstripe/images/sslogo.png"
		alt="Homepage image" />
		</div>
        <div id="BrowserPoll">
            <% if $BrowserPollForm %>
                $BrowserPollForm
            <% else %>
                <ul>
                    <% loop $BrowserPollResults %>
                    <li>
                        <div class="browser">$Browser : $Count : $Percentage%</div>
                        <div class="bar" style="width:$Percentage%">&nbsp;</div>
                    </li>
                    <% end_loop %>
                </ul>
            <% end_if %>
        </div>
		<div class="content">$Content</div>
	</article>
		$Form
		$PageComments
</div>
