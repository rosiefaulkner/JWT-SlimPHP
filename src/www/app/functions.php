<?php

class DisplayForm
{
	public function __construct()
	{
		$this->serverForm();
	}
	public function serverForm()
	{
		echo '
		<br>
			<div class="row">
			  <div class="large-12 columns">
				<div class="callout">
				  <h3>Get Last 100 Lines of Console Log</h3>
				  <form action="index.php" method="post" enctype="application/x-www-form-urlencoded">
					  <div class="row">
						<div class="medium-6 columns">
						  <label>Select Server
							<select name="HOSTNAME" id="hostname">
							  <option value="10.200.18.144">10.200.18.144</option>
							  <option value="10.200.23.203">10.200.23.203</option>
							</select>
						  </label>
						</div>
					</div>
					<div class="row">
						<div class="medium-6 columns">
							<input type="submit" class="button" value="Execute">
						</div>
					</div>
				</form>
				</div>
			  </div>
			</div>
		';
	}
}
$display = new DisplayForm();
