<body>
<div class="bs-component">
    <div class="container">
      

      <form method="POST" action="https://secure.cobroinmediato.tech/vads-payment/">
        <input type="hidden" name="vads_action_mode" value="INTERACTIVE" />
        <input type="hidden" name="vads_amount" value=<?php echo $monto ?> />         
        <input type="hidden" name="vads_ctx_mode" value="TEST" />
        <input type="hidden" name="vads_currency" value="032" />
        <input type="hidden" name="vads_language" value="es" />
        
        <input type="hidden" name="vads_page_action" value="PAYMENT" />
        <input type="hidden" name="vads_payment_config" value="SINGLE" />
        <input type="hidden" name="vads_site_id" value="86342911" />
        <input type="hidden" name="vads_trans_date" value=<?php echo $time ?>  />
        <input type="hidden" name="vads_trans_id" value="012233" />
        <input type="hidden" name="vads_version" value="V2" />
        <input type="hidden" name="signature" value=<?php echo $signature ?>  />
        <input type="submit" name="pagar" value="Pagar"/>
      </form>  
      
    </div> <!-- /container -->
</div>



</body></html>
