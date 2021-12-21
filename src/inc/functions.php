<?php



class PrintConsoleLogs
{
	// SSH Host
	private $ssh_host = 'myserver.example.com';
	// SSH Port
	private $ssh_port = 22;
	// SSH Server Fingerprint
	private $ssh_server_fp = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
	// SSH Username
	private $ssh_auth_user = 'username';
	// SSH Public Key File
	private $ssh_auth_pub = '/home/username/.ssh/id_rsa.pub';
	// SSH Private Key File
	private $ssh_auth_priv = '/home/username/.ssh/id_rsa';
	// SSH Private Key Passphrase (null == no passphrase)
	private $ssh_auth_pass;
	// SSH Connection
	private $connection;

	function __construct()
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
                        <select name="server_name" id="server_name">
                          <option value="dash-rw-qa.dbc.chenmed.local">dash-rw-qa.dbc.chenmed.local</option>
                          <option value="dash-rw-stg.dbc.chenmed.local">dash-rw-stg.dbc.chenmed.local</option>
                          <option value="dash-rw-dev.dbc.chenmed.local">dash-rw-dev.dbc.chenmed.local</option>
                          <option value="dash-rw-uat.dbc.chenmed.local">dash-rw-uat.dbc.chenmed.local</option>
                          <option value="RAD_DEV">RAD_DEV</option>
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
		if (isset($_POST["SERVER_NAME"])) {
			$this->connect();
		}
	}

	public function connect()
	{
		if (!($this->connection = ssh2_connect($this->ssh_host, $this->ssh_port))) {
			throw new Exception('Cannot connect to server');
		}
		$fingerprint = ssh2_fingerprint($this->connection, SSH2_FINGERPRINT_MD5 | SSH2_FINGERPRINT_HEX);
		if (strcmp($this->ssh_server_fp, $fingerprint) !== 0) {
			throw new Exception('Unable to verify server identity!');
		}
		if (!ssh2_auth_pubkey_file($this->connection, $this->ssh_auth_user, $this->ssh_auth_pub, $this->ssh_auth_priv, $this->ssh_auth_pass)) {
			throw new Exception('Autentication rejected by server');
		}
	}
	public function command()
	{
		// CD to logs
		$stream = ssh2_exec($this->connection, "tail -f -n 100 /var/logs");
		$this->exec($stream);
	}

	public function exec($cmd)
	{
		if (!($stream = ssh2_exec($this->connection, $cmd))) {
			throw new Exception('SSH command failed');
		}
		stream_set_blocking($stream, true);
		$data = "";
		while ($buf = fread($stream, 4096)) {
			$data .= $buf;
		}
		fclose($stream);
		return $data;
	}
	public function disconnect()
	{
		$this->exec('echo "EXITING" && exit;');
		$this->connection = null;
	}
	public function __destruct()
	{
		$this->disconnect();
	}
}
$newprint = new PrintConsoleLogs();
