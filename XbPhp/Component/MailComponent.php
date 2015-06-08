<?php
/**
 * 发送mail组件
 * @author wave
 */


class MailComponent {
	
	/**
	 * 發送EMAIL的組件
	 * @param Array $opt 發送的數據
	 * @author wave
	 */
	public function mail_send($opt = array()) {
		set_time_limit(0);
		Socket::$address = $opt['address'];
		Socket::$port =  $opt['port'];
		$_data = array(
			0 => "EHLO ".$opt['cc']."\r\n",
			1 => "AUTH LOGIN\r\n",
			2 => base64_encode($opt['form'])."\r\n",
			3 => base64_encode($opt['pass'])."\r\n",
			4 => "MAIL FROM: <".$opt['form'].">\r\n",
			5 => "RCPT TO: <".$opt['to'].">\r\n",
			6 => "Content-Type: text/html; charset=\"utf-8\"\r\n",
			7 => "DATA\r\n",
			8 => "Form: ".$opt['cc']."<".$opt['form'].">\r\nTo: ".$opt['to']."\r\nSubject: ".$opt['title']."\r\n\r\n".$opt['body']."\r\n",
			9 => "\r\n.\r\n",
			10 => "QUIT\r\n"
		);
		$jilu = array(); //記錄發送數組
		foreach($_data as $k => $v) {
			Socket::$data = $v;
			Socket::send();
			$jilu['ok'][$k] = Socket::read();
			if(!in_array($k,array(7,8))){

			}
			if($k - count($_data) == 0) {
				if(substr(Socket::read(), 0,3) != "250") {
					$jilu['err'] = Socket::read();
				}
			}
		}
		Socket::colse();
		return isset($jilu['err']) ? $jilu['err'] : 'ok';
	}



}