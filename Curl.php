<?php

use yii\helpers\ArrayHelper;

/**
 * CURL工具类
 *
 * @namespace 
 * @filename Curl.php
 * @encoding UTF-8
 * @author forsona <2317216477@qq.com>
 * @link https://github.com/forsona
 * @datetime 2016-7-22 17:27:50
 * @version $Id$
 */
class Curl {

	public $options = [ ];
	private $_config = [
		CURLOPT_RETURNTRANSFER => TRUE, // 返回页面内容
		CURLOPT_HEADER => FALSE, // 不返回头部
		CURLOPT_ENCODING => "", // 处理所有编码
		CURLOPT_USERAGENT => "spider", // 
		CURLOPT_AUTOREFERER => TRUE, // 自定重定向
		CURLOPT_CONNECTTIMEOUT => 30, // 链接超时时间
		CURLOPT_TIMEOUT => 60, // 超时时间
		CURLOPT_MAXREDIRS => 10, // 超过十次重定向后停止
		CURLOPT_SSL_VERIFYHOST => FALSE, // 不检查ssl链接
		CURLOPT_SSL_VERIFYPEER => FALSE,
		CURLOPT_VERBOSE => TRUE,
	];
	private $_error = null;
	private $_header = null;
	private $_headerMap = null;
	private $_info = null;
	private $_status = null;
	private $_debug = false;

	public function __construct( $options = [ ] ) {
		$this->options = $options;
		$this->init();
	}

	public function getOptions() {
		return ArrayHelper::merge( $this->options, $this->_config );
	}

	public function setOption( $key, $value ) {
		$this->options[$key] = $value;
		return $this;
	}

	public function setOptions( $options ) {
		$this->options = ArrayHelper::merge( $this->options, $options );
		return $this;
	}

	public function buildUrl( $url, $param ) {
		return $url . (strpos( $url, '?' ) ? '&' : '?') . http_build_query( $param );
	}

	public function exec( $url, $options ) {
		$ch = curl_init( $url );
		curl_setopt_array( $ch, $options );
		$output = curl_exec( $ch );
		$this->_status = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		if ( FALSE === $output ) {
			$this->_error = curl_error( $ch );
			$this->_info = curl_getinfo( $ch );
		} else if ( TRUE === $this->_debug ) {
			$this->_info = curl_getinfo( $ch );
		}
		if ( isset( $options[CURLOPT_HEADER] ) && TRUE === $options[CURLOPT_HEADER] ) {
			list($header, $output) = $this->_processHeader( $output, curl_getinfo( $ch, CURLINFO_HEADER_SIZE ) );
			$this->_header = $header;
		}
		curl_close( $ch );
		return $output;
	}

	private function _processHeader( $response, $headerSize ) {
		return [substr( $response, 0, $headerSize ), substr( $response, $headerSize ) ];
	}

	public function get( $url, $param = [ ] ) {
		$execUrl = $this->buildUrl( $url, $param );
		$options = $this->getOptions();
		return $this->exec( $execUrl, $options, $this->_debug );
	}

	public function post( $url, $param = [ ], $post ) {
		$execUrl = $this->buildUrl( $url, $param );
		$options = $this->getOptions();
		$options[CURLOPT_POST] = true;
		$options[CURLOPT_POSTFIELDS] = $post;
		return $this->exec( $execUrl, $options, $this->_debug );
	}

	public function getError() {
		return $this->_error;
	}

	public function getInfo() {
		return $this->_info;
	}

	public function getStatus() {
		return $this->_status;
	}

	public function init() {
		isset( $this->options['debug'] ) && true === $this->options['debug'] && $this->_debug = true;
		return;
	}

}
