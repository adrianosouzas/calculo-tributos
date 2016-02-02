<?php
class RssBO {

	/**
	 * Atualiza as noticias com os RSS cadastrados
	 */
	public static function updateRss() {
		$rssModel = new RssDbTable();
		$noticiaModel = new NoticiaDbTable();
		$mensagem = '';
		$rssArray = $rssModel->fetchAll(
			$rssModel->select()->from('rss', array('id', 'url'))->where('ativo = ?', 1)
		);

		for ($i = 0; $i < $rssArray->count(); $i++) {
			$rss = $rssArray->getRow($i);
			
			if (Zend_Uri::check($rss->url)) {
				try {
					$feed = new Zend_Feed_Rss($rss->url);

					foreach ($feed as $item) {
						$data = $item->pubDate();
						
						if (!empty($data)) {
							$noticiaModel->getAdapter()->beginTransaction();

							$data = str_replace('  ', ' ', $data);
							if (       preg_match('/^([A-Za-z]{3})\, ([0-9]{1,2}) ([A-Za-z]{3}) ([0-9]{4}) ([0-9]{1,2})\:([0-9]{1,2})\:([0-9]{1,2})( \-([0-9]{4}))?$/', $data)) {
								$data = new Zend_Date($data, Zend_Date::RSS);
							} else if (preg_match('/^([A-Za-z]{3})\, ([0-9]{1,2}) ([A-Za-z]{3}) ([0-9]{4}) ([0-9]{1,2})\:([0-9]{1,2})\:([0-9]{1,2})( \+([0-9]{4}))?$/', $data)) {
								$data = new Zend_Date(preg_replace('/^([A-Za-z]{3})\, ([0-9]{1,2}) ([A-Za-z]{3}) ([0-9]{4}) ([0-9]{1,2})\:([0-9]{1,2})\:([0-9]{1,2})( \+([0-9]{4}))?$/', '$1, $2 $3 $4 $5:$6:$7$8', $data), Zend_Date::RSS);
							} else if (preg_match('/^([A-Za-z]{3})\, ([0-9]{1,2}) ([A-Za-z]{3}) ([0-9]{4}) ([0-9]{1,2})\:([0-9]{1,2})( GMT)?$/', $data)) {
								$data = new Zend_Date(preg_replace('/^([A-Za-z]{3})\, ([0-9]{1,2}) ([A-Za-z]{3}) ([0-9]{4}) ([0-9]{1,2})\:([0-9]{1,2})( GMT)?$/', '$1, $2 $3 $4 $5:$6:00$7', $data), Zend_Date::RSS);
							} else if (preg_match('/^([A-Za-z]{3})\, ([0-9]{1,2}) ([A-Za-z]{3}) ([0-9]{4}) ([0-9]{1,2})\:([0-9]{1,2})\:([0-9]{1,2})( GMT)?$/', $data)) {
								$data = new Zend_Date(preg_replace('/^([A-Za-z]{3})\, ([0-9]{1,2}) ([A-Za-z]{3}) ([0-9]{4}) ([0-9]{1,2})\:([0-9]{1,2})\:([0-9]{1,2})( GMT)?$/', '$1, $2 $3 $4 $5:$6:$7$8', $data), Zend_Date::RSS);
							} else {
								$noticiaModel->getAdapter()->rollBack();
								continue;
							}
							
							try {
								if ($data->compareDate(new Zend_Date()) == 0) {
									$data = $data->toString('Y-m-d H:i:s', 'php');

									$noticiaModel->insert(array(
										'data'		=> $data,
										'chave'		=> ChaveBusiness::chave($item->title()),
										'nome'		=> $item->title(),
										'descricao'	=> strip_tags($item->description()),
										'url'		=> $item->link(),
										'rss_id'	=> $rss->id
									));

									$noticiaModel->getAdapter()->commit();
								} else {
									$noticiaModel->getAdapter()->rollBack();
								}
							} catch (Exception $e) {
								$mensagem .= 'Oops... ocorreu um erro ao atualizar RSS!<br />
			                	URL: '.$rss->url.'<br />
			                	Data: '.date('d/m/Y H:i:s').'<br /><br />
								<b>'.$e->getMessage().'</b><br />
			                	<pre>'.$e->getTraceAsString().'</pre>';
			
								$noticiaModel->getAdapter()->rollBack();
							}
						}
					}
				} catch (Exception $e) {
					$mensagem .= 'Oops... ocorreu um erro ao atualizar RSS!<br />
                	URL: '.$rss->url.'<br />
                	Data: '.date('d/m/Y H:i:s').'<br /><br />
					<b>'.$e->getMessage().'</b><br />
                	<pre>'.$e->getTraceAsString().'</pre>';
				}

				if(!empty($mensagem)) {
					print_r($mensagem);
				}
			}
		}
	}

}