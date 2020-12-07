<?php
class IMDBListBridge extends BridgeAbstract {
		
	const MAINTAINER="ty";
	const NAME = "IMDB Lists";
	const URI = "https://www.imdb.com";
	const DESCRIPTION = "returns w/e list from imdb";
	const CACHE_TIMEOUT = 2;
//	const PARAMETERS = array( array(
//		'ID' => array(
//			'name' =>'ID',
//			'exampleValue'=>'ls12345',
//		),
//	));

	public function collectData() {

		//$listID = $this->getInput('ID');
		$url = self::URI .
			'/list/' .
			'ls016522954'.
			'/' .
			'?st_dt=&mode=detail&page=1&ref_=ttls_vm_dtl&title_type=movie&sort=list_order,asc';

		$item = array();
		
		$html = getSimpleHTMLDOM(
			$url) or returnServerError('Could request IMDB');

		if($html->find('div.lister-list',0)==false){
			returnServerError('List not found');
		}

		foreach($html->find('div.lister-item') as $ele){
			$item = array();

			$item['title'] = $ele->find('a',1)->plaintext . $ele->find('span',1)->plaintext;
			$item['uri'] = str_replace('?ref_=ttls_li_i','',self::URI.$ele->find('a',0)->href);
			$item['content'] = str_replace('https://www.imdb.com     ','',self::URI.$ele->find('p',1)->plaintext);
			$item['rating'] = $ele->find('p',0)->find('span',0)->innertext;
			$item['runtime'] = $ele->find('p',0)->find('span',2)->innertext;

			if(isset($item['title'])){
				$this->items[] = $item;
			}
			
		}
	}

}


