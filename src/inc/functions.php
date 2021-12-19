<?php

function serverForm()
{
    echo 'echo
    <br>
        <div class="row">
          <div class="large-12 columns">
            <div class="callout">
              <h3>Create New Read Only User</h3>
              <form action="index.php" method="post" enctype="application/x-www-form-urlencoded">
                  <div class="row">
                    <div class="medium-6 columns">
                    <p class="help-text"><strong>Query:</strong> mysql --user=lsql_maint --password=? -h (?) -e "STORED PRO";</p>
                      <label>Select Hostname
                        <select name="hostname" id="hostname">
                          <option value="dash-rw-qa.dbc.chenmed.local">dash-rw-qa.dbc.chenmed.local</option>
                          <option value="dash-rw-stg.dbc.chenmed.local">dash-rw-stg.dbc.chenmed.local</option>
                          <option value="dash-rw-dev.dbc.chenmed.local">dash-rw-dev.dbc.chenmed.local</option>
                          <option value="dash-rw-uat.dbc.chenmed.local">dash-rw-uat.dbc.chenmed.local</option>
                          <option value="RAD_DEV">RAD_DEV</option>
                        </select>
                      </label>
                      <label>New Username
                        <input type="text" name="NEW_USERNAME_READONLY" placeholder="Input New Username" required>
                      </label>
                      <label>New Password
                        <input type="text" name="NEW_PASSWORD_READONLY" placeholder="Inpur New Password" required>
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
