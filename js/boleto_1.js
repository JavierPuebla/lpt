
const boleto = {
  create : function(o){
    console.log('preparing to print',o);
  let c = '';
  c += "<div style='padding: 2cm;font-size: x-large;'><H1 align='CENTER'>BOLETO DE COMPRAVENTA</h1>";
  c += this.parrafo_1(o);
  c += "<H2 align='start'><strong>CONSIDERANDO QUE:</strong></h2>";
  c += this.parrafo_2(o);
  c += this.parrafo_3(o);
  c += "</div>" // cierra el container
  return c;
  },
  parrafo_1 :function(o){
      let vendedor = this.get_vendedor(o.expediente);
      return gen_txt = "En la ciudad de Buenos aires, a los <strong>"+o.fechaDia+" días del mes de "+o.fechaMes+" del "+o.fechaYear+"</strong> , entre "+vendedor+"(en adelante, el “VENDEDOR”) por una parte y por la otra <strong>"+o.tit_nomap+"</strong> , DNI Nro. <strong>"+o.tit_dni+"</strong>"+
      " Con domicilio en la calle <strong>"+o.tit_domic+"</strong> en la localidad de <strong> "+o.tit_localidad+"</strong> "+(o.cotit_nomap.length > 1 ?" y <strong>"+o.cotit_nomap+"</strong> , DNI Nro. <strong>"+o.cotit_dni+"</strong> Con domicilio en la calle <strong>"+o.cotit_domic+"</strong> en la localidad de <strong>"+(o.cotit_locali?o.cotit_locali:' - ')+"</strong>":"")+", actuando por propio derecho (en adelante, el “COMPRADOR”) (en adelante se denominarán en forma conjunta el VENDEDOR y el COMPRADOR como las “Partes”), y,"
    },
  get_vendedor : function(exp){
    let res = '';
    switch (exp) {
      case 'MORENO':
        res = "Cerro Rico S.A. con domicilio en Talcahuano 638 4to. F de la ciudad autónoma de buenos aires, representada en este acto por el Sr. Gregorio Prieto D.N.I. 17.671.974 en su carácter de socio Presidente de la sociedad,"
        break;
      case '178516':
        res = "Chamarrita SA con domicilio en Talcahuano 638 4to. F de la ciudad autónoma de buenos aires, representada en este acto por el Sr. Alejandro Schneider D.N.I. 23.939.620 en su carácter de apoderado de la sociedad,"
        break;
      case '178515':
        res = "Chamarrita SA con domicilio en Talcahuano 638 4to. F de la ciudad autónoma de buenos aires, representada en este acto por el Sr. Alejandro Schneider D.N.I. 23.939.620 en su carácter de apoderado de la sociedad,"
        break;
      case '178514':
        res = "Chamarrita SA con domicilio en Talcahuano 638 4to. F de la ciudad autónoma de buenos aires, representada en este acto por el Sr. Alejandro Schneider D.N.I. 23.939.620 en su carácter de apoderado de la sociedad,"
        break;
      case '178513':
        res = "Chamarrita SA con domicilio en Talcahuano 638 4to. F de la ciudad autónoma de buenos aires, representada en este acto por el Sr. Alejandro Schneider D.N.I. 23.939.620 en su carácter de apoderado de la sociedad,"
        break;
      case '178512':
        res = "Chamarrita SA con domicilio en Talcahuano 638 4to. F de la ciudad autónoma de buenos aires, representada en este acto por el Sr. Alejandro Schneider D.N.I. 23.939.620 en su carácter de apoderado de la sociedad,"
        break;

      default:
        res = "Bursch S.R.L. con domicilio en Talcahuano 638 4to. F de la ciudad autónoma de buenos aires, representada en este acto por el Sr. Alejandro Schneider D.N.I. 23.939.620 en su carácter de socio Gerente de la sociedad,"
    }
    return res;
  },
  parrafo_2 :function(o){
    let res = '';
    switch (o.expediente) {
      case 'MORENO':
        res = "- EL VENDEDOR adquirió el día 06 de Diciembre de 2016, según escritura número 481 pasada ante el escribano BACCETTI ALBERTO DAMIAN (REG: 39)  4 (cuatro) fracciones de terreno en el Partido de Moreno, Provincia de Buenos Aires. LA PRIMERA identificada como 718 \”a\”, designado como lote 17 de la fracción \“A\”, con una superficie de 78.458 M2, Nomenclatura Catastral: Circunscripción IV, Sección rural, Parcela: 718 \“a\”, Partida: 368. LA SEGUNDA identificada como 718\”d\”, designado como lote 16 de la fracción \“A\”, con una superficie de 25.428 M2, Nomenclatura Catastral: Circunscripción IV, Sección rural, Parcela: 718 \“d\”, Partida: 11.556. LA TERCERA identificada como 718\”e\”, designado como lote 15 de la fracción \“A\”, con una superficie de 21.640 M2, Nomenclatura Catastral: Circunscripción IV, Sección rural, Parcela: 718 \“e\”, Partida: 11.555. LA CUARTA identificada como 718 \”f\”, designado como lote 14 de la fracción \“A\”, con una superficie de 17.318 M2, Nomenclatura Catastral: Circunscripción IV, Sección rural, Parcela: 718 \“e\”, Partida: 11.554 (en adelante, el \“Predio\”);<br/>- El VENDEDOR desea vender al COMPRADOR la unidad demarcada en el plano adjunto como Anexo II e individualizada bajo el número <strong>"+o.cod_lote+"</strong> (en adelante, el “LOTE”); - El VENDEDOR ha destinado el Predio a su subdivisión en lotes menores conforme el plano y detalle de obras de infraestructura a realizar que se indican en el Anexo III;<br/>- El COMPRADOR está interesado en adquirir el LOTE que resulte de la subdivisión dentro del Predio conforme plano adjunto como Anexo III y destinar el mismo a la construcción de una vivienda, la cual representa la 1/121 ava parte indivisa de las fracciones;<br/>- El COMPRADOR ha requerido del VENDEDOR el otorgamiento de financiación para el pago del precio de venta del LOTE y el VENDEDOR ha aceptado ello con la condición de reajustar la cuota periódicamente, lo cual ha sido aceptado por el COMPRADOR;<br/>- En vista de lo que antecede, las Partes convienen:"

        break;
      case '156197':
        res = "- EL VENDEDOR adquirió el día 21 de Noviembre de 2011, dos fracciones de terreno cito en Avda. de los inmigrantes esquina Yapeyu del Partido de Escobar, Provincia de Buenos Aires, Nomenclatura Catastral: Circunscripción XI, Sección rural, Parcelas: 2661 y 2662, ante el escribano Ezequiel Cabuli, al folio 684 del Registro 186 de la Ciudad Autónoma de Buenos Aires y ante la escribana María Cecilia Koundukjian, Titular del registro Notarial 2000, Mat: 4852, con fecha 13 de setiembre de 2013, según escritura numero  255 la parcela 2653 (en adelante, el “Predio”);<br/>- El VENDEDOR desea vender al COMPRADOR la unidad demarcada en el plano adjunto como Anexo II e individualizada bajo el número <strong>"+o.cod_lote+"</strong> (en adelante, el “LOTE”); - El VENDEDOR ha destinado el Predio a su subdivisión en lotes menores conforme el plano y detalle de obras de infraestructura a realizar que se indican en el Anexo III;<br/>- El COMPRADOR está interesado en adquirir el LOTE que resulte de la subdivisión dentro del Predio conforme plano adjunto como Anexo III y destinar el mismo a la construcción de una vivienda, la cual representa la 1/309 ava parte indivisa de las fracciones;<br/>- El COMPRADOR ha requerido del VENDEDOR el otorgamiento de financiación para el pago del precio de venta del LOTE y el VENDEDOR ha aceptado ello con la condición de reajustar la cuota periódicamente, lo cual ha sido aceptado por el COMPRADOR;<br/>- En vista de lo que antecede, las Partes convienen:"
      break;
      case '178516':
        res = "- EL VENDEDOR es el propietario de 12 fracciones de terreno, identificados como lotes 1,2,3,4,5,6,7,8,9,10,11 y 12 del partido de Escobar, Provincia de Buenos Aires, Nomenclatura Catastral: Circunscripción IX, Seccion: E, Quinta: 33, Chacra: SS, Partidas 11.462, 11.463, 11464, 11465, 11466, 11467, 11468, 11469, 11470, 11471, 11472 y 11473, dichas parcelas cuentan con el visado de subdivisión municipal N°: 178.515/2015  de fecha 09/12/2015 (en adelante, el “Predio”);<br/>- El VENDEDOR desea vender al COMPRADOR la unidad demarcada en el plano adjunto como Anexo II e individualizada bajo el número <strong>"+o.cod_lote+"</strong> (en adelante, el “LOTE”); - El VENDEDOR ha destinado el Predio a su subdivisión en lotes menores conforme el plano y detalle de obras de infraestructura a realizar que se indican en el Anexo III;<br/>- El COMPRADOR está interesado en adquirir el LOTE que resulte de la subdivisión dentro del Predio conforme plano adjunto como Anexo III y destinar el mismo a la construcción de una vivienda, la cual representa la 1/44 ava parte indivisa de las fracciones;<br/>- El COMPRADOR ha requerido del VENDEDOR el otorgamiento de financiación para el pago del precio de venta del LOTE y el VENDEDOR ha aceptado ello con la condición de reajustar la cuota periódicamente, lo cual ha sido aceptado por el COMPRADOR;<br/>- En vista de lo que antecede, las Partes convienen:";
        break;
      case '178515':
        res = "- EL VENDEDOR es el propietario de 12 fracciones de terreno, identificados como lotes 1,2,3,4,5,6,7,8,9,10,11 y 12 del partido de Escobar, Provincia de Buenos Aires, Nomenclatura Catastral: Circunscripción IX, Seccion: E, Quinta: 33, Chacra: SS, Partidas 11.462, 11.463, 11464, 11465, 11466, 11467, 11468, 11469, 11470, 11471, 11472 y 11473, dichas parcelas cuentan con el visado de subdivisión municipal N°: 178.515/2015  de fecha 09/12/2015 (en adelante, el “Predio”);<br/>- El VENDEDOR desea vender al COMPRADOR la unidad demarcada en el plano adjunto como Anexo II e individualizada bajo el número <strong>"+o.cod_lote+"</strong> (en adelante, el “LOTE”); - El VENDEDOR ha destinado el Predio a su subdivisión en lotes menores conforme el plano y detalle de obras de infraestructura a realizar que se indican en el Anexo III;<br/>- El COMPRADOR está interesado en adquirir el LOTE que resulte de la subdivisión dentro del Predio conforme plano adjunto como Anexo III y destinar el mismo a la construcción de una vivienda, la cual representa la 1/44 ava parte indivisa de las fracciones;<br/>- El COMPRADOR ha requerido del VENDEDOR el otorgamiento de financiación para el pago del precio de venta del LOTE y el VENDEDOR ha aceptado ello con la condición de reajustar la cuota periódicamente, lo cual ha sido aceptado por el COMPRADOR;<br/>- En vista de lo que antecede, las Partes convienen:";
        break;
      case '178514':
        res = "- EL VENDEDOR es el propietario de 12 fracciones de terreno, identificados como lotes 1,2,3,4,5,6,7,8,9,10,11 y 12 del partido de Escobar, Provincia de Buenos Aires, Nomenclatura Catastral: Circunscripción IX, Seccion: E, Quinta: 36, Chacra: VV, Partidas 11.550, 11551, 11552, 11553, 11554, 11555,11556, 11557, 11558, 11559 y 11560, dichas parcelas cuentan con el visado de subdivisión municipal N°: 178.514/2015  de fecha 09/12/2015 (en adelante, el “Predio”);<br/>- El VENDEDOR desea vender al COMPRADOR la unidad demarcada en el plano adjunto como Anexo II e individualizada bajo el número <strong>"+o.cod_lote+"</strong> (en adelante, el “LOTE”); - El VENDEDOR ha destinado el Predio a su subdivisión en lotes menores conforme el plano y detalle de obras de infraestructura a realizar que se indican en el Anexo III;<br/>- El COMPRADOR está interesado en adquirir el LOTE que resulte de la subdivisión dentro del Predio conforme plano adjunto como Anexo III y destinar el mismo a la construcción de una vivienda, la cual representa la 1/56 ava parte indivisa de las fracciones;<br/>- El COMPRADOR ha requerido del VENDEDOR el otorgamiento de financiación para el pago del precio de venta del LOTE y el VENDEDOR ha aceptado ello con la condición de reajustar la cuota periódicamente, lo cual ha sido aceptado por el COMPRADOR;<br/>- En vista de lo que antecede, las Partes convienen:"
        break;
      case '178513':
        res = "- EL VENDEDOR es el propietario de 06 fracciones de terreno, identificados como lotes 1,2,3,8,9, y 10 , del partido de Escobar, Provincia de Buenos Aires, Nomenclatura Catastral: Circunscripción IX, Sección: E, Quinta: 37, Chacras: XX, Partidas 11.561, 11.562, 11.563, 11.568, 11.569 y 11.570, dichas parcelas cuentan con el visado de subdivisión municipal N°: 178.513/2015  de fecha 09/12/2015 (en adelante, el “Predio”);<br/>- El VENDEDOR desea vender al COMPRADOR la unidad demarcada en el plano adjunto como Anexo II e individualizada bajo el número <strong>"+o.cod_lote+"</strong> (en adelante, el “LOTE”); - El VENDEDOR ha destinado el Predio a su subdivisión en lotes menores conforme el plano y detalle de obras de infraestructura a realizar que se indican en el Anexo III;<br/>- El COMPRADOR está interesado en adquirir el LOTE que resulte de la subdivisión dentro del Predio conforme plano adjunto como Anexo III y destinar el mismo a la construcción de una vivienda, la cual representa la 1/30 ava parte indivisa de las fracciones;<br/>- El COMPRADOR ha requerido del VENDEDOR el otorgamiento de financiación para el pago del precio de venta del LOTE y el VENDEDOR ha aceptado ello con la condición de reajustar la cuota periódicamente, lo cual ha sido aceptado por el COMPRADOR;<br/>- En vista de lo que antecede, las Partes convienen:"
        break;
      case '178512':
        res = "- EL VENDEDOR es el propietario de 06 fracciones de terreno, identificados como lotes 1,2,3,8,7 y 6 del partido de Escobar, Provincia de Buenos Aires, Nomenclatura Catastral: Circunscripción IX, Seccion: E, Quinta: 40, Chacra: AAA, Partidas 11.649, 11650, 11651, 11652, 11653, 11654, dichas parcelas cuentan con el visado de subdivisión municipal N°: 178.512/2015  de fecha 09/12/2015 (en adelante, el “Predio”);<br/>- El VENDEDOR desea vender al COMPRADOR la unidad demarcada en el plano adjunto como Anexo II e individualizada bajo el número <strong>"+o.cod_lote+"</strong> (en adelante, el “LOTE”); - El VENDEDOR ha destinado el Predio a su subdivisión en lotes menores conforme el plano y detalle de obras de infraestructura a realizar que se indican en el Anexo III;<br/>- El COMPRADOR está interesado en adquirir el LOTE que resulte de la subdivisión dentro del Predio conforme plano adjunto como Anexo III y destinar el mismo a la construcción de una vivienda, la cual representa la 1/30 ava parte indivisa de las fracciones;<br/>- El COMPRADOR ha requerido del VENDEDOR el otorgamiento de financiación para el pago del precio de venta del LOTE y el VENDEDOR ha aceptado ello con la condición de reajustar la cuota periódicamente, lo cual ha sido aceptado por el COMPRADOR;<br/>- En vista de lo que antecede, las Partes convienen:"
        break;

      case '140777':
        res = "- EL VENDEDOR adquirió con fecha 09 de setiembre del 2011, según escritura numero 193 pasada ante la escribana María Cecilia Koundukjian, Titular del registro Notarial 2000, Mat: 4852, una fracción de terreno cito en Calle Los Tulipanes entre las calles estrada y sin nombre del partido de Escobar, Provincia de Buenos Aires, designado en el plano de mensura y división de la parcela 2621d, aprobado bajo la característica 118-21-96 que cita su título, designado como parcela 2621e, Nomenclatura Catastral: Circunscripción XI, Parcelas: 2621e, Partida 118-076646, y ante la misma escribana, con fecha 13 de setiembre de 2013, según escritura numero  255 la parcela 2621g, ambas parcelas cuentan con el visado de subdivisión municipal N°: 140.777/11 de fecha 26/05/2011, y se encuentra aprobado el plano de subdivisión en La plata bajo el número 118-0028/2015 (en adelante, el \“Predio\”);<br/>- El VENDEDOR desea vender al COMPRADOR la unidad demarcada en el plano adjunto como Anexo II e individualizada bajo el número <strong>"+o.cod_lote+"</strong> (en adelante, el “LOTE”); - El VENDEDOR ha destinado el Predio a su subdivisión en lotes menores conforme el plano y detalle de obras de infraestructura a realizar que se indican en el Anexo III;<br/>- El COMPRADOR está interesado en adquirir el LOTE que resulte de la subdivisión dentro del Predio conforme plano adjunto como Anexo III y destinar el mismo a la construcción de una vivienda, la cual representa la 1/275 ava parte indivisa de las fracciones;<br/>- El COMPRADOR ha requerido del VENDEDOR el otorgamiento de financiación para el pago del precio de venta del LOTE y el VENDEDOR ha aceptado ello con la condición de reajustar la cuota periódicamente, lo cual ha sido aceptado por el COMPRADOR;<br/>- En vista de lo que antecede, las Partes convienen:"
    }
    return res;

  },
  parrafo_3 : function(o){
    let r = "<br><strong>PRIMERA:</strong> OBJETO - COMPROMISO DE COMPRAVENTA:<br/>El COMPRADOR se compromete a comprar al VENDEDOR, y el VENDEDOR acepta vender al COMPRADOR el LOTE. El LOTE tendrá una superficie de aproximadamente<strong>"+o.metros_2+"</strong> metros cuadrados con las siguientes medidas, <strong>"+o.metros_frente+"</strong> metros de frente y <strong>"+o.metros_fondo+"</strong> metros de fondo. La venta se realizará bajo los términos y condiciones del presente.<br/><strong>SEGUNDA:</strong> PRECIO. PLAZO DE PAGO. AJUSTE. CANCELACION ANTICIPADA. LUGAR DE PAGO:<br/>2.1 El precio total y convenido por la presente compra del Lote es de <strong>$ "+ accounting.formatMoney(parseFloat(o.precio_total), '',0, '.', ',')+" (Pesos "+numeroALetras(o.precio_total)+")</strong> (en adelante, el “Precio”), el cual se ajustará y abonará según el sistema más abajo indicado.<br/>2.2 El Precio se abonará como sigue:<div style='padding-left:2cm;'>2.2.1 la suma de <strong>$  "+accounting.formatMoney((parseFloat(o.monto_ciclo1)+parseFloat(o.primer_pago_ciclo1)), '',0, '.', ',')+" (Pesos "+numeroALetras(parseFloat(o.monto_ciclo1)+parseFloat(o.primer_pago_ciclo1))+")</strong> (en adelante, el “ANTICIPO”) en <strong>"+(o.cant_ctas_ciclo1)+" ("+numeroALetras((o.cant_ctas_ciclo1))+")</strong> cuotas mensuales y consecutivas, abonándose la primera de esas cuotas por la suma de <strong>$ "+accounting.formatMoney(parseFloat(o.primer_pago_ciclo1), '',0, '.', ',')+"(Pesos "+numeroALetras(o.primer_pago_ciclo1)+".)</strong> en este acto, sirviendo el presente de suficiente recibo y carta de pago. Las restantes cuotas vencerán los días DIA 10 DEL MES SIGUIENTE A LA FIRMA DEL BOLETO o el día hábil siguiente si alguno de ellos fuera inhabil. El importe de cada una de las <strong>"+(o.cant_ctas_ciclo1-1)+" ("+numeroALetras((o.cant_ctas_ciclo1-1))+")</strong> cuotas siguientes será el resultado de dividir al saldo del ANTICIPO luego de deducidas las cuotas abonadas hasta la fecha en que se realizara el pago de una nueva cuota y reajustado el importe de ese saldo del ANTICIPO conforme el sistema previsto en el punto 2.3 cuando correspondiera por la cantidad de cuotas pendientes de pago en cada momento.<br/>2.2.2 la suma de <strong>$ "+accounting.formatMoney((parseInt(o.primer_pago_ciclo1)*parseInt(o.cant_ctas_ciclo2)), '',0, '.', ',')+"(Pesos "+numeroALetras((parseInt(o.primer_pago_ciclo1)*parseInt(o.cant_ctas_ciclo2)))+".)</strong>(en adelante, el “SALDO”) reajustado según lo previsto en el punto 2.3, a los 30 (treinta) días contados desde el pago del total del ANTICIPO. Se reconoce la facultad al COMPRADOR de optar por el pago del SALDO en hasta <strong>"+(o.cant_ctas_ciclo2)+" ("+numeroALetras((o.cant_ctas_ciclo2))+")</strong>  cuotas mensuales y consecutivas, siendo la primera exigible a los 30 (treinta) días contados desde el pago del total del ANTICIPO. A efectos de ejercer el derecho de financiar el pago del SALDO y como condición indispensable a ese efecto, el COMPRADOR deberá: (a) notificar al VENDEDOR con por lo menos 30 (treinta) días de anticipación a la fecha de pago fijada para el SALDO del ejercicio de su opción. La falta de comunicación en ese plazo será considerada como una negativa a obtener financiación alguna para el pago del SALDO, siendo exigible el total del SALDO –con más sus reajustes- en la fecha fijada a ese efecto; y (b) presentarse el dîa fijado para el pago del SALDO en el domicilio del VENDEDOR o donde el VENDEDOR le indique en el futuro a efectos de suscribir el convenio de financiación en el pago del SALDO bajo los términos y condiciones que el VENDEDOR fije a su sola discreción.</div><br/>2.3 Cada 12 (doce) meses contados a partir del día de la fecha y considerando como mes base al de <strong> "+o.fechaMes+" de "+o.fechaYear+"</strong>., se ajustará el valor del PRECIO pendiente de cancelación conforme el ajuste mayor dispuesto bajo alguno de los siguientes índices:<br/>&nbsp;&nbsp;&nbsp;&nbsp;(a) al porcentual de incremento salarial dispuesto por la paritaria del gremio para empleados de "+o.gremio+";<br/>&nbsp;&nbsp;&nbsp;&nbsp;(b) índice de precios al consumidor minorista informado por el INDEC;<br/>&nbsp;&nbsp;&nbsp;&nbsp;(c) índice de Unidad de Valor Adquisitivo (UVA) informado por el Banco Central de la República Argentina;<br/>&nbsp;&nbsp;&nbsp;&nbsp;(d) índice del Coeficiente de Estabilización de la Referencia (CER).<br/>El VENDEDOR notificará al COMPRADOR el nuevo valor de las cuotas, siendo ese el importe definitivo por pagar a partir de esa fecha y por los siguientes 12 (doce) meses. Cada cuota abonada por el COMPRADOR conforme ese sistema no estará sujeta a ulteriores reajustes retroactivos. En caso de falta de pago de alguna de esas cuotas en el tiempo fijado a ese efecto, la cuota en mora quedará sujeta a ulteriores reajustes por aplicacion de lo aquí convenido. El importe así determinado será definitivo, renunciando la COMPRADORA a invocar la teoría de la imprevisión o el mayor valor sobreviniente y a cuestionar el importe de la cuota.<br/>2.4 El VENDEDOR podrá optar por modificar el sistema de reajuste anual por otro semestral y viceversa, el cual nunca alcanzará a las cuotas reajustadas y definitivas –conforme el punto 2.3. Ese derecho se reconoce al VENDEDOR ilimitadamente y a su sola discreción.<br/>2.5 Se reconoce el derecho del COMPRADOR a anticipar el pago de cuotas, en cuyo caso, deberá cancelarse en primer lugar la más antigua en su vencimiento y así sucesivamente por orden de antigüedad. Solo se admitirán pagos de cuotas anticipadas por un importe equivalente a por lo menos 2 (dos) cuotas y no admitiéndose pagos menores, ni parciales de cuotas.<br/>2.6 Todos los pagos se realizarán en el domicilio del VENDEDOR o donde designe el VENDEDOR en el futuro.<br/>2.7 EL COMPRADOR declara y garantiza que ha estudiado minuciosamente los efectos del ajuste en la cuota y el mercado, habiéndose asesorado debidamente, renunciando en consecuencia a invocar su inaplicabilidad, ilegitimidad y/o inconstitucionalidad y/o la teoría de la imprevisión para pretender invocar su invariabilidad, todo lo cual ha sido condición del VENDEDOR para la contratación en las condiciones aquí previstas.<br/>TERCERA: CONDICIONES DE VENTA:<br/>El VENDEDOR declara, garantiza y se obliga a que esta venta se realice en base a títulos perfectos, libre de gravámenes, restricciones y/o afectaciones de cualquier tipo y con todos los impuestos, tasas y contribuciones pagas hasta la fecha de entrega de la posesión del LOTE.<br/>CUARTA: COMPROMISO DEL VENDEDOR – POSESION – IMPUESTOS, TASAS Y CONTRIBUCIONES – COMPROMISOS DEL COMPRADOR:<br/>4.1 El VENDEDOR se compromete a realizar todos los trámites y presentaciones necesarias ante las autoridades públicas, mixtas y/o privadas que corresponda para subdividir el Predio, conforme los términos del plano adjunto al presente como Anexo I.<br/>El COMPRADOR declara saber y conocer que: (a) la ubicación del LOTE y su superficie es aproximada, pudiendo las mismas modificarse, aunque no sustancialmente, pudiendo inclusive el VENDEDOR entregar al COMPRADOR un terreno en otro lugar del Predio que guarde similitud de características de tamaño con el comprometido. En caso que debiera ajustarse la superficie, en más o en menos, no se ajustará el precio proporcionalmente; y (b) el Predio se encuentra pendiente de conclusión del proceso subdivisión, conforme estado que le ha sido debidamente explicado y que ha comprendido, habiendose asesorado debidamente al respecto y los tiempos de terminación.<br/>4.2 La posesión del LOTE será entregada por el VENDEDOR al COMPRADOR al vencimiento y pago de la última cuota correspondiente al ANTICIPO y siempre que el LOTE se encontrara en condiciones de edificarse por haberse obtenido los permisos necesarios al efecto, no asumiendo el VENDEDOR compromiso alguno de obtención de los mismos en plazo alguno determinado, mas sí la realización de los actos necesarios al efecto. La negativa del COMPRADOR a recibir la posesion del LOTE, estando en condiciones de ser recibido, devengará una multa diaria equivalente al 3 % (tres por ciento) del valor de la cuota vigente al momento del incumplimiento y hasta que cesara el incumplimiento. Esa multa será exigible dentro de los 2 (dos) dias de requerido su pago.<br/>4.3 Una vez iniciada la construcción de la vivienda, la obra deberá concluir en o antes de los 12 (doce) meses, salvo problemas de lluvias, paro, falta de personal y/o falta de suministro de materiales, en cuyo caso se prorrogará el plazo de obra por el tiempo que pudiera demorar esa imposibilidad. El incumplimiento del COMPRADOR en la terminacion de la obra en el tiempo convenido de ello devengará a favor del VENDEDOR una multa diaria equivalente al 3 % (tres por ciento) del valor de la cuota vigente al momento del incumplimiento y hasta que cesara el incumplimiento. Esa multa será exigible dentro de los 2 (dos) dias de requerido su pago por el VENDEDOR. En caso que durante el transcurso de la obra que se encuentre realizando EL COMPRADOR, EL COMPRADOR incumpliera en el pago de las cuotas que integran el PRECIO en el tiempo y forma previsto al efecto, el VENDEDOR podrá exigir la suspensión de la obra que se estuviera realizando en el LOTE y ello mientras dure la mora. En caso de incumplir con la suspensión, se devengará una multa diaria a favor del VENDEDOR equivalente al 3 % (tres por ciento) del valor de la cuota vigente al momento del incumplimiento y hasta que cesara el incumplimiento exigible dentro de los 2 (dos) dias de requerido su pago.<br/>El COMPRADOR no podrá negarse a recibir el LOTE y/o a pagar las cuotas invocando la falta de conclusión de la obra de infraestructura y/o falta de aprobacion de planos y/o subdivisión y/o finales de obra.<br/>4.4 A partir del día de la fecha, el COMPRADOR deberá afrontar el pago de todos los impuestos, tasas y contribuciones y costos de mantenimiento, corte de césped y servicios que el LOTE devengue, debiendo en su caso, reembolsar al VENDEDOR cualquier suma de dinero que el VENDEDOR debiera abonar por algún concepto a cargo del COMPRADOR y ello dentro del quinto día de requerido por cualquier forma por el VENDEDOR. La falta de reembolso de esas sumas devengará a favor del VENDEDOR un interés equivalente al 6% (seis por ciento) mensual por mora.<br/>4.5 EL COMPRADOR se compromete a destinar el LOTE a vivienda y comprometiéndose a construir una vivienda en el mismo con los lineamientos constructivos que se indican en el Anexo III, declarando saber y entender que el VENDEDOR se compromete a vender el LOTE al COMPRADOR en atención a la asunción y compromiso del COMPRADOR de construcción bajo el antedicho proyecto constructivo. El seguimiento de los lineamientos no hace responsable al VENDEDOR por la obra, ni su construcción, quedando liberado de toda y cualquier responsabilidad por ello y/o por cualquier siniestro que pudiera ocurrir en la misma.<br/>4.6 El VENDEDOR se compromete a realizar en el Predio las obras de infraestructura y servicios que se indican en el Anexo I.<br/>QUINTA: GASTOS Y HONORARIOS. Todos los gastos y honorarios, sellados y demás impuestos que correspondan como consecuencia del presente serán afrontados por las partes según los usos y costumbres, al igual que los que correspondan por la escritura traslativa de dominio a su favor y los gastos y honorarios de mensura del sector que se vende, como así también todo otro que corresponda. El escribano será designado por EL VENDEDOR.<br/>SEXTA: 6.1 ESCRITURACION. La escritura traslativa del dominio del LOTE se realizará dentro de los 90 (noventa) días de pagado el total del precio de compra del LOTE y siempre que a esa fecha se encontrara el LOTE en condiciones de escriturarse por haber concluido la subdivisión del Predio y demás trámites; en caso que no se encontrara en condiciones por causas no imputables al VENDEDOR, la escrituración ocurrirá en el mayor plazo que se requiera en función de las circunstancias por falta de conclusión de la subdivisión del Predio quedando prorrogado el plazo de escrituración. EL COMPRADOR declara saber y conocer que el trámite de subdivisión y los necesarios para el otorgamiento de la escritura pública pueden demorar en atencion a requerirse aprobaciones municipales y provinciales, no asumiendo el VENDEDOR plazo alguna de finalizacion, sino a realizar cuantas presentaciones fueran necesarias para obtener las aprobaciones. El VENDEDOR se reserva el derecho de poder exigir al COMPRADOR la escrituración del LOTE antes de la cancelacion del total del PRECIO constituyéndose una hipoteca en primera grado de privilegio sobre el LOTE que garantice el pago del saldo del PRECIO impago a esa fecha, siendo en ese caso la totalidad de los gastos de la hipoteca a cargo de EL COMPRADOR.<br/>6.2 INCUMPLIMIENTO: El incumplimiento de cualquiera de las partes a cualquiera de las obligaciones asumidas en este contrato los hará incurrir en mora, la que se producirá de pleno derecho y por el vencimiento de los términos, sin que se requiera citación judicial o extrajudicial previa de ninguna naturaleza. Al efecto, si la mora y/o incumplimiento fuese imputable al COMPRADOR, será facultad alternativa del VENDEDOR proceder de conformidad con cualquiera de los siguientes procedimientos: a) En caso de falta pago de 2 (dos) cuotas consecutivas o 3 (tres) alternadas durante la vigencia del contrato, aún luego que hubiera tomado posesión del Lote, RESOLVER la presente operación, sin necesidad de intimacion alguna previa, en cuyo caso conservará el VENDEDOR como total indemnización el 100% (cien por ciento) del importe percibido del COMPRADOR hasta esa fecha si el COMPRADOR hubiera abonado un máximo de 12 cuotas, el 90% (noventa por ciento) del importe percibido del COMPRADOR hasta esa fecha si el COMPRADOR hubiera abonado de 13 a 18 cuotas, el 80% (ochenta por ciento) del importe percibido del COMPRADOR hasta esa fecha si el COMPRADOR hubiera abonado de 19 a 24 cuotas, el 70% (setenta por ciento) del importe percibido del COMPRADOR hasta esa fecha si el COMPRADOR hubiera abonado de 25 a 30 cuotas, el 60% (sesenta por ciento) del importe percibido del COMPRADOR hasta esa fecha si el COMPRADOR hubiera abonado más de 31 cuotas, debiendo reintegrar el VENDEDOR al COMPRADOR cualquier suma en exceso de ese importe dentro del décimo día posterior a la comunicación de la resolución del contrato y siempre que le fuera restituida la posesión del LOTE al VENDEDOR, sin interés, ni actualización alguna; en este caso, si el COMPRADOR se encontrare en posesión del LOTE, deberá desalojarlo dentro de los 10 (diez) días de resuelto el contrato y devengándose una multa diaria a favor del VENDEDOR equivalente a U$D 30 (dólares estadounidenses TREINTA) en caso de incumplimiento y hasta el efectivo desalojo, la que podrá el VENDEDOR compensar contra el importe por reembolsar al COMPRADOR o reclamarlo del COMPRADOR, a solo criterio del VENDEDOR, devengando la falta de pago de la multa un interés del 2% (dos por ciento) mensual; o b) RECLAMAR el cumplimiento del presente contrato, en cuyo caso, a requerimiento del VENDEDOR, se producirá la caducidad de todos los plazos acordados ante la sola falta de pago de una cuota en tiempo, siendo exigible el total del PRECIO adeudado a esa fecha, todo ello con más un interés compensatorio y punitorio equivalente al 6% (seis por ciento) mensual calculado sobre las sumas impagas hasta esa fecha, que se devengará desde la mora hasta el momento del cumplimiento y que la parte COMPRADORA deberá hacer efectivo en dicho momento. En este último caso, podá el VENDEDOR reajustar el importe de acuerdo a lo previsto en la cláusula Segunda, punto 2.3. Si la mora y/o incumplimiento fuese imputable a EL VENDEDOR, será facultad alternativa del COMPRADOR proceder de conformidad con cualquiera de los siguientes procedimientos: a) RESOLVER la presente operación, a cuyos efectos deberá previamente intimar al VENDEDOR al cumplimiento en un plazo de 30 (treinta) días, en cuyo caso el VENDEDOR deberá reintegrar al COMPRADOR los importes recibidos hasta esa fecha del COMPRADOR con más con más el equivalente al 25 % (veinticinco por ciento) de las sumas percibidas en concepto de total indemnización, pagaderos en la misma moneda que el de la sumas entregadas hasta ese momento, importe que lo abonará a los 30 (treinta) días de requerido el pago por el COMPRADOR; o b) RECLAMAR el cumplimiento del presente contrato, todo ello con más una multa mensual en concepto de indemnización equivalente al 1% (uno por ciento) de las sumas abonadas hasta esa fecha por EL COMPRADOR a EL VENDEDOR, que se devengará desde la mora hasta el momento del cumplimiento y que EL VENDEDOR deberá abonar dentro de los 30 días de requerido su pago.<br/>SEPTIMA: PLANOS - SUBDIVISION.<br/>No será condición para la recepción de la posesión del LOTE que los planos de subdivisión del Predio se encuentren aprobados por las autoridades correspondientes, ni que el LOTE se encuentre dividido municipal y/o catastralmente, los cuales deberán encontrarse en ese estado al momento de celebración de la escritura traslativa de dominio.<br/>OCTAVA:8.1 JURISDICCION Y DOMICILIO. Las partes constituyen domicilio especial en los arriba indicados, y electrónico en <strong>administracion@lotesparatodos.com.ar </strong>el VENDEDOR y <strong>"+(o.tit_email?o.tit_email:'sin-email ') + '</strong> y <strong>'+(o.cotit_email?o.cotit_email:' ') +"</strong> el COMPRADOR,conforme lo dispuesto en el art. 75 del CCyCom. (texto según ley 27551), donde serán válidas todas las intimaciones o notificaciones que se practiquen. La parte que deba efectuar la noificación podrá hacerlo altermativamente al domicilio postal o al domicilio electrónico constituido por cada parte, a su solo criterio y opción. Se pacta, en caso de controversia la jurisdicción de los Tribunales Nacionales con asiento en la Ciudad de Buenos Aires, con renuncia a todo otro fuero o jurisdicción que pudiera corresponder.<br/>8.2 SELLOS. El impuesto de sellos que devengue el presente será afrontado por las Partes por mitades.<br/>8.3 CESION: Se deja constancia que el VENDEDOR se encontrará facultado a transferir el presente crédito, conjuntamente con todos o alguno/s de los restantes créditos provenientes de la venta de unidades resultantes de la división del Predio a distintos compradores que integren una cartera de créditos y todo ello en los términos del art. 70 de la ley 24.441. Se establece particularmente que el COMPRADOR libera al VENDEDOR de notificar la cesión del crédito, conforme así lo permite el art. 72 de la ley 24.441, la cual quedará perfeccionada desde la fecha de firma del documento de cesión.<br/>8.4 INSTRANFERIBILIDAD: El COMPRADOR no podrá ceder y/o transferir bajo forma alguna el presente boleto.<br/>En prueba de conformidad, se firma el presente en dos ejemplares de un mismo tenor y un solo efecto.<br/><br/><br/><br/><br/><br/>Firma Vendedor: ______________		Firma Comprador: _________________";
    return r;
  }
}
