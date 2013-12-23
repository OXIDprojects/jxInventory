<?php
class order_package_custom extends order_package_custom_parent
{
	public function render () {
            $ret = parent::render();
            if($ret == 'order_package.tpl') {
                return 'order_package_custom.tpl';
            } else {
                return $ret;
            }

        }
}