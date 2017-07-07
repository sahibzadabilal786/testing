<?php
class TM_Theme_Helper_Data extends Mage_Core_Helper_Abstract
{
    
    
    
    public function getNewLabel($product)
	{

		$html = '';
		$isNew = false;
		$isNew = $this->isNew($product);
		if ($isNew == true)

		{

			$html .= '<div class="text-center caption hover-circle">' . $this->__('New') . '</div>';

		}
		
		return $html;

	}
    
    
    public function getSaleLabel($product)
	{

		$html = '';
		$isSale = false;
		$isSale = $this->isOnSale($product);

		if ($isSale == true){
			$html .= '<div class="text-center caption hover-circle">' . $this->__('Sale') . '</div>';
		}
		
		return $html;

	}
    
    
    
    public function getExclusiveLabel($product)
	{

		$html = '';
		$isEXL = false;
		$isEXL = $this->isExl($product);
		if ($isEXL == true)

		{

			$html .= '<div class="text-center caption hover-circle">' . $this->__('Exclusive') . '</div>';

		}
		
		return $html;

	}
    
    public function isExl($product)
	{
		return $product->getTmIsExclusive();
	}
    
    
	/**
	 * Check if "new" label is enabled and if product is marked as "new"
	 *
	 * @return  bool
	 */
	public function isNew($product)
	{
		return $this->_nowIsBetween($product->getData('news_from_date'), $product->getData('news_to_date'));
	}
	
	/**
	 * Check if "sale" label is enabled and if product has special price
	 *
	 * @return  bool
	 */
	public function isOnSale($product)
	{
		$specialPrice = number_format($product->getSpecialPrice(), 2);
		$regularPrice = number_format($product->getPrice(), 2);
		
		if ($specialPrice != $regularPrice)
			return $this->_nowIsBetween($product->getData('special_from_date'), $product->getData('special_to_date'));
		else
			return false;
	}
	
	protected function _nowIsBetween($fromDate, $toDate)
	{
		if ($fromDate)
		{
			$fromDate = strtotime($fromDate);
			$toDate = strtotime($toDate);
			$now = strtotime(Mage::app()->getLocale()->date()->setTime('00:00:00')->toString(Varien_Date::DATETIME_INTERNAL_FORMAT));
			
			if ($toDate)
			{
				if ($fromDate <= $now && $now <= $toDate)
					return true;
			}
			else
			{
				if ($fromDate <= $now)
					return true;
			}
		}
		
		return false;
	}

	public function getImg($product, $w, $h, $imgVersion='image', $file=NULL,$zoomImage=false)
	{
		$url = '';
		if ($h <= 0)
		{

			$url = Mage::helper('catalog/image')
				->init($product, $imgVersion, $file)
				->constrainOnly(true)
				->keepAspectRatio(true)
				->keepFrame(false)
				//->setQuality(90)
				->resize($w);
			$siteurl = Mage::getBaseUrl();
			//$url_u = Mage::getUrl();
			//$r = str_replace($siteurl.'media/','',$url);
			$r = str_replace($siteurl,'',$url);
			$realPath = Mage::getBaseDir().DS.$r;
			$image_size = getimagesize($realPath);

			//Mage::log($siteurl,null,'base_url.log');
			//Mage::log(zend_debug::dump($url),null,'url.log');
			if(($image_size[0] < 647  || $image_size[1] < 647) && $imgVersion[1] < Mage::getStoreConfig('cloudzoom/images/tm_main_height')  && $zoomImage==true){
				$oneXpicUrl =  Mage::getBaseUrl('media').'catalog/1x/1x1.jpg';
				$oneXpicBasePath = Mage::getBaseDir('media').DS.'catalog'.DS.'1x'.DS.'1x1.jpg';
				//exit;
				if(is_file($oneXpicBasePath)){

					$url = $oneXpicUrl;

				}else{
					$url = Mage::helper('catalog/image')
						->init($product, $imgVersion, $file)
						//->constrainOnly(true)
						//->keepAspectRatio(true)
						->keepFrame(false)
						//->setQuality(90)
						->resize(1);
					//echo $url;exit;
				}
			}
		}
		else
		{
			$url = Mage::helper('catalog/image')
				->init($product, $imgVersion, $file)
				->resize($w, $h);
		}
		return $url;
	}
}
