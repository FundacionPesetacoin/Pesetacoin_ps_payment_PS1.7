<?php

/**
 * @since 1.5.0
 */
class Pesetacoin_ps_paymentValidationModuleFrontController extends ModuleFrontController
{
    /**
     * @see FrontController::postProcess()
     */
    public function postProcess()
    {
        $cart = $this->context->cart;
        if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 || $cart->id_address_invoice == 0 || !$this->module->active) {
            Tools::redirect('index.php?controller=order&step=1');
        }

        // Check that this payment option is still available in case the customer changed his address just before the end of the checkout process
        $authorized = false;
        foreach (Module::getPaymentModules() as $module) {
            if ($module['name'] == 'pesetacoin_ps_payment') {
                $authorized = true;
                break;
            }
        }

        if (!$authorized) {
            die($this->module->l('El método de pago no está disponible.', 'validation'));
        }


        $customer = new Customer($cart->id_customer);
        if (!Validate::isLoadedObject($customer))
             Tools::redirect('index.php?controller=order&step=1');

        $currency = $this->context->currency;
        $total = (float)$cart->getOrderTotal(true, Cart::BOTH);
		
		global $currency;
		$my_currency_iso_code = $currency->iso_code;
		$my_currency_sign = $currency->sign;

		

		
		$token = Configuration::get('PTC_PAYMENT_DIR_PAGO');
		$sql = "UPDATE PREFIX_pesetacoin_ps_payment SET estado_ptc=1, id_pedido_ptc='{$cart->id}' WHERE token_ptc='{$token}'";
		$mysql = $this->prepareSql($sql);
		Db::getInstance()->execute($mysql);	

		$mailVars = array( 
			'{direccion_ptc}' => $token,
			'{importe_ptc}' => Configuration::get('PTC_PAYMENT_IMPORTE'),
			'{importe_ptc_ptc}' => Configuration::get('PTC_PAYMENT_IMPORTE_PTC'),
			'{currency_iso_code_ptc}' => $my_currency_iso_code,
			'{currency_sign_ptc}' => $my_currency_sign
		);
		
	    $this->module->validateOrder($cart->id, (int)Configuration::get('PTC_PAYMENT_ID_ORDER_STATE'), $total, $this->module->displayName, NULL, $mailVars, (int)$currency->id, false, $customer->secure_key);
		
		Configuration::deleteByName('PTC_PAYMENT_IMPORTE');
		Configuration::deleteByName('PTC_PAYMENT_IMPORTE_PTC');
	
		// redireccion a pagina de respuesta típica	
		Tools::redirect('index.php?controller=order-confirmation&id_cart='.$cart->id.'&id_module='.$this->module->id.'&id_order='.$this->module->currentOrder.'&key='.$customer->secure_key);
		 
		// redireccion a pagina de respuesta de pesetacoin
		// Configuration::updateValue('PTC_PAYMENT_DIR_ESTADO', 'Asociada a pedido '.$order );
		//$this->context->smarty->assign([
        //    'params' => $_REQUEST,
		//	'id' => $order,
        //]);
		//$this->setTemplate('module:pesetacoin_ps_payment/views/templates/front/payment_return.tpl');
		
		
		
		
		
		
    }
	
	
	
	
	public function prepareSql($sql)
	{
	  // Get install SQL file content
	  $sql_content = $sql;

	  // Replace prefix and store SQL command in array
	  $sql_content = str_replace('PREFIX_', _DB_PREFIX_, $sql_content);
	  
	  
	  return $sql_content;
	}
	
	public function loadSQLFile($sql_file)
	{
	  // Get install SQL file content
	  $sql_content = file_get_contents($sql_file);

	  // Replace prefix and store SQL command in array
	  $sql_content = str_replace('PREFIX_', _DB_PREFIX_, $sql_content);
	  $sql_requests = preg_split("/;\s*[\r\n]+/", $sql_content);

	  // Execute each SQL statement
	  $result = true;
	  foreach($sql_requests as $request)
	  if (!empty($request))
		$result &= Db::getInstance()->execute(trim($request));

	  // Return result
	  return $result;
	}
	
	
	
}
