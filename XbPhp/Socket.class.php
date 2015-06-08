<?php
/**
 * SOCKET
 * @author wave
 */

class Socket {

	//要連接的地址
	public static $address;

	//端口
	public static $port;

	//發送的數據
	public static $data;

	protected static $sock;
	
	//連接的次數
	private static $link = 0;

	/**
	 * 創建SOCKET
	 * @author wave
	 */
	protected static function create() {
		self::$sock = socket_create(AF_INET,SOCK_STREAM,getprotobyname('tcp'));
		if(!self::$sock) {
			exit('create socket error');
		}
	}

	/**
	 * socket遠程連接
	 * @author wave
	 */
	protected static  function connect() {
		if(empty(self::$link)){
			Socket::create();
			if(!socket_connect(self::$sock, self::$address,self::$port)) {
				exit(socket_strerror(socket_last_error()));
			}
			++self::$link;
		}
	}

	/**
	 * socket發送數據
	 * @author wave
	 */
	public static function send() {
		self::connect();
		self::wirte();
	}

	/**
	 * socket寫入數據
	 * @author wave
	 */
	protected function wirte() {
		socket_write(self::$sock, self::$data,strlen(self::$data));
	}

	/**
	 * socket读取狀態
	 * @author wave
	 */
	public static function read() {
		return socket_read(self::$sock,4096);
	}

	/**
	 * 關閉socket
	 * @author wave
	 */
	public static function colse() {
		socket_close(self::$sock);
	}
}