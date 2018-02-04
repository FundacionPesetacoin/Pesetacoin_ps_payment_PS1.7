{extends "$layout"}

{block name="content"}
  <section>
    <p>{l s='Gracias por pagar en PesetaCoin.' mod='pesetacoin_ps_payment'}</p>
    <p>{l s='Aqui están los datos:' mod='pesetacoin_ps_payment'}</p>
    <ul>
      {foreach from=$params key=name item=value}
        <li>{$name}: {$value}</li>
      {/foreach}
    </ul>
    <p>{l s="Cuando hayamos recibido el pago, comenzaremos a tramitar su pedido." mod='pesetacoin_ps_payment'}</p>
  </section>
{/block}