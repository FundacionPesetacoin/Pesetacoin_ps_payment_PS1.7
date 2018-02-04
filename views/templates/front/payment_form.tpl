{assign currency Currency::getDefaultCurrency()->sign}
{assign currency_code Currency::getDefaultCurrency()->iso_code}

	
<p>{l s='Recuerde que debe realizar el pago de ' mod='pesetacoin_ps_payment'} {$importePtc} PTC ({$importe} {$currency})
{l s='a la siguiente dirección de PesetaCoin :' mod='pesetacoin_ps_payment'} {$direccion}</p>



<form action="{$action}" id="payment-form" style="display:none">

  <p>
    <label>{l s='direccion'}</label>
    <input type="text" size="60" autocomplete="off" name="direccion-pesetacoin" value="{$direccion}">
  </p>
  
  <p>
    <label>{l s='importe'}</label>
    <input type="text" size="20" autocomplete="off" name="importe-compra" value="{$importe}">
  </p>
  
  <p>
    <label>{l s='importe pesetacoin'}</label>
    <input type="text" size="20" autocomplete="off" name="importe-pesetacoin" value="{$importePtc}">
  </p>
  

  
</form>
