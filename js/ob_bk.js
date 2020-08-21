

//****** 28 julio 2020
//**** IMPRESION DE BOLETO DE COMPRA
//************************************************
const boleto = {
  create : function(o){
    console.log('preparing to print',o);
  let c = '';
  c += "<div style='padding: 2cm;font-size: x-large;'><H1 align='CENTER' class='font-weight-bold'>BOLETO DE COMPRAVENTA</h1>";
  c += this.parrafo_1(o);
  c += "<H2 align='start' class='font-weight-bold'>CONSIDERANDO QUE:</h2>";
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



// *************************************************************************
// ***  24/06/2020
// *** CONSTRUYE UN DROPDOWN CON LOS DATOS EN TOP.SELECTS.[LABEL DEL ELEMENTO VISUAL DEFINIDO POR STRUCT]
// *** RECIBE LOS DATOS POR V Y LA EXTRADATA POR XTR
// ************************************************************************
const dropdown = {
  create:function(v){
		const o = Object.create(this);
		return o.set(v);
	},
	set: function(v){
    // log('val',v)
    // HTML BUTTON
    let curr_select = 0;
    let c = "<div class=\"dropdown\" ><div class=\"btn-group dropright\" >";
    c += "<button id=\'" + v.row.id + "\' ";
    c += "type=\"button\" class=\""+(v.hasOwnProperty('style')?v.style:'btn-dropdown')+" dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">";
    c += v.row.value;
    c += "</button><div class=\"dropdown-menu\" aria-labelledby=\"dLabel\">";
    // HTML ITEMS DEL DROPDOWN
    if(TOP.permisos < 2){
      curr_select = TOP.selects[v.row.label];
     for (let i = 0; i < curr_select.length ; i++){
       c +="<a class=\"dropdown-item\" onClick=dropDownUpdate('"+v.row.id+"','"+encodeURIComponent(curr_select[i].label) +"',"+JSON.stringify({'method':v.updateMethod,'pcle_id':v.row.id,'parent_id':v.row.atom_id,'sending':true})+") >"+curr_select[i].label+"</a>";
     }
    }
    c +="</div></div></div>";
    return c;
	},
}




// CREA UN BOT DE BACK
var back_button = {
	create : function(){
		let x = "<div class=\"col d-flex justify-content-start pt-2 \">";
		x += "<button type=\"button\" onClick=front_call({'method':'back'}) class=\"btn-success\">";
		x += "<i class=\"material-icons \">arrow_back_ios</i>";
		x += "</button>"
		x += "</div>";
		return x;
	}
}


// CREA UN TOAST DE SELECCION DE FILTRO
var f_toast = {
	create : function(v,ix){
		console.log('toast',v)
		let x = "<div class='toast show' role='alert' aria-live='assertive' aria-atomic='true'>";
		x += "<div class='toast-header'><strong class='mr-auto'>"+v+"</strong>"
		x += "<button type='button'onClick=\'ftoast_remove("+ix+")\' class='ml-2 mb-1 close' data-dismiss='toast' aria-label='Close'>";
		x += "<span aria-hidden='true'>&times;</span></button>";
		x += "</div></div>";
		return x;
	}
}

var filter = {
	create:function(v){
	let f = Object.keys(v).map(x=>{
		let t = "";
		// CANTIDADES Y MONTOS INPUTS
		if(v[x].filter_type === 'num_range'){
				// RANGE DE CANTIDADES
				t += "<ul class=\"list-group list-group-flush\" >";
				t +="<li class=\'list-group-item jp-f_cat\'><div class='btn jp-smbtn' onClick=\"cf1(\'"+v[x].label+"\',\'"+v[x].title+"\',\'"+v[x].title+"\')\">";
				t += v[x].title+"</div></li>";
				t += "<li class=\'list-group-item\'>"
				t += "<input type='number' max=1000000 min=0 class='form-control form-control-sm m-2' placeholder=\'desde\' id=\'"+v[x].label+"_range_in\'>";
				t += "<input type='number' max=1000000 min=0 class='form-control form-control-sm m-2' placeholder=\'hasta\' id=\'"+v[x].label+"_range_out\'>";
				t += "<div class='btn jp-smbtn' onClick=\"cf1(\'"+v[x].label+"\',\'"+v[x].title+"\',\'"+v[x].title+"\')\"><i class=\"material-icons \">input</i></div>"
				t += "</li>"
				t += "</ul>"
		}
		// SELECTORES FECHA DESDE Y HASTA
		if (v[x].filter_type === 'date_range' ) {
			// RANGE DE CANTIDADES
			t += "<ul class=\"list-group list-group-flush\" >";
			t +="<li class=\'list-group-item jp-f_cat\'><div class='btn jp-smbtn' onClick=\"cf1(\'"+v[x].label+"\',\'"+v[x].title+"\',\'"+v[x].title+"\')\">";
			t += v[x].title+"</div></li>";
			t += "<li class=\'list-group-item\'>";
			t += "<input type=\'text\' class=\"form-control m-2\" id=\""+v[x].label+"_date_in\" placeholder=\"Desde\" readonly='readonly'/>";
			t += "<script type=\"text/javascript\">$(function () { $('#"+v[x].label+"_date_in').datetimepicker({ locale: 'es', allowInputToggle: true, format: 'DD/MM/YYYY',showClear: true, showClose: true, ignoreReadonly: true }); });</script>"
			t += "<input type=\'text\' class=\"form-control m-2\" id=\""+v[x].label+"_date_out\" placeholder=\"Hasta\" readonly='readonly'/>";
			t += "<script type=\"text/javascript\">$(function () { $('#"+v[x].label+"_date_out').datetimepicker({ locale: 'es', allowInputToggle: true, format: 'DD/MM/YYYY',showClear: true, showClose: true, ignoreReadonly: true }); });</script>"
			t +="<div class='btn jp-smbtn' onClick=\"cf1(\'"+v[x].label+"\',\'"+v[x].title+"\',\'"+v[x].title+"\')\"><i class=\"material-icons \">input</i></div>"
			t += "</li>"
			t += "</ul>"
		}
		// COLAPSABLES DE SUBCATEGORIAS
		if (v[x].filter_type === 'item' ){
			t += filter_items_collapse.create(v[x]);
		}
		return t;
	}).join('');
	return f;
	}
}
// CREA UN BOX CON LOS LINKS DE FILTRADO
var filter_items_collapse = {
	create:function(v){
		t = "<ul class=\"list-group list-group-flush\" id=\'"+v.title+"\'>";
		// TITLE
		t +="<li class=\'list-group-item jp-f_cat d-flex justify-content-between\'>";
		t +="<div class=\'btn jp-smbtn d-flex\' onClick=\"cf1(\'"+v.label+"\',\'"+v.title+"\',\'"+v.title+"\')\">"+v.title+"</div>";
		t +="<div class=\'btn jp-smbtn d-flex\' data-toggle=\'collapse\' data-target=\'#lg_"+v.label+"\' aria-expanded=\'true\' aria-controls=\'lg_"+v.label+"\'><i class=\'material-icons\'>more_vert</i></div>";
		t +="</li>";
		// CONTENIDO DE COL
		t +="<div class=\'collapse\' id=\'lg_"+v.label+"\' >";

		if(v.count >0){
			t += v.cnt.map(i=>{return "<li class=\'list-group-item\'><div class='btn jp-smbtn' onClick=\"cf1(\'"+v.label+"\',\'"+v.title+"\',\'"+(i.name?i.name:i.value)+"\')\">"+ (i.name?i.name:i.value) +"</div></li>" }).join('');
		}
		t += "</div></ul>"
		return t;
	}
}

// CREA UN BOX CON LOS LINKS DE FILTRADO
var filter_back = {
	create:function(v){
	let f = Object.keys(v).map(x=>{
		let t = "<div id=\'ftoast_cntnr\' class=\'row d-flex flex-wrap justify-content-start\'></div>"
		t += "<ul class=\"list-group list-group-flush\" id=\'"+v[x].title+"\'>";
		// CATEGORIA
			t +="<li class=\'list-group-item jp-f_cat\'><div class='btn jp-smbtn' onClick=\"cf1(\'"+v[x].label+"\',\'"+v[x].title+"\',\'"+v[x].title+"\')\">"+v[x].title+"</div></li>";
		// SUB CATS
		if(v[x].count >0){
			t += v[x].cnt.map(i=>{return "<li class=\'list-group-item\'><div class='btn jp-smbtn' onClick=\"cf1(\'"+v[x].label+"\',\'"+v[x].title+"\',\'"+i+"\')\">"+ i +"</div></li>" }).join('');
		}
		t += "</ul>"
		return t;
	}).join('');
	return f;
	}
}

// TABLA DE FILTRO DE DATOS
var f_tbl = {
	create:function(id,v){
		// class='table table-hover table-bordered table-sm'
		let r =  "<table id='"+id+"'class='table table-hover table-bordered table-sm	nowrap' width='100%' >";
		r += "<thead><tr>";
		r += v.tbl_head.map(i=>{return "<th class=\'text-center\'>"+i.title+"</th>"}).join('');
		if(TOP.actions_col_index > -1){r += "<th class=\'text-center\'>Ver</th>";}
		r +="</tr></thead>";
		r +="<tbody>";
		if(v.tbl_data && Array.isArray(v.tbl_data)){
			r += v.tbl_data.map(row => {
					let row_content = v.tbl_head.map(ci => {
						// looping columns
						let crr_id = 0;
						if(row.hasOwnProperty('elements_id')){crr_id = row.elements_id;}
						else if (row.hasOwnProperty('id')){crr_id = row.id;}
						return data_format_hook(crr_id,ci,row[ci.label]);
					}).join('');
					let row_actions ="";
					if(TOP.actions_col_index > -1){
						row_actions =  "<td><button type=\"button\" class=\"btn jp-smbtn p-0\" onClick=front_call({method:'get_elements',sending:true,data:{elm_id:"+row['elements_id']+"}})><i class=\"material-icons p-0\">launch</i></button></td>";
					}
					return "<tr>"+row_content+row_actions+"</tr>"; //
				}).join('');
		}
		r += "</tbody></table>";
		return r
	}
}

// RECIBE UN PCLE Y LO VUELVE EDITABLE  (ON CHANGE LLAMA A SU CALLER PIDIENDO UPDATE)
var mk_editable = {
	_data:{},
	_screen:{},
	create:function (val){
		var obj = Object.create(this);
		obj.set(val);
		return obj._screen;
	},
	set: function(v){
		this._data = v;
		var parent_id = (v.hasOwnProperty('parent_id')?v.parent_id:0);
		v.type = 'text';
		v.method = 'update_edi';
		console.log('creating editable',v)
    // var isdate = v.value.match(/\d+\/\d+\/\d+/);
    // tengo que revisar el modo date
    // if(isdate != null){
    	// v.value = fx_date_to_dmy(v.value);
        // v.type = 'date';
    // }
    if(!isNaN(v.value)){
    	v.value = parseFloat(v.value);
    	v.type = 'number';
    }
    var r = "\<div class=\"form-group form-inline \">";
    if (v.hasOwnProperty('title') && v.title != ''){
    	r += "<label class=\"col-form-label\" for=\"edi_"+v.id+"\">"+v.title+"</label>";
    }
    r+= "<input type=\""+v.type+"\" class=\"form-control\" id=\"edi_"+v.id+"\" ";
    r+= (TOP.permisos >= 10?"readonly ":"");
    r+= (v.readonly == true ?"readonly ":"");

    r+= "value=\""+v.value+"\"  ";
    r+= (v.method == 'update_edi' && v.value == 0  ? "disabled=\"\"":"")
    r+= (v.method == 'update_edi'? "onChange=front_call({method:\""+v.method+"\",data:{\'id\':\""+v.id+"\"}}) ":"");
    r+= (v.method == 'update_edi'? "onblur=front_call({method:\""+v.method+"\",data:{\'id\':\""+v.id+"\"}}) ":"");
    r+= (v.method == 'update_edi'?"style=\'width: 7em;\'":'');
    r+= "onChange=front_call({method:\""+v.method+"\",data:{id:\""+v.id+"\",label:\""+v.label+"\",elem_id:\'"+TOP.curr_elem_id+"\',val:this.value,parent_id:"+parent_id+"}})";
    r+= (v.type == 'number'?" min=0 max=999999 style=\"width: 9em;\"":'');
    r+= ">";
     // r+= (v.label.match(/_usd/))?"<div class=\"input-group-append\"><span class=\"input-group-text\">u$d</span></div>":"";
     r+= "</div>";
     this._screen = r;
 },
};

// *************************************************************************
// ***  13/02/2020
// *** CONSTRUYE UN item de la tabla
// *** RECIBE UN ARRAY DE OBJETOS QUE EXPONEN LOS CAMPOS DE LA BASE DE DATOS
// *** si es editable llama a _obj_updater
// ************************************************************************
const tbl_itm_set = {
	_screen:{},
	create:function(val,extras){
		var obj = Object.create(this);
		obj.set(val,extras);
		return obj._screen;
	},
	get_screen:function(){return this._screen},
	set:function(v,xt){
		let t = '',dx =1;vet_obj= 'text';vet_edit='_obj'//date_obj.create({label:"srvc_fec_init",title:'Fecha de Inicio'}).get_screen();
		if(v.label != null){
			if(v.hasOwnProperty('editable') && v.editable == true){
				// vet_obj = vet_check(v.vis_elem_type)
				// si esta en extras 'nolabel' se lo pasa al para del objeto visual CREADO agregando prop nolabel a v
				if(xt.hasOwnProperty('nolabel')){
					v.nolabel = true;
				}
				// si encuentra el item en redonly lo agrega al visual element
				if(xt.hasOwnProperty('readonly')){
					const found = xt.readonly.find(function(e) {
							return e == v.label;
						});
					if(found){
						v.readonly = true;
					}
				}
				vet_edit = '_obj_updater';
				//** DO ELEMENTS UPDATE
				if(v.hasOwnProperty('elements_id')){
					switch(v.label){
						case 'fec_ini':
							// console.log('edit fec ini');
							v.front_call = {
								method:'pcle_updv_fec_ini',
								sending:false,
								data:{
									type:"Element",
									prnt_id:v.elements_id,
									id:v.id,
									// LID -> LOCAL ID ES EL ID DEL INPUT EN PANTALLA
									lid:v.label+"_"+v.id
								}
							};
						break;
						default:
							v.front_call = {
								method:'pcle_updv',
								sending:true,
								data:{
									type:"Element",
									prnt_id:i.elements_id,
									id:v.id,
									// LID -> LOCAL ID ES EL ID DEL INPUT EN PANTALLA
									lid:v.label+"_"+v.id
								}
							};
						break;
					}

				}
				//*** DO ATOM PCLES UPDATE
				if(v.hasOwnProperty('atom_id')){
					v.front_call = {
						method:'pcle_updv',
						sending:true,
						data:{
							type:"Atom",
							prnt_id:v.atom_id,
							id:v.id,
							// LID -> LOCAL ID ES EL ID DEL INPUT EN PANTALLA
							lid:v.label+"_"+v.id
						}
					};
				}
				const ox = vet_obj+vet_edit;
				t += window[vet_obj+vet_edit].create(v).get_screen();
			}
			else{
				const null_obj = {
					label:'',
					value:'',
					readonly:true
				}
				console.log('null obj',null_obj);
				t += window['text_obj'].create(null_obj).get_screen();
			}
		}






		this._screen = t;
	},
}


// *************************************************************************
// ***  21/11/2019
// *** CONSTRUYE UN SET DE INPUTS EDITABLES
// *** RECIBE UN ARRAY DE OBJETOS QUE EXPONEN LOS CAMPOS DE LA BASE DE DATOS
// ************************************************************************
const editable_tbl_itm_set = {
	_screen:{},
	create:function(val,extras){
		var obj = Object.create(this);
		obj.set(val,extras);
		return obj._screen;
	},
	get_screen:function(){return this._screen},
	set:function(v,xt){
		let t = '',dx =1;vet_obj= 'text';//date_obj.create({label:"srvc_fec_init",title:'Fecha de Inicio'}).get_screen();
		if(v.label != null){
			// vet_obj = vet_check(v.vis_elem_type)
			// si esta en extras 'nolabel' se lo pasa al para del objeto visual CREADO agregando prop nolabel a v
			if(xt.hasOwnProperty('nolabel')){
				v.nolabel = true;
			}
			// si encuentra el item en redonly lo agrega al visual element
			if(xt.hasOwnProperty('readonly')){
				const found = xt.readonly.find(function(e) {
						return e == v.label;
					});
				if(found){
					v.readonly = true;
				}
			}
			//** DO ELEMENTS UPDATE
			if(v.hasOwnProperty('elements_id')){
				switch(v.label){
					case 'fec_ini':
						// console.log('edit fec ini');
						v.front_call = {
							method:'pcle_updv_fec_ini',
							sending:false,
							data:{
								type:"Element",
								prnt_id:v.elements_id,
								id:v.id,
								// LID -> LOCAL ID ES EL ID DEL INPUT EN PANTALLA
								lid:v.label+"_"+v.id
							}
						};
					break;
					default:
						v.front_call = {
							method:'pcle_updv',
							sending:true,
							data:{
								type:"Element",
								prnt_id:i.elements_id,
								id:v.id,
								// LID -> LOCAL ID ES EL ID DEL INPUT EN PANTALLA
								lid:v.label+"_"+v.id
							}
						};
					break;
				}

			}
			//*** DO ATOM PCLES UPDATE
			if(v.hasOwnProperty('atom_id')){
				v.front_call = {
					method:'pcle_updv',
					sending:true,
					data:{
						type:"Atom",
						prnt_id:v.atom_id,
						id:v.id,
						// LID -> LOCAL ID ES EL ID DEL INPUT EN PANTALLA
						lid:v.label+"_"+v.id
					}
				};
			}
			const xo = vet_obj+'_obj_updater';
			// console.log(xo);
			t += window[xo].create(v).get_screen();
		}
		else{
			const null_obj = {
				label:'',
				value:'',
				readonly:true
			}
			console.log('null obj',null_obj);
			t += window['text_obj_updater'].create(null_obj).get_screen();
		}
		this._screen = t;
	},
}


//********* 23 junio 2020 ***************************
// TD UPDATER CREA TD ELEMENTS EDITABLES
// es llamado por objeto table editable otbl_editable
// le falta clarificar el usuario permitido
// y otros elementos visuales ademas de texto y dropdown
var td_updater = {
	create:function(o){
		var obj = Object.create(this);
		return obj.set(o);
	},
	set: function(o){
		let x = '';
		//  SI EL CAMPO ES READONLY SOLO PONE EL TEXTO
		if(TOP.permisos > 1){
			x += "<td>";
			x += o.row.value;
		}
		// PONE EVENTS SOBRE EL TD PARA QUE REACIONE AL CAMBIO
		else{
			// CELDA DE TEXT EDITABLE
			if(o.row.vis_elem_type === 'text'){
				x += "<td contenteditable=\'true\' id='"+o.row.id+"'";
				let val = {'method':o.updateMethod,'pcle_id':o.row.id,'parent_id':o.row.atom_id,'sending':true}
				x += "onFocus='set_curr_edited(this)' onBlur='front_call("+JSON.stringify(val)+")' oninput='validate_td_update("+o.row.id+")'>";
				x += o.row.value;
			}

			if(o.row.vis_elem_type === 'number'){
				x += "<td contenteditable=\'true\' id='"+o.row.id+"'";
				let val = {'method':o.updateMethod,'pcle_id':o.row.id,'parent_id':o.row.atom_id,'sending':true}
				x += "onFocus='set_curr_edited(this)' onBlur='front_call("+JSON.stringify(val)+")' oninput='validate_td_update("+o.row.id+")'>";
				x += o.row.value;
			}

			// CELDA CON DROPDOWN SELECTOR
			else if (o.row.vis_elem_type === 'select') {
				x += "<td data-order=\'"+o.row.value+"\' data-search=\'"+o.row.value+"\'";
				x += dropdown.create(o);
			}
		}
		x += "</td>";
		return x;
	}
};




//********* 23 junio 2020 ***************************
// OBJETO TABLA CON INLINE EDIT USA EDITABLE FUNCT
// requiere td_updater obj
const otbl_editable={
	set_checkbox:function(v){
	 		let c = "<td class='text-center'>";
			c += "<input type=\"checkbox\" ";
			c += "id=\"select_id_check_"+v.atom_id+"\" value=\""+v.atom_id+"\" onChange=upd_checked(this.value) />";
			c += "</td>";
			return c;
	},
	create:function(v){
		// console.log('otbl',v	)
		// let extras = {
		// 	'select_id':false,
		// 	'caller':'edit_element',
		// 	'editables':v.map(l=>{return l.label}),// si algun label no es editable no esta en este array
		// 	'edit_call':(!edit_call?'pcle_updv':edit_call),
		// 	'nolabel':true
		// }
		if(v.data && Array.isArray(v.data)){
			let r = "<table id='"+v.tblId+"' class='table table-hover table-bordered tabe-sm'>";
			r += "<thead><tr>";
			// CHECKBOX PARA BORRAR ROWS EN COL 0

      if(v.data[0].hasOwnProperty('atom_id') && parseInt(TOP.permisos) < 2){
				r += "<th><a href='#' onClick=front_call({method:'delete_selected',data:{sending:'true'}})>Borrar</a></th>";
			}
			// COLUMNAS DEL HEADING

      r += Object.keys(v.data[0]).map(i=>{
					if(v.data[0][i]){
						return "<th class=\'text-center\'>"+v.data[0][i].title+"</th>"
					}
				}).join('');
			r +="</thead></tr>";

      console.log('rrrrr',r);
      r +="<tbody>";
			// END OF HEADING
			// DECLARA EL ROW Y LA COLUMNA CERO PARA EL CHECKBOX
			r += (v.data && Array.isArray(v.data)?v.data.map(row=>{
				return (row[0].hasOwnProperty('atom_id') && parseInt(TOP.permisos) < 2 ?"<tr id ="+
				row[0]['atom_id']+" >"+this.set_checkbox(row[0]):'<tr>') +
					Object.keys(row).map(c=>{
						// RESTO DE LAS COLUMNAS EN UN STRING
						let curr_value = {row:row[c],updateMethod:v.updateMethod}
						return td_updater.create(curr_value)+"</td>"}).join('')+"</tr>"
					}).join(''):'<td></td>');
			r += "</tbody></table>";
			return r
		}
	}
}

// LISTADO DE CAJA PARA UNA SOLA CAJA CON PRINT
const hcaja1 ={
	_content:'',
	tot:function(r){
		let res = 0;
		for(key in r){
			res += parseFloat(r[key].monto);
		}
		return res;
	},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj._content;
	},
	set:function(v){

		let bxs = '';
		if(v.hasOwnProperty('ctav_nom')){

			let total_vales =  parseFloat(v.ctav_saldo)+this.tot(v.ctav_li)-this.tot(v.ctav_le);

			let total_cash = parseFloat(v.saldo)+this.tot(v.ingresos)-this.tot(v.egresos);


			bxs += data_box.create({id:'saldo',label:"Saldo Previo ",value:accounting.formatMoney(parseFloat(v.saldo), "$", 2, ".", ",")}).get_screen()
			bxs += data_box.create({id:'tt_ingr',label:"Ingresos ",value:accounting.formatMoney(this.tot(v.ingresos), "$", 2, ".", ",")}).get_screen();
			bxs += data_box.create({id:'tt_egre',label:"Egresos ",value:accounting.formatMoney(this.tot(v.egresos), "$", 2, ".", ",")}).get_screen()
			bxs += data_box.create({id:'tt_actual',label:"Total Efectivo ",value:accounting.formatMoney(total_cash, "$", 2, ".", ",")}).get_screen()
			bxs +="</div></hr><h4 class=\'text-center\'>"+v.ctav_nom+"</h4><div class=\'card-body d-flex flex-wrap justify-content-around\'>";

			bxs += data_box.create({id:'ctav_saldo',label:"Saldo Previo ",value:accounting.formatMoney(parseFloat(v.ctav_saldo), "$", 2, ".", ",")}).get_screen()
			bxs += data_box.create({id:'ctav_tt_ingr',label:"Ingresos ",value:accounting.formatMoney(this.tot(v.ctav_li), "$", 2, ".", ",")}).get_screen();
			bxs += data_box.create({id:'ctav_tt_egre',label:"Egresos ",value:accounting.formatMoney(this.tot(v.ctav_le), "$", 2, ".", ",")}).get_screen();
			bxs += data_box.create({id:'ctav_tt_actual',label:"Total "+v.ctav_nom,value:accounting.formatMoney(total_vales, "$", 2, ".", ",")}).get_screen();
			bxs += "</div></hr><div class=\'card-body d-flex flex-wrap justify-content-around\'><div class=\'row d-flex justify-content-center\'>";
			bxs += data_box.create({id:'pos_actual',label:"Total "+"Posición actual",value:accounting.formatMoney((total_cash + total_vales), "$", 2, ".", ",")}).get_screen();
			bxs += "</div>";

		}else{
			let total = parseFloat(v.saldo)+this.tot(v.ingresos)-this.tot(v.egresos);

			bxs += data_box.create({id:'saldo',label:"Saldo Previo ",value:accounting.formatMoney(parseFloat(v.saldo), "$", 2, ".", ",")}).get_screen()
			bxs += data_box.create({id:'tt_ingr',label:"Ingresos ",value:accounting.formatMoney(this.tot(v.ingresos), "$", 2, ".", ",")}).get_screen();
			bxs += data_box.create({id:'tt_egre',label:"Egresos ",value:accounting.formatMoney(this.tot(v.egresos), "$", 2, ".", ",")}).get_screen()
			bxs += data_box.create({id:'tt_actual',label:"Total ",value:accounting.formatMoney(total, "$", 2, ".", ",")}).get_screen()
		}


		this._content = {
			title:"<span class=\'p-2\'>Arqueo de Caja: "+v.caja_nom+"</span><span class=\'ml-3 mr-3\'> - </span><span class=\'p-2\'>Desde: "+v.fec_desde+"</span><span class=\'p-2\'>Hasta: "+v.fec_hasta+"</span>",
			pnl_id:'pnl_caja',
			content:bxs
		};

	},


}

// LISTADO DE CAJA PARA UNA SOLA CAJA CON PRINT
const hcaja2 ={
	_content:'',
	_vc_tot:0,
	tot:function(r){
		let res = 0;
		for(key in r){
			res += parseFloat(r[key].monto);
		}
		return res;
	},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj._content;
	},
	mk_boxes:function(id,x){
		let bx = '';total = parseFloat(x.saldo)+this.tot(x.ingresos)-this.tot(x.egresos);

		bx += data_box.create({id:id+'saldo',label:"Saldo Previo ",value:accounting.formatMoney(parseFloat(x.saldo), "$", 2, ".", ",")}).get_screen();
		bx += data_box.create({id:id+'tt_ingr',label:"Ingresos ",value:accounting.formatMoney(this.tot(x.ingresos), "$", 2, ".", ",")}).get_screen();
		bx += data_box.create({id:id+'tt_egre',label:"Egresos ",value:accounting.formatMoney(this.tot(x.egresos), "$", 2, ".", ",")}).get_screen();
		bx += data_box.create({id:id+'tt_actual',label:"Total ",value:accounting.formatMoney(total, "$", 2, ".", ",")}).get_screen();
		return bx;
	},
	set:function(v){
		console.log('in hcaja2',v)
		// IMPRIMO LA CAJA MAIN
		let bxsline = this.mk_boxes('main',v);
		let total_cash = parseFloat(v.saldo)+this.tot(v.ingresos)-this.tot(v.egresos);

		if(v.hasOwnProperty('cuentas_vinculadas')){
			// IMPRIMO CAJAS VINCULADAS
			let c = v.cuentas_vinculadas;
			for (var i = 0; i < c.length; i++) {
				this._vc_tot += parseFloat(c[i].saldo)+this.tot(c[i].ingresos)-this.tot(c[i].egresos);
				bxsline += "</div></hr><h3 class=\'jp-title text-center\'>"+c[i].nombre+"</h3><div class=\'card-body d-flex flex-wrap justify-content-around\'>";

				bxsline +=	this.mk_boxes('cvinc'+i,c[i]);
			}
			bxsline += "</div></hr>";

			bxsline  += "<div class='row mt-3 d-flex justify-content-around'>";
			bxsline  += data_box.create({id:'cuentas_vinculadas',label:"Total Cuentas vinculadas",value:accounting.formatMoney(this._vc_tot, "$", 2, ".", ",")}).get_screen();
			bxsline  += data_box.create({id:'pos_actual',label:"Total "+"Posición actual",value:accounting.formatMoney((total_cash + this._vc_tot), "$", 2, ".", ",")}).get_screen();
			bxsline  += "</div>";
		}

		this._content = {
			title:"<span class=\'card-title p-2\'>Arqueo de Caja: "+v.caja_nom+"</span><span class=\'card-title ml-3 mr-3\'> - </span><span class=\'card-title p-2\'>Desde: "+v.fec_desde+"</span><span class=\'card-title p-2\'>Hasta: "+v.fec_hasta+"</span>",
			pnl_id:'pnl_caja',
			content:bxsline
		};

	}
};

//<span class=\'p-2\'>"+moment().format('D/M/YYYY')+"</span>

// **** GENERIC TABLE FROM ARRAY *******
const tbl_farr = {
	_scrn:''
	,get_screen:function(){return this._scrn}
	,create:function(v){
		const o = Object.create(this);
		o.set(v);
		return o;
	}
	,set: function(v){
		this._scrn = "<div class=\"row d-flex justify-content-around align-items-start p-3\">";
		// this._scrn += "<div class=\"col\"><legend>"+v.title+"</legend></div>";
		// this._scrn += "</div>";
		this._scrn += "<table class=\"table table-hover\" id=\'"+v.tbl_id+"\'>";
		this._scrn +="<thead><tr>";
		const h = v.headings;


		for(var k in h){
			this._scrn +="<th>"+h[k]+"</th>";
		}
		this._scrn +="</thead></tr>";
		// BODY AND ROWS ************
		this._scrn +="<tbody>";
		let i = v.items;

		let rows = '';
		for(let r in i ){
			let cols = '';
			for (let x in h){
				// ************* DEFAULT VALUE

				let col_value  = i[r][x];
				console.log('row',col_value);
				// accounting.formatMoney(parseFloat(x[key]), "", 2, ".", ",")
				cols +="<td>"+col_value+"</td>";
			}
			rows +="<tr>"+cols+"</tr>";
		}
		this._scrn += rows + "</tbody></table></div>";
	}
};



// *** GENERIC TABLE 1 ***********
const gntbl_1 = {
	_scrn:''
	,get_screen:function(){return this._scrn}
	,create:function(v){
		const o = Object.create(this);
		o.set(v);
		return o;
	}
	,set: function(v){
		this._scrn = "<div class=\"row d-flex justify-content-around align-items-start p-3\">";
		this._scrn += "<div class=\"col\"><legend>"+v.title+"</legend></div>";
		this._scrn += "</div>";
		this._scrn += "<table class=\"table table-hover\" id=\'"+v.tbl_id+"\'>";
		this._scrn +="<thead><tr>";
		const h = v.headings;


		for(var k in h){
			this._scrn +="<th>"+h[k]+"</th>";
		}
		this._scrn +="</thead></tr>";
		// BODY AND ROWS ************
		this._scrn +="<tbody>";
		let i = v.items;
		let rows = '';
		for(let r in i ){
			let cols = '';
			for (let x in h){
				// ************* DEFAULT VALUE
				let col_value  = i[r][x];
				// ***** VALUE DE BOTON ACCIONES
				if(x == 'id' && h[x] == 'Acciones'){
					let colv = '';
					for(let c in v.acciones){
					// console.log('acciones',v.acciones[c]);
						colv += "<span class=\"p-1\"><button type=\"button\" class=\"btn btn-primary\" onClick=front_call({method:'"+v.acciones[c].method+"',sending:"+v.acciones[c].sending+",data:{id:"+i[r][x]+"}})><i class=\"material-icons \">"+v.acciones[c].icon+"</i></button></span>";
					}
					col_value = colv;
				}
				cols +="<td>"+col_value+"</td>";
			}

			rows +="<tr>"+cols+"</tr>";
		}
		this._scrn += rows + "</tbody></table></div>";
	}
};

// DETALLE DE CUOTAS
var table_detalle_ctas ={
	_data:{},
	_screen:'',
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	set: function(val){
		this._data = val;
		if(this._data.hasOwnProperty('ctas')){
			// SI EL TIT DICE RESTANTE O EN MORA AGREGO LA CUENTA DE LIQUIDACION EN UN PAGO
			if (val.title.match(/Restantes/)|| val.title.match(/Mora/)){
				this._data.temp_tmonto_cta = 0;
				for (var i = 0;  i < this._data['ctas'].length; i++){
					for (var x = 0; x < this._data['ctas'][i].pcles.length; x++) {
						if (this._data['ctas'][i].pcles[x].label == 'nro_recibo' || this._data['ctas'][i].pcles[x].label == 'fecha_pago' ){
							this._data['ctas'][i].pcles[x].vis_elem_type = -1;
						}
						if(this._data['ctas'][i].pcles[x].label == 'monto_cta'){
							if(i == 0 ){
								this._data.temp_tmonto_cta_act =  this._data['ctas'][i].pcles[x].value
							}
							this._data.temp_tmonto_cta += parseInt(this._data['ctas'][i].pcles[x].value);
						}
					}
				}
				this._data.temp_liquidac_1pago = parseInt(this._data.temp_tmonto_cta_act)*i;
				this._data.title = "&nbsp;&nbsp;"+this._data.title + "&nbsp;&nbsp;Total:&nbsp;&nbsp;" + this._data.temp_tmonto_cta.toLocaleString() +"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total en 1 Pago : " + this._data.temp_liquidac_1pago.toLocaleString()
			}
			this.title = this._data.title
			// this._data.title += "<div class='col-lg-4 align-items-right'>Total: "+this._data.temp_tmonto_cta+"</div>"


			// hides nro. de cuota
			// for (var i = 0;  i < this._data['ctas'].length; i++){
			// 		for (var x = 0; x < this._data['ctas'][i].pcles.length; x++) {
			// 			if (this._data['ctas'][i].pcles[x].label == 'nro_cta' ){;
			// 				// // console.log('closing',this._data[i].pcles[i])
			// 				this._data['ctas'][i].pcles[x].vis_elem_type = -1;
			// 			}
			// 		}
			// 	}
			this._screen = "<table class=\"table table-hover\" id=\"tbl_det_ctas\">";
			// make table hedings *****
			// console.log('det cuotas',this._data);
			this._screen +="<thead><tr>";
			var t = this._data['ctas'][this._data['ctas'].length -1].pcles.map(function(i){if(i.vis_elem_type > -1){return "<th class=\"text-center\" scope=\'col\'>"+i.title+"</th>"}});
			this._screen += t.join('');
			this._screen +="</thead></tr><tbody>";

			//  get labels
			const h = this._data['ctas'][this._data['ctas'].length -1].pcles.filter(i => i.vis_elem_type > -1);

			// make table rows
			var tr = '<tr>';
			const tblrows = this._data['ctas'].map(function(x){
				const r = h.map(function(hl){
					return x.pcles.find(function(pf){return pf.label == hl.label});
				});

				r.map(function(i){
					if(typeof i !== 'undefined' && i.hasOwnProperty('value')){
						const v = (i.label.indexOf('monto') > -1 ? parseInt(i.value).toLocaleString():i.value);
						tr += "<td class=\'text-center\'>"+v+"</td>";
							// console.log('tr',tr);
						}
					})
				tr +="</tr>";
					// return r.map(function(x){if(x.hasOwnProperty('value')){return x['value']}});

				});

			 // console.log('rows',tr);
					// h.map(function(p){x.pcles.find(function(f){return f.label == p.label;})});


			// for (var i = 0;  i < this._data['ctas'].length; i++) {
			// 	// var temp = this._data['ctas'][i].pcles.map((x)=>{
			// 	// 	if(x.vis_elem_type > -1){
			// 	// 		return "<td>"+this.fix_value_type.call(this,x)+"</td>";
			// 	// 	}
			// 	// });
			// 	const temp = this._data['ctas'][i].pcles.map((x)=>{
			// 		const pcls = lbl.map((p)=>{
			// 			if(x.hasOwnProperty(p.label)){
			// 				return "<td>"+this.fix_value_type.call(this,x)+"</td>";
			// 			}
			// 		});

			// 	});

			// 	this._screen += temp.join('');
			// 	console.log('temp',temp.join(''));
			// 	this._screen += "</tr>";
			// }
			this._screen += tr+"</tbody></table>";
		};
	},
	get:function(val){
		return this._data[val];
	},
	get_screen:function(){return this._screen},
	fix_value_type:function(v){
		if(v.hasOwnProperty('label')){
			if(v.label.indexOf('monto') > -1){
				return parseFloat(v.value).toLocaleString();
			}else if(v.label.indexOf('fec')> -1){
				return v.value
				// return fx_date_to_dmy(v.value);
			}else {
				return v.value;
			}
		}else{
			return 'no_data';
		}
	},
}
// ************************

// NEW DETALLE CUOTAS DATOS *****
const new_det_ctas_data = {
	create:function(d){
		var obj = Object.create(this);
		return obj.set(d);
	},
	set: function(d){
		if(d){
			let tdata = new Array();
			let tot_pagado = 0;
			// LOOPING CUOTAS
			for (let i = 0; i < d.length; i++) {
				let xdata = new Array()
				// LOOPING PCLES
				// CONTROLA LOS HEADERS Y QUE INFO SE MUESTRA EN EL DETALLE
				let ctas_struct = new Array('monto_cta','fecha_vto',"nro_cta",'monto_pagado','fec_pago','recibo_nro','dias_mora','interes_mora')
				let ctas_struct_titles = new Array('Monto Cuota','Fecha Vto',"Nro. Cuota",'Monto Pagado','Fecha Pago','Recibo Nro.','Dias Mora','Intereses Mora')
				for (var p = 0; p < ctas_struct.length; p++) {
					let pcle = get_pcle(d[i],ctas_struct[p])
					if(pcle){
						xdata[ctas_struct_titles[p]] = pcle;
						if(ctas_struct[p] === "monto_pagado"){
							tot_pagado += parseInt(pcle);
						}
					}else{
						xdata[ctas_struct_titles[p]] = '-';
					}
				}
				tdata.push(xdata);
			}
			return {total:tot_pagado,det:tdata};
		}
		return ''
	}
}

// ************************

// TABLE cobranza futura
var table_reports ={
	_data:{},
	_screen:'',
	_height:500,
	get:function(val){
		return this._data[val];
	},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	set: function(val){
    // TOP.selected_ids = [];
	// TOP.current_selection_table = val;
	this._data = val;


	var h = val.headings;
	this._screen = "<table class=\"table table-hover "+val.size+"\" style=\"table-layout: fixed; \">";
	this._screen +="<thead><tr class=\"d-flex\">";

	this._screen +="<th scope=\'col\' class=\"col-3 align-middle text-center\">"+h.cli+"</th>";
	this._screen +="<th scope=\'col\' class=\"col-1 align-middle text-center\">"+h.pagado+"</th>";

	for (var i = 0; i < val.items.maxd; i++) {
		this._screen +="<th scope=\'col\' class=\"col-1 align-middle text-center\">"+h['apg_'+i]+"</th>";
	}
	this._screen +="</thead></tr><tbody>";
	if(val.items.hasOwnProperty('data')){
		for (var it = 0 ; it < val.items.data.length ; it ++){
			val.items.data[it].a_pagar.events.for
			this._screen +="<tr class=\"d-flex \" >\
			<td scope=\'col\'class=\"col-3 align-middle text-center\">"+val.items.data[it].cli+"</td>\
			<td scope=\'col\'class=\"col-1 align-middle text-center\">"+parseInt(val.items.data[it].pagado_cli).toLocaleString()+"</td>"
			val.items.data[it].a_pagar.events.forEach(e => this._screen +="<td scope=\'col\'class=\"col-1 align-middle text-center\">"+parseInt(e.monto).toLocaleString()+"</td>");
			this._screen +="</tr>";

		}
	}
	this._screen += "</tr>";
	this._screen +="</tbody></table></div>";
	this._screen +="<div class\"row d-flex\"><div class=\"col-4 d-flex\">Total Pagado a la fecha: "+parseInt(val.items.totgen).toLocaleString()+" </div>"

	},
	get_screen:function(){return this._screen},
}




// *****************  ATOMS TABLE ***********
var table_atoms ={
	_data:{},
	_screen:'',
	_height:500,
	get:function(val){
		return this._data[val];
	},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	set: function(val){
		this._data = val;
	  // // console.log('table_atom data',this._data);

	  this._screen = "<div class=\"row d-flex justify-content-around align-items-start p-3\">";
	  this._screen += this.get_newbot();
	  this._screen += "<div class=\"col\"><legend>Modificar Items del tipo "+val.type+"</legend></div>";
	  this._screen += "</div>";
	  this._screen += "<table class=\"table table-hover "+val.size+"\" style=\"table-layout: fixed; \">";
	  this._screen +="<thead><tr class=\"d-flex\">";
	  var h = Object.values(val.headings);
	//  // console.log('headins',);
	//  HEADINGS ***********************
	for(var k in h){
		if(h[k] != 'Acciones'){
			this._screen +="<th scope=\'col\' class=\"col align-middle text-center\">"+h[k]+"</th>";
		}
	}
	this._screen +="<th scope=\'col\' class=\"col align-middle text-center\">Acciones</th>";
	this._screen +="</thead></tr><tbody>";
	// BODY AND ROWS ************
	this._screen +="<tbody>";
	if(val.items.hasOwnProperty('rows')){
		var rows = val.items.rows;
		TOP.contab={};
		TOP.contab.rows = rows;
		for(var r in rows ){
			this._screen +="<tr class=\"d-flex \" >";
			if(rows[r].label != 'id'){
				this._screen +="<td scope=\'col\'class=\"col align-middle \">"+rows[r].name+"</td>";
			}
			this._screen += "<td scope=\'col\'class=\"col-2 align-middle text-center\">"+this.get_actions(rows[r].id)+"</td>";
			this._screen +=	"</tr>";
		}
	}

	this._screen +="</tbody></table></div>";
	this._screen += this.get_pagination();
	},
	get_screen:function(){return this._screen},
	get_actions(id){
		var act="<div class=\"row d-flex justify-content-around align-items-center \">";
		act +="<div class=\"col\"><button type=\"button\" class=\"btn btn-primary\"onClick=front_call({method:'edit_atom',data:{id:"+id+"},sending:true})><i class=\"material-icons \">open_in_new</i></button></div>"
		act +="<div class=\"col\"><button type=\"button\" class=\"btn btn-primary\"onClick=front_call({method:'delete_atom',data:{id:"+id+",sending:'false'}})><i class=\"material-icons \">delete</i></button></div>"
		act +="</div>";
		return act;
	},
	get_newbot(){
		var act ="<div class=\"col\">\
		<button type=\"button\" class=\"btn btn-primary\" onClick=front_call({'method':'new_atom',type:'"+this._data.type+"','sending':true})> Agregar Nuevo Item </button>\
		</div>";
		return act;
	},
	get_pagination(){
		var cp = parseInt(this._data.items.current_page)
		var scr = "<div class=\"row d-flex justify-content-around align-items-center \"><div><ul class=\"pagination\">";
		for (var i = 1; i <= parseInt(this._data.items.tot_pages); i++) {
			scr += "<li class=\"page-item "+(i==cp?"active":'')+"\"><a class=\"page-link\" href=\"#\" onClick=front_call({method:'refresh_atom',id:'"+this._data.items.id+"',page:"+i+",sending:true})>"+i+"</a></li>"
		}
		scr +="</ul></div></div>";
		return scr;
	}
};

var table_contab = {
	_data:{},
	_screen:'',
	_height:500,
	get:function(val){
		return this._data[val];
	},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	set: function(val){
		this._data = val;
	  // // console.log('data',this._data);
	  // o.data.forEach( a => // console.log('cnt',a.cli))
	  // o.data.forEach( a => // console.log('cnt',a.fst.pcles.monto))
	  // val.items.data.forEach(l => // console.log(l.cli));

	  this._screen = "<div class=\"row d-flex mt-5 justify-content-around align-items-start p-3\">";
	  this._screen += this.get_newbot();
	  this._screen += "<div class=\"col\"><legend>Cuentas contables</legend></div>";
	  this._screen += "</div>";
	  this._screen += "<table id='contab_tbl' class=\"table table-hover "+val.size+"\" style=\"table-layout: fixed;\" >";
	  this._screen +="<thead><tr class=\"d-flex\">";
	  var h = Object.values(val.headings);
	//  // console.log('headins',);
	for(var k in h){
		// // console.log(k)
		// if(h[k] != 'Id'){
			this._screen +="<th scope=\'col\' class=\"col align-middle text-center\">"+h[k]+"</th>";
		// }
	}
	this._screen +="<th scope=\'col\' class=\"col align-middle text-center\">Acciones</th>";
	this._screen +="</thead></tr><tbody>";

	if(val.items.hasOwnProperty('rows')){
		var rows = val.items.rows;
		TOP.contab={};
		TOP.contab.rows = rows;
		for(var r in rows ){
			this._screen +="<tr class=\"d-flex \" >";
			for (var line in rows[r]){
				// if(rows[r][line].label != 'id'){
					this._screen +="<td scope=\'col\'class=\"col align-middle \">"+rows[r][line].value+"</td>";
				// }
			}
			var acc_id = rows[r].find(function(i){return i.label == 'id'})
			this._screen += "<td>"+this.get_actions(acc_id.value)+"</td>";
			this._screen +=	"</tr>";
		}
	}

	this._screen +="</tbody></table></div>";
	// this._screen += this.get_pagination();
	},
	get_screen:function(){return this._screen},
	get_actions(id){
		var act="<div class=\"row d-flex justify-content-around align-items-center \">";
		act +="<div class=\"col\"><button type=\"button\" class=\"btn btn-primary\"onClick=front_call({method:'edit_contab',data:"+id+"})><i class=\"material-icons \">open_in_new</i></button></div>"
		act +="<div class=\"col\"><button type=\"button\" class=\"btn btn-primary\"onClick=front_call({method:'delete_contab',data:"+id+"})><i class=\"material-icons \">delete</i></button></div>"
		act +="</div>";
		return act;
	},
	get_newbot(){
		var act ="<div class=\"col\">\
		<button type=\"button\" class=\"btn btn-primary\" onClick=front_call({'method':'new_contab','sending':false})> Agregar Nuevo Item </button>\
		</div>";
		return act;
	},

}

// TABLE PLANILLA DE CAJAS
const table_plc ={
	_data:{},
	_screen:'',
	_height:500,
	get:function(val){
		return this._data[val];
	},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	set: function(val){
		TOP.selected_ids = [];
		TOP.current_selection_table = val;
		this._data = val;
		if(val.items.length > 0 ){
			this._screen = "<div class=\'row\'><h3 class=\'jp-title mt-4 mb-0 pl-4\'>"+val.title+"</h3></div><hr/>"
			this._screen += "<table class=\"table table-hover\" id=\'"+val.table_id+"\'>";
			this._screen +="<thead><tr>";
			var t='';
			for(var key in val.headings) {
				t += "<th class=\'text-center\'>"+val.headings[key]+"</th>";
			}
			this._screen += t;
			this._screen +="</thead></tr><tbody>";
	    // make table rows
	    var t2 = val.items.map(function(x,i){
	        // // console.log('listing',x )
	        var d ='';
	        for(var key in val.headings) {
	        	if(x.hasOwnProperty(key)) {
	        		var it = '';
	        		let td_class = '';
	        		if(key == "monto" || key.indexOf('saldo') > -1 || key.indexOf('greso') > -1 || key.indexOf('total') > -1 ){
	        			it = accounting.formatMoney(parseFloat(x[key]), "", 2, ".", ",");
	        			td_class= 'text-right pr-3';
	        		}else if(x[key] != '' && x[key] != undefined && x[key] != null){
	        			it = x[key];
	        		}
	        		if(key.indexOf('date') > -1 || key.indexOf('fecha') > -1 || key.indexOf('fec') > -1){
	        			it = (x[key] != '' ? fx_date_to_dmy(x[key]):'')
	        		}
	        		if(key == 'id'){
	        			it = "<button type=\"button\" class=\"btn btn-primary\"onClick=front_call({method:'edit_op',sending:true,data:{op_id:"+x[key]+"}})><i class=\"material-icons \">open_in_new</i></i></button>"
	        		}
	        		if(key == 'events_id' && val.hasOwnProperty('extras') && val.extras.hasOwnProperty('select_id')){
	        			TOP.selected_ids.push(x[key]);
	        			it = "<div class=\"custom-control custom-checkbox\">\
	        			<input type=\"checkbox\" class=\"custom-control-input\" id=\"select_id_check_"+x[key]+"\" value="+x[key]+" onChange=update_selected("+x[key]+",'"+val.extras.caller+"') checked=\"\">\
	        			<label class=\"custom-control-label\" for=\"select_id_check_"+x[key]+"\"></label></div>";
	        		}
	        		if(val.hasOwnProperty('extras') && val.extras.hasOwnProperty('editables')){
	        			if(val.extras.editables.find(function(e){return e == key})){
	        				let p = {'value':x[key],'label':key,'method':val.extras.edit_call,'id':x['events_id']}
	        				it = editable.create(p);
	        			};
	        		}
	        		d += "<td class='"+td_class+"' >"+it+"</td>";
	        	}
	        }
	        return "<tr class=\""+val.row_indicator+" \" >"+d+"</tr>";
	    });
	    this._screen += t2.join('');
	    this._screen += "</tr>";
	    this._screen +="</tbody></table></div>";
	}else{
		this._screen = "Sin datos";
	}

	},
	get_screen:function(){return this._screen},
	}

	const print_table_plc ={
		_screen:'',
		create:function(val){
			var obj = Object.create(this);
			obj.set(val);
			return obj;
		},
		set: function(val){
			if(val.items.length > 0 ){
				this._screen = "<div class=\'row\'><h3 class=\'p-3\'>"+val.title+"</h3><hr/></div>"
				this._screen += "<table class=\"table table-hover\">";
				this._screen +="<thead><tr>";
				let t='';
				for(var key in val.headings) {
					if(val.headings[key] != 'Ver'){
						t += "<th>"+val.headings[key]+"</th>";
					}
				}
				this._screen += t;
				this._screen +="</thead></tr><tbody>";
			    // make table rows
			    var t2 = val.items.map(function(x,i){
			        let d ='';
			        let td_class = '';
			        for(var key in val.headings) {
			        	if(x.hasOwnProperty(key) && key != 'id') {
			        		var it = '';
			        		if(key == "monto"){
			        			it = accounting.formatMoney(parseFloat(x[key]), "", 2, ".", ",");
	        					td_class= 'text-right pr-3';
			        		}else if(x[key] != '' && x[key] != undefined && x[key] != null){
			        			it = x[key];
			        		}
			        		if(key.indexOf('date') > -1 || key.indexOf('fecha') > -1 || key.indexOf('fec') > -1){
			        			it = (x[key] != '' ? fx_date_to_dmy(x[key]):'')
			        		}
			        		d +=  "<td class='"+td_class+"' >"+it+"</td>";
			        	}
			        }
			        return "<tr>"+d+"</tr>";
			    });
			    this._screen += t2.join('');
			    this._screen += "</tr>";
			    this._screen += "</tbody></table></div>";
			}else{
				this._screen = "Sin datos";
			}
		},
		get_screen:function(){return this._screen},
	}

	const prepare_print_pagares ={
			_screen:'',
			_registro_monto_ctas : 0,
			_mk_pagare: function(v,n){
				let r = '';
				if(v.nro_cta == 'Saldo a Financiar'){r += "<div class=\"pagebreak \" ></div>";}

				r += "<div class=\"container \"><br /><hr/><div class=\"row d-flex justify-content-end pr-4 \">Vence el "+v.fecha_vto+"&nbsp;&nbsp;</div>";
						r +=" <div class=\"row d-flex justify-content-around pl-4 pr-4\"><div class=\'col-6\' ><legend>"+v.nro_cta+"</legend></div><div class=\'col\'><legend>Por: &nbsp;"+accounting.formatMoney(parseFloat(v.monto_cta), "$", 0, ".", ",")+"</legend></div></div>";
						r += "<br/>";
						r +="<div class=\"row d-flex justify-content-between pl-4 pr-4\"><p>El dia&nbsp;<strong>"+fec_frmt_1(v.fecha_vto)+"</strong>&nbsp;pagaré sin protesto (art.50 D. Ley5965/63) a &nbsp;"+v.propietario+"&nbsp;";
						r += "o a su orden la cantidad de &nbsp;" +numeroALetras(parseFloat(v.monto_cta), {plural: 'PESOS ',singular: 'PESO',centPlural: 'CENTAVOS',centSingular: 'CENTAVO'})+"&nbsp;";
						r += "&nbsp; Por igual valor recibido a mi entera satisfacción pagadero en Talcahuano 638 4º F CABA</p></div>"
						r += "<div class=\"row d-flex pl-4 pr-4\">Codigo Lote/Servicio:&nbsp;"+v.prod+"</div>";
						r += "<div class=\"row d-flex pl-4 pr-4\">Firmante: &nbsp;"+v.firmante+"</div>";
						r += "<div class=\"row d-flex pl-2 pr-4\"><div class=\'col-8 pt-1\'>DNI: &nbsp; "+v.dni+"<br/>";
						r += "Calle: &nbsp; "+v.domic+" <br/>";
						r += "Localidad: &nbsp; "+v.loc+"<br/></div>";
						r += "<div class=\'col-4\'>Firma</br></br>Aclaración<br/></div></div>";
						r += "<hr/></div>";
						if(n % 4 == 0){r += "<div class='pagebreak'></div>";}
						return r;
			},
			create:function(val){
				var obj = Object.create(this);
				obj.set(val);
				return obj;
			},
			set: function(val){
				let dt = {
					prod:TOP.data.lote.lote_nom+(val.hasOwnProperty('srvc_name')?" - "+val.srvc_name:'') ,
					firmante:TOP.data.lote.datos_boleto.tit_nomap+' '+(TOP.data.lote.datos_boleto.cotit_nomap && TOP.data.lote.datos_boleto.cotit_nomap.length > 1?" / "+TOP.data.lote.datos_boleto.cotit_nomap:''),
					dni:TOP.data.lote.datos_boleto.tit_dni +' '+ (TOP.data.lote.datos_boleto.cotit_dni && TOP.data.lote.datos_boleto.cotit_dni.length > 1?" / "+TOP.data.lote.datos_boleto.cotit_dni:''),
					domic:TOP.data.lote.datos_boleto.tit_domic+' '+ (TOP.data.lote.datos_boleto.cotit_domic && TOP.data.lote.datos_boleto.cotit_domic.length > 1?" / "+TOP.data.lote.datos_boleto.cotit_domic:''),
					loc:TOP.data.lote.datos_boleto.tit_localidad+' '+ (TOP.data.lote.datos_boleto.cotit_locali && TOP.data.lote.datos_boleto.cotit_locali.length > 1?" / "+TOP.data.lote.datos_boleto.cotit_locali:''),
					propietario:TOP.data.lote.propietario
				};

				let cta_corte = 0;
				ord_num = 0;
				// cuotas que estan en mora, a la pantalla de pagares
				if(val.ctas_mora.events.length > 0 ){
					for (let m = 0; m < val.ctas_mora.events.length; m++){
						ord_num ++;
						let v = val.ctas_mora.events[m];
						let n = get_pcle(v,'nro_cta').replace(/Cuota/m, "");
						let ncta = parseInt(n.substring(0,n.indexOf('de')));
						dt.fecha_vto = get_pcle(v,'fecha_vto');
						dt.monto_cta =  get_pcle(v,'monto_cta');
						dt.nro_cta = "Nro.:&nbsp;"+n;
						this._screen += this._mk_pagare(dt,ord_num);
					}
				}
				//***  CUOTAS A PAGAR A LA PANTALLA DE PAGARES
				if(val.ctas_restantes.events.length > 0){
					for (let i = 0; i < val.ctas_restantes.events.length; i++) {
						ord_num ++;
						let v = val.ctas_restantes.events[i];
						let n = get_pcle(v,'nro_cta').replace(/Cuota/i, "");
						let ncta = parseInt(n.substring(0,n.indexOf('de')));
						this._registro_monto_ctas += parseInt(get_pcle(v,'monto_cta'));
						//*** SETEANDO ULTIMO PAGARE EN BASE A LA FRECUENCIA DE REVISION DE CONTRATO
						if(parseInt(val.freq_rev) > 0 && ncta % parseInt(val.freq_rev) == 0){
							//***  SETEO VALORES DEL ultimo PAGARE antesd el corte
		 					dt.fecha_vto = get_pcle(v,'fecha_vto');
							dt.monto_cta =  get_pcle(v,'monto_cta');
							dt.nro_cta = "Nro.:&nbsp;"+n;
							//  suma el html de pagare a var de pantalla
							this._screen += this._mk_pagare(dt,ord_num);

							//***  SETEO EL PAGARE POR EL SALDO A FINANCIAR DESDE EL CORTE DE REVISION
							//*** SALDO A FINANCIAR ES EL MONTO DE LA CUOTA SIGUIENTE POR CANTIDAD DE CUOTAS RESTANTES

							let vlast = val.ctas_restantes.events[i+1];
								console.log('nro cuota',vlast)
							dt.fecha_vto = get_pcle(v,'fecha_vto');
							dt.monto_cta =  parseInt(get_pcle(vlast,'monto_cta'))*parseInt(val.ctas_restantes.events.length - i);
							dt.nro_cta = "Saldo a Financiar";
							// console.log('making last pagare por el total', (val.ctas_restantes.events.length - cta_corte))
							this._screen += this._mk_pagare(dt,0);
							break;
						}
						//***  SETEO VALORES DEL PAGARE ACTUAL EN EL LOOP
	 					dt.fecha_vto = get_pcle(v,'fecha_vto');
						dt.monto_cta =  get_pcle(v,'monto_cta');
						dt.nro_cta = "Nro.:&nbsp;"+n;
						//  suma el html de pagare a var de pantalla
						this._screen += this._mk_pagare(dt,ord_num);

						//*** SETEANDO ULTIMO PAGARE SI HAY SALDO A FINANCIAR
						// val.sf.total es el saldo a financiar que si tiene dos ciclos es mas alto que la suma de todas las cuotas resgistradas en el loop de imprimir pagares
						if(val.sf && val.sf.hasOwnProperty('total')){
							if(i == val.ctas_restantes.events.length -1 && parseInt(val.sf.total) > this._registro_monto_ctas ){
								dt.fecha_vto = val.sf.fecha;
								dt.monto_cta =  parseInt(val.sf.total);
								dt.nro_cta = "Saldo a Financiar";
								// console.log('making last pagare por el total', (val.ctas_restantes.events.length - cta_corte))
								this._screen += this._mk_pagare(dt,0);
								break;
							}
						}

					}
				}
			},
			get_screen:function(){return this._screen},
		}





	// *** REVISION TABLE ***********
	const table_revision = {
		_data:{},
		_screen:'',
		_height:500,
		get:function(val){
			return this._data[val];
		},
		create:function(val){
			var obj = Object.create(this);
			obj.set(val);
			return obj;
		},
		set: function(val){
			this._data = val;
			this._screen = "<div class=\"row d-flex justify-content-around align-items-start p-3\">";
			this._screen += this.get_newbot();
			this._screen += "<div class=\"col\"><legend>Lotes Reportados </legend></div>";
			this._screen += "</div>";
			this._screen += "<table class=\"table table-hover\" id=\'"+val.tbl_id+"\'>";
			this._screen +="<thead><tr>";

		//  // console.log('headins',);
		//  HEADINGS ***********************
		var h = val.headings;
		for(var k in h){
			this._screen +="<th>"+h[k]+"</th>";
		}
		this._screen +="</thead></tr>";
		// BODY AND ROWS ************
		this._screen +="<tbody>";
		var i = val.items;
		var rows = '';
		for(var r in i ){

			var cols = '';
			for (var x in h ){
				// console.log('item',r)
				// console.log(x);
				// ************* DEFAULT VALUE
				var col_value  = i[r][x];
				// *** VALUE SI ES CAMPO EDITABLE DE ASIGNADO_A
				// if(x == 'asignado_a2'){
				// 	const p = {'value':i[r][x],'label':x,'method':val.extras.edit_call,'id':i[r]['rev_id']};
				// 	col_value = select_obj_by_name.create(p).get_screen();
				// }
				if(x =='estado'){
					col_value = this.get_estado(i[r][x],i[r]['rev_id']);
				}

				// if(x == 'estado' && col_value == 'pendiente'){state = 'class=\"table-warning\"';}else{state = '';}

				cols +="<td>"+col_value+"</td>";
			}

			rows +="<tr>"+cols+"</tr>";
		}
		this._screen += rows + "</tbody></table></div>";
	},
		get_screen:function(){return this._screen},
		get_newbot(){
		var act ="<div class=\"col\">\
		<button type=\"button\" class=\"btn btn-primary\" onClick=front_call({'method':'new_revision','sending':false})> Nuevo Mensage </button>\
		</div>";
		return act;
	},
		get_estado : function(v,id){
		var type = '';
		var x = '';
		switch(v){
			case 'resuelto':
			type = 'success';
			x = 'Resuelto';
			break;
			case 'pendiente':
			type = 'warning';
			x = 'Pendiente';
			break;

		}
		// *** SELECTOR DE ESTADO
		var r = "<div class=\"btn-group dropleft p-1\" role=\"group\" aria-label=\"Button group with nested dropdown\">\
		<button type=\"button\" id=\"btn_estado_"+id+"\" class=\"btn btn-sm btn-"+type+"\">"+x+"</button>\
		<div class=\"btn-group dropleft show\" role=\"group\">\
		<button id=\"btnGroupDrop_"+id+"\" type=\"button\" class=\"btn btn-sm btn-"+type+" dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"true\"></button>\
		<div class=\"dropdown-menu \" aria-labelledby=\"btnGroupDrop1\" x-placement=\"bottom-end\" style=\"position: absolute; transform: translate3d(0px, 36px, 0px); top: 0px; left: 0px; will-change: transform; z-index:10000;\">\
		<a class=\"dropdown-item\" onClick=front_call({'method':'revision_set_estado',sending:true,'state':'1','id':"+id+"})>Resuelto</a>\
		<a class=\"dropdown-item\" onClick=front_call({'method':'revision_set_estado',sending:true,'state':'0','id':"+id+"})>Pendiente</a>\
		</div>\
		</div>\
		</div>";

		return r;
	},
};






// RECIBE OBJ HEADINGS Y ARR ITEMS CON OBJS DEL MISMO LABEL
var mk_simple_table ={
	_data:{},
	_screen:'',
	_height:500,
	get:function(val){
		return this._data[val];
	},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	set: function(val){
		this._data = val;
		this._screen = "<table class=\"table table-hover "+val.size+"\">";
		this._screen +="<thead id=\'encab\'>";
		var t ='';
		for(var key in val.headings) {
			t += "<th class=\"align-middle text-center\" >"+val.headings[key]+"</th>";
		}
		this._screen += t;
		this._screen +="</thead><tbody>";
    // make table rows
    // // console.log('listing', val)
    var t2 = val.items.map(function(x){
        // // console.log('listing',x )
        var d ='';
				//**** RECORRE HEADINGS PARA OBTENER LABEL:VALUE
			for(var key in val.headings){
      	var it = '&nbsp;-------------------------&nbsp;';
				//**** X ES ITEM DE LA COLUMNA
				if(x.hasOwnProperty(key)) {
        		if(!isNaN(parseFloat(x[key]))){
        			it = td_format_cont(key,x[key]);
        		}
						//*** LA COLUMNA TIENE CONTENIDO VALIDO - LO MANDA A DAR FORMATO AL VALUE
						else if(x[key] != '' || x[key] != undefined){
							it = td_format_cont(key,x[key]);
        		}
						//  ** LA COLUMAN TIENE UN DATO DE FECHA LO MANDA A FORMATEAR
						if(key.indexOf('date') > -1 || key.indexOf('fecha') > -1 || key.indexOf('fec') > -1){
							it = (x[key] != '' ? td_format_cont(key,fx_date_to_dmy(x[key])):'')
        		}
        		if(key == 'events_id' && val.hasOwnProperty('extras') && val.extras.hasOwnProperty('select_id')){
						//*** BUSCA SI ESTA EN EL ARRAY DE SELECCIONADOS PARA MOSTRARLO EN EL CHECKBOX
						var ch = TOP.selected.find(function(i){return i.events_id == x[key]});
						// *** HACE EL CHECKBOX PARA SELECCIONAR / DESSELECCIONAR LA FILA Y SUS COLUMNAS
						it = "<td><div class=\"custom-control custom-checkbox\">\
							<input type=\"checkbox\" class=\"custom-control-input\" id=\"select_id_check_"+x[key]+"\" value="+x[key]+" onChange=update_selected() "+(ch!=undefined?"checked":"")+">\
							<label class=\"custom-control-label\" for=\"select_id_check_"+x[key]+"\"></label></div></td>";
					}
					//**** SI ESTA MARCADO COMO EDITABLE HACE UN IMPUT TYPE
				if(val.hasOwnProperty('extras') && val.extras.hasOwnProperty('editables')){
					if(val.extras.editables.find(function(e){return e == key})){
						var p = {'value':x[key],'label':key,'method':val.extras.edit_call,'id':x['events_id']}
						it = "<td>"+editable.create(p)+"</td>";
					};
				}
			}
				d += it;
			}
			//**** SALIDA DE LA FILA Y COLUMNAS
			return "<tr "+(x.termino!="EN_MORA" ? "class=\"table-success\"" : "class=\"table-warning\"")+">"+d+"</tr>";
		});
    this._screen += t2.join('');
    this._screen += "</tr>";
    this._screen +="</tbody></table></div>";
	},

	get_screen:function(){return this._screen},
}

// NO ESTOY USANDO | RECORRE UN ARRAY HEADINGS Y LUEGO UN ARRAY CON FILAS Y COLUMNAS
var mk_pcles_table ={
	_data:{},
	_screen:'',
	get:function(val){
		return this._data[val];
	},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	set: function(val){
		TOP.selected_ids = [];
		TOP.current_selection_table = val;
		this._data = val;
		this._screen = "<table class=\"table table-hover\">";
    // make table hedings *****
    this._screen +="<thead><tr>";
    var t='';
    for(var key in val.headings) {
    	if(val.headings.hasOwnProperty(key)) {
    		t += "<th scope=\'col\'>"+val.headings[key]+"</th>";
    	}
    }
    this._screen += t;
    this._screen +="</thead></tr><tbody>";
    // make table rows

    for(var line in val.items){
    	var d ='';
    	for(var col in val.items[line]){
    		var it = val.items[line][col].value;
    		var isdate = it.match(/\d+\-\d+\-\d+/);
    		if(isdate != null){
    			it = fx_date_to_dmy(it);
    		}else
    		if(!isNaN(parseFloat(it))){
    			it = parseFloat(it).toLocaleString();
    		}


    		d += "<td class=\"align-middle\">"+it+"</td>";
    	}
    	this._screen += "<tr>"+d+"</tr>";
    }
    this._screen += "</tr>";
    this._screen +="</tbody></table>";

 //    var t2 = val.items.map(function(l){
 //        var d ='';
 //        var line = l.map(function(x){
 //        	// console.log('line',x.label,x.value )
 //            // for(var xk in val.headings) {
	// 	    	// // console.log('x',x[xk])
	// 	    	// if(x.hasOwnProperty(key)) {
	// 	        	var it = '';
	// 	        	if(!isNaN(parseFloat(x.value))){
	// 		          it = parseFloat(x.value).toLocaleString();
	// 		        }else if(x.value != '' || x.value != undefined){
	// 		        	it = x.value;
	// 		        }
	// 		        if(x.value.indexOf('date') > -1 || x.value.indexOf('fecha') > -1){

	// 		        	it = (it.length > 1 ? fx_date_to_dmy(x.value):'')
	// 		        }
	// 		        if(x.value == 'events_id' && val.extras.hasOwnProperty('select_id')){
	// 		        	TOP.selected_ids.push(x.value);
	// 		        	it = "<div class=\"custom-control custom-checkbox\">\
	// 							  <input type=\"checkbox\" class=\"custom-control-input\" id=\"select_id_check_"+x.value+"\" value="+x.value+" onChange=update_selected("+x.value+",'"+val.extras.caller+"') checked=\"\">\
	//   							<label class=\"custom-control-label\" for=\"select_id_check_"+x.value+"\"></label></div>";
	// 		        }
	// 		      	d += "<td class=\"align-middle\">"+it+"</td>";
	// 			// }
	// 		// }
	// 		return d;
	// 	});
	// 	return "<tr>"+line+"</tr>";
	// });

 //      this._screen += t2.join('');
 //      this._screen += "</tr>";
 //      this._screen +="</tbody></table>";
	},
	get_screen:function(){return this._screen},
	}

	// RECIBE UN OBJETO CON HEADING ARRAY / CONTENT ARRAY /
	// HACE UN TABLE EDITABLE
	var mk_editable_table ={
		_data:{},
		_screen:'',
		create:function(val){
			var obj = Object.create(this);
			obj.set(val);
			return obj.get_screen();
		},
		set: function(val){
			this._data = val;
	    // if(this._data.length >0){
	    	this._screen = "<table class=\"table table-hover\">";
	      // make table hedings *****
	      this._screen +="<thead><tr>";
	      var t = this._data.headings.map(function(i){return "<th scope=\'col\'>"+i+"</th>"});
	      this._screen += t.join('');
	      this._screen +="</thead></tr><tbody>";
	      // make table rows
	      var t2 = this._data.items.map(function(x){
	      	x.type = 'text';
	      	var isdate = x.value.match(/\d+\/\d+\/\d+/);
	      	if(isdate != null){
	      		x.value = fx_date_to_dmy(x.value);
	      	}else if(!isNaN(x.value)){
	      		x.value = parseFloat(x.value).toLocaleString();
	      		x.type = 'number';
	      	}
	      	return "<tr><td>"+x.label+"</td><td id=\""+x.id+"\">"+editable.create(x)+"</td></tr>"
	      });

	      this._screen += t2.join('');
	      this._screen += "</tr>";
	      this._screen +="</tbody></table>";
	    // }
	},
	get_screen:function(){return this._screen},
}

var mk_table_gen1={
	_data:{},
	_screen:'',
	_height:500,
	get:function(val){
		return this._data[val];
	},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	set: function(val){
		this._data = val;
		// console.log('items',TOP)
		// console.log('val',val)
		// ************  HEADINGS   *************
		this._screen = "<table class=\"table table-hover\">";
		this._screen +="<thead><tr>";
		var t='';
		for(var key in val.headings) {
			t += "<th scope=\'col\' class=\"align-middle text-center\" >"+val.headings[key]+"</th>";
		}
		this._screen += t;
		this._screen +="</thead></tr><tbody>";
    //     ************* ROWS ***************
    // <button type=\"button\" class=\"btn btn-primary\" onClick=front_call({method:'kill_event',sending:true,data:{ev_id:"+x['events_id']+",elm_id:"+x['elements_id']+"}})><i class=\"material-icons \">delete</i></button>
   // // console.log('heading',val.headings)

   	var t2 = val.items.map(function(x){
   	var d ='';
        // console.log('h2',val.headings)
        for(var key in val.headings) {
        	if(x.hasOwnProperty(key)) {
        		var it = '';
        		if(!isNaN(parseFloat(x[key]))){
        			it = parseFloat(x[key]);
        		}else if(x[key] != '' || x[key] != undefined){
        			it = x[key];
        		}
        		if(key.indexOf('date') > -1 || key.indexOf('fec') > -1){

        			it = (x[key] != '' ? x[key]:'-')
        		}
        		// *********** fix de editar contrato ******
        		if(key == 'event_type'){
        			var types = {'4':"Vencida","6":"Adelantada","8":"A Vencer"}
        			it = types[x[key]];
        		}
        		// *********** id para kill event  *************
        		// if(key == 'event_id'){
        		// 	it = "<button type=\"button\" class=\"btn btn-primary\" onClick=front_call({method:'kill_event',sending:true,data:{ev_id:"+x[key]+",elm_id:"+ TOP.last_call_param.id +"}})><i class=\"material-icons \">delete</i></button><span>&nbsp;"+x[key]+"</span>"
        		// }

        		//************ ES UN SELECT CHECK BOX *****************
        		if(key == 'events_id' && val.hasOwnProperty('extras') && val.extras.hasOwnProperty('select_id')){
					// AGREGO EL EVENTS_ID PARA QUE LO REFRESQUE UPDATES
					// if(!TOP.selected_ids.find(function(i){ i == x[key]})){TOP.selected_ids.push(x[key]);}
					it = "<div class=\"custom-control custom-checkbox\">\
					<input type=\"checkbox\" class=\"custom-control-input\" id=\"select_id_check_"+x[key]+"\" value="+x[key]+" onChange=update_selected("+x[key]+",'"+val.extras.caller+"') checked=\"\">\
					<label class=\"custom-control-label\" for=\"select_id_check_"+x[key]+"\"></label></div>";
				}
				// ****************  ES UN CAMPO EDITABLE  ***************************
				if(val.hasOwnProperty('extras') && val.extras.hasOwnProperty('editables')){
					if(val.extras.editables.find(function(e){return e == key})){
						var pcleid = key +'_pcle_id';
						var p = {'value':x[key],'label':key,'method':val.extras.edit_call,'id':x[pcleid],'parent_id':x['event_id'],'readonly':false}
						// console.log(' editable',p);
						if(key == 'monto_pagado' && x[key] == '0'){p.readonly = true}
						if(key == 'fec_pago' && x[key] == '-'){p.readonly = true}
						it = editable.create(p);
					};
				}
				// **************** ES UN ID PARA ACTIVAR DETALLE  ********************
				// // console.log('edit1',key)
				// // console.log('edit2',val.headings[key])
				if(key == 'detalle_id'){
					// AGREGO EL EVENTS_ID PARA QUE LO REFRESQUE UPDATES
					// if(!TOP.selected_ids.find(function(i){ i == x[key]})){TOP.selected_ids.push(x[key]);}
					it = "<button type=\"button\" class=\"btn btn-primary\" onClick=front_call({method:'detalle_recibo',sending:true,data:{rec_id:"+x[key]+"}})><i class=\"material-icons \">open_in_new</i></button>";
				}

				//*************  IMPRIMO EL TD  ******************************
				d += "<td scope=\'col\'class=\"align-middle text-center\">"+it+"</td>";
			}
		}
		return "<tr>"+d+"</tr>";
	});
   	this._screen += t2.join('');
   	this._screen += "</tr>";
   	this._screen +="</tbody></table></div>";
	},
	get_screen:function(){return this._screen},
}


// *********************************************************
// *** TABLA DEL EDITOR DE CONTRATOS
var mk_table_edit_contrato={
	_data:{},
	_screen:'',
	_height:500,
	get:function(val){
		return this._data[val];
	},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	set: function(val){
		this._data = val;
		// console.log('items',TOP)
		// console.log('val',val)
		// ************  HEADINGS   *************
		this._screen = "<table class=\"table table-hover\">";
		this._screen +="<thead><tr>";
		var t='';
		for(var key in val.headings) {
			t += "<th scope=\'col\' class=\"align-middle text-center\" >"+val.headings[key]+"</th>";
		}
		this._screen += t;
		this._screen +="</thead></tr><tbody>";
    //     ************* ROWS ***************
    // <button type=\"button\" class=\"btn btn-primary\" onClick=front_call({method:'kill_event',sending:true,data:{ev_id:"+x['events_id']+",elm_id:"+x['elements_id']+"}})><i class=\"material-icons \">delete</i></button>
   // // console.log('heading',val.headings)
   	// console.log('items',val.items);
   	var t2 = val.items.map(function(x){
   	var d ='';
        // console.log('h2',val.headings)
        for(var key in val.headings) {
        	if(x.hasOwnProperty(key)) {
        		var it = '';
        		if(!isNaN(parseFloat(x[key]))){
        			it = parseFloat(x[key]);
        		}else if(x[key] != '' || x[key] != undefined){
        			it = x[key];
        		}
        		if(key.indexOf('date') > -1 || key.indexOf('fec') > -1){

        			it = (x[key] != '' ? x[key]:'-')
        		}
        		// *********** fix de editar contrato ******
        		if(key == 'event_type'){
        			var types = {'4':"Vencida","6":"Adelantada","8":"A Vencer"}
        			it = types[x[key]];
        		}
        		// ****************  SI ES UN CAMPO EDITABLE  ***************************
				if(val.hasOwnProperty('extras') && val.extras.hasOwnProperty('editables')){
					if(val.extras.editables.find(function(e){return e == key})){
						var pcleid = key +'_pcle_id';
						var p = {'value':x[key],'label':key,'method':val.extras.edit_call,'id':x[pcleid],'parent_id':x['event_id'],'readonly':false}
						// console.log(' editable',p);
						if(key == 'estado'){p.readonly = true}
						if(key == 'monto_pagado' && x[key] == '0'){p.readonly = false}
						// if(key == 'fec_pago' && x[key] == '-'){p.readonly = true}
						it = editable.create(p);
					};
				}
				// **************** ES UN ID PARA ACTIVAR DETALLE  ********************
				// // console.log('edit1',key)
				// // console.log('edit2',val.headings[key])
				if(key == 'detalle_id'){
					// AGREGO EL EVENTS_ID PARA QUE LO REFRESQUE UPDATES
					// if(!TOP.selected_ids.find(function(i){ i == x[key]})){TOP.selected_ids.push(x[key]);}
					it = "<button type=\"button\" class=\"btn btn-primary\" onClick=front_call({method:'detalle_recibo',sending:true,data:{rec_id:"+x[key]+"}})><i class=\"material-icons \">open_in_new</i></button>";
				}

				//*************  IMPRIMO EL TD  ******************************
				d += "<td scope=\'col\'class=\"align-middle text-center\">"+it+"</td>";
			}
		}
		return "<tr>"+d+"</tr>";
	});
   	this._screen += t2.join('');
   	this._screen += "</tr>";
   	this._screen +="</tbody>";
   	// this._screen +="<tfoot><tr><th colspan='4' text-right >TOTAL PAGADO:</th><th>"+val.total+"</th></tr></tfoot>" ;
   	this._screen +="</tbody></table></div>";
	},
	get_screen:function(){return this._screen},
}

const comprobantes_tbl={
	create:function(v,id){
		let r = '';
		r += "<table class=\"table table-hover\" id=\'"+id+"\'>";
		r += "<thead><tr>";
		console.log('comprob',v[0]);
		r += (Object.keys(v[0]).map(i=>{return "<th>"+i+"</th>"})).join('');
		r +="</thead></tr>";
		r +="<tbody>";
		// ******* row con formateado de numeros  y readonly
		// r += v.map(row=>{return "<tr>"+Object.keys(row).map(c=>{return td_format_cont(c,(c == 'Total' && row['Descripcion'].indexOf('Debito')> -1 ?'-'+row[c]:row[c]))}).join('')+"</tr>"}).join('');
		// ****************
		// ******* row con total y saldo editable ****
		r += v.map(row=>{return "<tr>"+Object.keys(row).map(c=>{
		if(c.indexOf('Intereses') > -1 ||c.indexOf('Total') > -1 || c.indexOf('Saldo') > -1 ){
			let x = {'value':row[c],'label':c,'method':'update_comprobante','id':row['Recibo Nro.']}
			return '<td>'+editable.create(x)+'</td>';
		}else{
			return '<td>'+row[c]+'</td>';
		}

		}).join('')+"</tr>"}).join('');
		r += "</tbody></table>";
		return r
	}
}



const tbl={
	create:function(v,id){
		let r = '';
		r += "<table class=\"table table-hover\" id=\'"+id+"\'>";
		r += "<thead><tr>";
		r += (Object.keys(v[0]).map(i=>{return "<th>"+i+"</th>"})).join('');
		r +="</thead></tr>";
		r +="<tbody>";
		r += v.map(row=>{return "<tr>"+Object.keys(row).map(c=>{return "<td>"+row[c]+"</td>"}).join('')+"</tr>"}).join('');
		r += "</tbody></table>";
		return r
	}
}



const table_detalle_movs = {
	create:function(v,id){
		return "<div class=\"row p-1\"><div class=\"col d-flex justify-content-between\"></div>\
		</div>\
		<div class='card bg-light '>\
		<div class='card-header  d-flex justify-content-center'>\
		<div class='card-title'>"+v.title+"</div>\
		</div>\
		<div class=\'card-body d-flex flex-wrap justify-content-around\'>\
		<div class=\'col d-flex p-1 justify-content-center\' id=\"container_table_last_movs\">"+otbl.create(v.events,id)+"</div>\
		</div>\
		</div>"
	}
}

const new_modal = {
	create:function(o){
		$('#my_modal_container').addClass('modal-dialog-centered modal-'+o.wm);
		if(o.title != ''){
			$('#modal_header').addClass('d-flex')
			$('#my_modal_title').html(o.title)
		}else{
			$('#modal_header').addClass('d-none')
		}
		$('#my_modal_body').html(o.content);

		if(o.okbutt){
			$('#ok_button').show();
		}else{
			$('#ok_button').hide();
		}

		$('#close_button').show();

		$('#my_modal').on('shown.bs.modal', function() {
		    $('input:text:visible:first', this).focus()
		});

		// KEYBOARD FALSE PARA NO CERRAR CON KEYSTROKES
		$('#my_modal').modal({
			    backdrop: 'static',
			    keyboard: false
			});
		// CLEAR ALL ANTES DE CERRAR
		$('#my_modal').on('hidden.bs.modal', function (e) {
			$('#my_modal_title').html('');
			$('#my_modal_body').html('');
			$('#modal-footer-msgs').html('');
			$('#my_modal_container').removeClass('modal-dialog-centered modal-lg');
			$('#my_modal_container').removeClass('modal-dialog-centered modal-xl');
			$('#my_modal_container').removeClass('modal-dialog-centered modal-lg2');
			$('#my_modal_container').removeClass('modal-dialog-centered modal-med');
			$('#my_modal_container').removeClass('modal-dialog-centered modal-sml');
		})
		$("#my_modal").modal('show');
	}
}

var mk_table_gen2={
	_data:{},
	_screen:'',
	_height:500,
	get:function(val){
		return this._data[val];
	},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	set: function(val){
		this._data = val;
		// ************  HEADINGS   *************
		this._screen = "<table class=\"table table-hover\" id=\'"+val.table_id+"\'>";
		this._screen +="<thead><tr>";
		var t='';
		for(var key in val.headings) {
			t += "<th scope=\'col\' class=\"align-middle text-center\" >"+val.headings[key]+"</th>";
		}
		this._screen += t;
		this._screen +="</thead></tr><tbody>";
    //     ************* ROWS ***************
   // // console.log('heading',val.headings)
   // // console.log('items',val.items)
   var t2 = val.items.map(function(x){
   	var d ='';
        // // console.log('h2',val.headings)
        for(var key in val.headings) {
        	if(x.hasOwnProperty(key)) {
        		var it = '';
        		var reg = /^\d+$/;

        		if(reg.exec(x[key])){
        			it = accounting.formatMoney(parseFloat(x[key]), "",0, ",", "");
        		}else if(x[key] != '' || x[key] != undefined){
        			it = x[key];
        		}
        		if(key.indexOf('date') > -1 || key.indexOf('fec') > -1){

        			it = (x[key] != '' ? x[key]:'-')
        		}

        		//************ ES UN SELECT CHECK BOX *****************
        		if(key == 'events_id' && val.hasOwnProperty('extras') && val.extras.hasOwnProperty('select_id')){
					// AGREGO EL EVENTS_ID PARA QUE LO REFRESQUE UPDATES
					// if(!TOP.selected_ids.find(function(i){ i == x[key]})){TOP.selected_ids.push(x[key]);}
					it = "<div class=\"custom-control custom-checkbox\">\
					<input type=\"checkbox\" class=\"custom-control-input\" id=\"select_id_check_"+x[key]+"\" value="+x[key]+" onChange=update_selected("+x[key]+",'"+val.extras.caller+"') checked=\"\">\
					<label class=\"custom-control-label\" for=\"select_id_check_"+x[key]+"\"></label></div>";
				}
				// ****************  ES UN CAMPO EDITABLE  ***************************
				if(val.hasOwnProperty('extras') && val.extras.hasOwnProperty('editables')){
					if(val.extras.editables.find(function(e){return e == key})){
						var pcleid = key +'_pcle_id';
						var p = {'value':x[key],'label':key,'method':val.extras.edit_call,'id':x[pcleid],'parent_id':x['event_id']}
						it = editable.create(p);
					};
				}
				// **************** ES UN ID PARA ACTIVAR acciones  ********************
				// // console.log('edit1',key)
				// // console.log('edit2',val.headings[key])
				if(key == 'elem_id'){
					// AGREGO EL EVENTS_ID PARA QUE LO REFRESQUE UPDATES
					// if(!TOP.selected_ids.find(function(i){ i == x[key]})){TOP.selected_ids.push(x[key]);}
					it = "<button type=\"button\" class=\"btn btn-primary\" onClick=front_call({method:'get_elements',sending:true,kprevwin:true,caller:'"+val.caller+"',data:{elm_id:"+x[key]+"}})><i class=\"material-icons \">open_in_new</i></button>";
				}
				//*************  IMPRIMO EL TD  ******************************
				d += "<td scope=\'col\'class=\"align-middle text-center\">"+it+"</td>";
			}
		}
		return "<tr>"+d+"</tr>";
	});
   	this._screen += t2.join('');
   	this._screen += "</tr>";
   	this._screen += "</tbody><tfoot>"
	ft = false;
	this._screen += (Object.keys(val.headings).map(ftrc=>{return "<td class=\'text-center\'>"+(ft?ftrc:'')+"</td>"})).join('');
	this._screen +="</tfoot></table></div>";
	},
	get_screen:function(){return this._screen},
}


// RECIBE UN PCLE Y LO VUELVE EDITABLE  (ON CHANGE LLAMA A SU CALLER PIDIENDO UPDATE)
var editable = {
	_data:{},
	_screen:{},
	create:function (val){
		var obj = Object.create(this);
		obj.set(val);
		return obj._screen;
	},
	set: function(v){
		this._data = v;
		var parent_id = (v.hasOwnProperty('parent_id')?v.parent_id:0);
		v.type = 'text';
    // 	console.log('creating editable',v)
    // var isdate = v.value.match(/\d+\/\d+\/\d+/);
    // tengo que revisar el modo date
    // if(isdate != null){
    	// v.value = fx_date_to_dmy(v.value);
        // v.type = 'date';
    // }
    if(!isNaN(v.value)){
    	v.value = parseFloat(v.value);
    	v.type = 'number';
    }
    var r = "\<div class=\"form-group form-inline \">";
    if (v.hasOwnProperty('title') && v.title != ''){
    	r += "<label class=\"col-form-label\" for=\"edi_"+v.id+"\">"+v.title+"</label>";
    }
    r+= "<input type=\""+v.type+"\" class=\"form-control\" id=\"edi_"+v.id+"\" ";
    r+= (TOP.permisos >= 10?"readonly ":"");
    r+= (v.readonly == true ?"readonly ":"");

    r+= "value=\""+v.value+"\"  ";
    r+= (v.method == 'update_edi' && v.value == 0  ? "disabled=\"\"":"")
    r+= (v.method == 'update_edi'? "onChange=front_call({method:\""+v.method+"\",data:{\'id\':\""+v.id+"\"}}) ":"");
    r+= (v.method == 'update_edi'? "onblur=front_call({method:\""+v.method+"\",data:{\'id\':\""+v.id+"\"}}) ":"");
    r+= (v.method == 'update_rev_asignado'?"style=\'width: 7em;\'":'');
    r+= "onChange=front_call({method:\""+v.method+"\",data:{id:\""+v.id+"\",label:\""+v.label+"\",elem_id:\'"+TOP.curr_elem_id+"\',val:this.value,parent_id:"+parent_id+"}})";
    r+= (v.type == 'number'?" min=0 max=999999 style=\"width: 9em;\"":'');
    r+= ">";
				//console.log('to edit ',r);
				 // r+= (v.label.match(/_usd/))?"<div class=\"input-group-append\"><span class=\"input-group-text\">u$d</span></div>":"";
     r+= "</div>";
     this._screen = r;
 },
};



// VENTANA DE INPUTS EN JUMBOTRON CENTRADO CON TITULO Y BOT FINAL DE CALL TO ACTION
const jb = {
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get_screen:function(){return this._screen},
	set: function(v){
		this._screen = "<div class=\"row\" id=\"\">\
		<div class=\"col-sm-1\"></div>\
		<div class=\"col-sm-10\">\
		<div class=\"jumbotron jumbotron\">\
		<p class=\"lead\">"+v.title+"</p>\
		<div class=\"row\">"+v.content+"</div>\
		<div class=\"row\">\
		<div class=\"col-10\">"+v.footer+"</div>\
		<div class=\"col-2\"><div class=\"btn btn-primary\" onClick=\"front_call({method:'"+v.method+"',sending:true,action:'"+v.action+"'})\"  href=\"#\" role=\"button\">"+v.call_text+"</div>\
		</div>\
		</div>\
		</div>\
		<div class=\"col-sm-1\"></div>\
		</div>\
		";
	}

}



const jb2 = {
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get_screen:function(){return this._screen},
	set: function(v){
		this._screen = "<div class=\"jumbotron jumbotron p-4\">\
		<h4 class=\"text-center\">"+v.title+"</h4>\
		<div class=\"row\">"+v.content+"</div>\
		<hr class='my-4'>\
		<div class=\"row\">\
		<div class=\"col-10\">"+v.footer+"</div>\
		<div class=\"col-2 align-bottom\"><div class=\"btn btn-primary\" onClick=\"front_call({method:'"+v.method+"',sending:true,action:'"+v.action+"'})\"  href=\"#\" role=\"button\">"+v.call_text+"</div>\
		</div>\
		</div>\
		</div>";
	}

}

const jb_views =  {
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj._screen;
	},
	get_screen:function(){return this._screen},
	set: function(v){
		let r = "<div class=\'row d-flex justify-content-center m-5\'>";
		r += "<div class='col-xl-6 col-md-8 col-sm-10 text-center\'><img src=\'images/iso-lpt.png\'/>";
		r +="<div class=\'text-center m-2\'>"+v.title+"</div>";
		r +="<div class=\'text-center\' id=\'"+v.id+"\'></div>";
		r +="</div></div>";

		this._screen = r;
	}
}

const btn_views =  {
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj._screen;
	},
	get_screen:function(){return this._screen},
	set: function(v){
		let r = "<button type=\"button\" class=\"btn-prestamo btn-lg btn-block\"";
		// console.log('v',v.call);
		r += "onclick=\"front_call({method:'"+v.call.method+"',sending:"+v.call.sending+",action:'"+v.call.action+"'"+(v.call.hasOwnProperty('elm_id')?",elm_id:"+v.call.elm_id+"":'')+(v.call.hasOwnProperty('data')?",data:{elm_id:"+v.call.data.elm_id+"}":"")+"})\">"+v.tag+"</button>";
		this._screen = r;
	}
}


// CREA UN LISTGROUP RECIBE:OBJECT CON: title,body,footer,call_param y call_text
var list_group = {
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get_screen:function(){return this._screen},
	set: function(v){
		this._screen = "\
		<div class=\"list-group\">\
		<a href=\"#\" id=\"lg_title\"class=\"list-group-item list-group-item-action active\">"+v.title+"</a>\
		<a href=\"#\" id=\"lg_body\"class=\"list-group-item list-group-item-action\">"+v.item+"</a>\
		<a href=\"#\" id=\"lg_footer\"class=\"list-group-item list-group-item-action disabled\">\
		<div class=\"row\">\
		<div class=\"col-10\">"+v.footer+"</div>\
		<div class=\"col-2\"><div class=\"btn btn-primary\" onClick=\"front_call("+v.call_param+")\"  href=\"#\" role=\"button\">"+v.call_text+"</div>\
		</div>\
		</div>\
		</a>\
		</div>";
	}
}

// CARD DETALLE DE MOVIMIENTOS
const det_movs = {
	_data:{},
	_screen:'',
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get_screen:function(){
		return this._screen
	},
	get_pcle : function (arr,lbl){
		for(i in arr){
			if(arr[i].label == lbl){
				return arr[i].value
			}
		}
	},
	set: function(v){
		// let scr = "<div class='card bg-light '><div class='card-header d-flex justify-content-start'>";
		// scr +="<button type=\"button\" onClick=front_call({'method':'back'}) class=\"btn btn-primary\">";
		// scr +="<i class=\"material-icons \">arrow_back_ios</i></button><h5 class=\"pl-4\"> Detalle de Operación  </h5>\</div>";
		let scr = '<div class=\'card bg-light\'>';
		scr +="<div class=\'card-body d-flex flex-wrap justify-content-start \'><div class=\'p-2 m-2\'>";
		//*****  BOT REIMPRIMIR RECIBO
			scr += "<button type=\"button\" class=\"btn btn-primary\" onClick=\"print_elem('reprint_recibo')\"><i class=\"material-icons \">print</i></button>";
		scr +="</div>";
		//call a print recibo
		// console.log('call a print recibo', v)
		// recibo_reimprimir.create(v);
		for(var key in v){
			// console.log('OP',key , v[key])
				if(key != 'id' && key != 'cpr_id' && v[key] != null){
					if(key.match(/Monto/)){v[key] = parseFloat(v[key]).toLocaleString()};

					scr += data_box_small.create({id:0,label:key,value: v[key]}).get_screen();
				}
		}
		scr += "</div></div>";
		this._screen = scr;
	},
}


// CARD DE VER OPERACION DE CAJA
const op_caja = {
	_data:{},
	_screen:'',
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get_screen:function(){
		return this._screen
	},
	get_pcle : function (arr,lbl){
		for(i in arr){
			if(arr[i].label == lbl){
				return arr[i].value
			}
		}
	},
	get_kill_op_btn:function(v){
		var date_op = moment(new Date('02-19-2020')).format('DD/MM/YYYY');
  	var okdate = moment().subtract(2, 'days').startOf('day').format('DD/MM/YYYY')

		dpts = v.Fecha.split("/");
		date_op = moment(new Date(dpts[2],dpts[1]-1,dpts[0])).format('D/M/YYYY');
		if(date_op > okdate  || parseInt(TOP.permisos) < 1  ){
			return "<button type=\"button\" class=\"btn btn-primary mr-1\" onClick=\"front_call({method:\'anular_op\',sending:false,data:{id:\'"+v.id+"\'}})\"><i class=\"material-icons \">delete</i></button>";
		}
	},
	set: function(v){

		// console.log('tst op_date ',(date_op === hoy ))

		// let scr = "<div class='card bg-light '><div class='card-header d-flex justify-content-start'>";
		// scr +="<button type=\"button\" onClick=front_call({'method':'back'}) class=\"btn btn-primary\">";
		// scr +="<i class=\"material-icons \">arrow_back_ios</i></button><h5 class=\"pl-4\"> Detalle de Operación  </h5>\</div>";
		let scr = '<div class=\'card bg-light\'>';
		scr +="<div class=\'card-body d-flex flex-wrap justify-content-start p-2\'>";

		//call a print recibo
		// console.log('call a print recibo', v)
		// recibo_reimprimir.create(v);
		for(var key in v){
			// console.log('TOP',TOP)
				if(key != 'id' && key != 'cpr_id' && v[key] != null){
					if(key.match(/Monto/)){v[key] = accounting.formatMoney(parseFloat(v[key]), "$", 2, ".", ",")};
					// console.log('val: ',v[key]);
					if((parseInt(TOP.permisos) < 2 )){
						if(key.match(/Caja/)){v[key] = select_obj.create({label:'cuentas',value:v[key],title:'no_title'}).get_screen()};
						if(key.match(/Imputación/)){v[key] = select_obj.create({label:'cuentas_imputacion',value:v[key],title:'no_title'}).get_screen()};
						if(key.match(/Proveedor/)){v[key] = select_obj.create({label:'proveedor',value:v[key],title:'no_title'}).get_screen()};
					}else{
						if(key.match(/Caja/)){v[key] = (TOP.selects['cuentas'].find(function(i){return i.id == v[key]})? TOP.selects['cuentas'].find(function(i){return i.id == v[key]}).lbl:'-')};
						if(key.match(/Imputación/)){v[key] = (TOP.selects['cuentas_imputacion'].find(function(i){return i.id == v[key]})?TOP.selects['cuentas_imputacion'].find(function(i){return i.id == v[key]}).lbl:'-')};
					}
					scr += "<div class=\'p-2 m-1\'>"+data_box_small.create({id:0,label:key,value:v[key]}).get_screen()+"</div>";
				}
		}
		//*****  BOT ANULAR OPERACION
		scr += "<div class=\'p-2 m-1\'>"+this.get_kill_op_btn(v)+"</div>";


		//*****  BOT REIMPRIMIR RECIBO
		// if(v.cpr_id){
		// 	scr += "<button type=\"button\" class=\"btn btn-primary\" onClick=\"print_elem('reprint_recibo')\"><i class=\"material-icons \">print</i></button>";
		// }

		scr += "</div></div>";
		this._screen = scr;
	},
}

// **OLD** CARDS DE EDITAR CONTRATO vijo
var elem_card = {
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get_screen:function(){


		return this._screen
	},
	get_pcle : function (arr,lbl){
		for(i in arr){
			if(arr[i].label == lbl){
				return arr[i].value
			}
		}
	},
	set: function(v){
		this._data = v;
		// console.log('TOP ---',TOP);
		let scr = "<div class='card bg-light mb-3'><div class='card-header d-flex justify-content-start'>";
		scr += back_button.create();
		// scr +="<button type=\"button\" onClick=front_call({'method':'back'}) class=\"btn btn-primary\">";
		scr +="<i class=\"material-icons \">arrow_back_ios</i></button><h5 class=\"pl-4\"> Contrato </h5>\</div>";
		scr +="<div class=\'card-body d-flex flex-wrap justify-content-around \'>";
		for(var key in v){
			console.log('key',key);
			if( key != 'financ' && key != 'fec_ini' && key != 'lote' && key != 'cuotas' && v[key]['id'] != null && v[key]['value'] != ''){
				// console.log('labels',v[key]['label']);
				if(v[key]['label'] == 'saldo'){
					// console.log('saldo edi',v)
					let x = {'value':v[key]['value'],'label':v[key]['label'],'method':'update_elem_pcle','id':v[key]['id'],'parent_id':v[key]['elements_id']}
						z = editable.create(x);
					scr += data_box_small.create({
						id:0,
						label:(v[key]['title'] != ''?v[key]['title']:v[key]['label'].charAt(0).toUpperCase() + v[key]['label'].slice(1)),
						value:z
					}).get_screen();
				}else if(TOP.user_id == '484' && v[key]['label'] == 'cli_id'){
					let t = {'value':v[key]['value'],'label':v[key]['label'],'method':'update_elem_pcle','id':v[key]['id'],'parent_id':v[key]['elements_id']}
						z = editable.create(t);
					scr += data_box_small.create({
						id:0,
						label:(v[key]['title'] != ''?v[key]['title']:v[key]['label'].charAt(0).toUpperCase() + v[key]['label'].slice(1)),
						value:z
					}).get_screen();
				}else if(v[key]['label'] == 'cant_ctas_post_posesion'){
					// console.log('saldo edi',v)
					let cc = {'value':v[key]['value'],'label':v[key]['label'],'method':'update_elem_pcle','id':v[key]['id'],'parent_id':v[key]['elements_id']}
						zz = editable.create(cc);
					scr += data_box_small.create({
						id:0,
						label:(v[key]['title'] != ''?v[key]['title']:v[key]['label'].charAt(0).toUpperCase() + v[key]['label'].slice(1)),
						value:zz
					}).get_screen();
				}
				else{
					scr += data_box_small.create({
						id:0,
						label:(v[key]['title'] != ''?v[key]['title']:v[key]['label'].charAt(0).toUpperCase() + v[key]['label'].slice(1)),
						value: v[key]['value']

					}).get_screen();
				}
			}
		}
		scr += "</div></div>";
		this._screen = scr;
	},
}
// COLLAPSED PANEL
//  CARD CUOTAS EN EDITAR CONTRATO
const collapsed_panel = {
	_screen:'',
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get_screen:function(){
		return this._screen
	},
	set: function(v){
		this._screen += "<div class='jp-card'><div class='card-header d-flex justify-content-between' id='panel_heading'><div class='card-title'>";
		this._screen += "<button type='button' class='btn-normal' data-toggle='collapse' data-target='#collapse_"+v.id+"' aria-expanded='true' aria-controls='panel_body'><i class='material-icons' >format_line_spacing</i></button>";
		this._screen +=v.title+"</div></div>";
		this._screen += "<div id='collapse_"+v.id+"'  class='collapse' aria-labelledby='panel_heading'>";
		this._screen +="<div class='card-body d-flex justify-content-around' id='panel_body"+v.id+"' >"+v.content+"</div></div></div>";
	}
}

//  CARD CUOTAS EN EDITAR CONTRATO
var cuotas_card = {
	_screen:'',
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get_screen:function(){
		return this._screen
	},
	set: function(v){
		this._screen += "<div class=\'card bg-light mb-2\'><div class=\'card-header d-flex justify-content-start\' id=\"heading_lt\" ><h5 class=\"d-flex justify-content-start pl-4\">";
			//**** COLAPSE BUTTON
		this._screen +="<button type=\"button\" class=\"btn btn-sm btn-primary \"  data-toggle=\"collapse\" data-target=\"#collapse_lt\" aria-expanded=\"true\" aria-controls=\"cuotas_card_body\"><i class=\"material-icons \">more_vert</i></button>";
		this._screen +="<span class=\"pl-2\">"+v.title_tag+":&nbsp;"+v.title_val+"</span><span class=\"pl-4\">"+v.fec_ini_title_tag+":&nbsp;"+v.fec_ini_val+"</span></h5></div>";
		this._screen += "<div id=\"collapse_lt\"  class=\"collapse show\" aria-labelledby=\"heading_lt\" >";
		this._screen +="<div class=\'card-body d-flex justify-content-around\' id=\"cuotas_card_body\"></div></div></div>";
	}
}

// CARD CUOTAS SERVICIOS EN EDITAR CONTRATO
var servicios_card = {
	_screen:'',
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get_screen:function(){
		return this._screen
	},
	set: function(v){
		this._screen ="<div class='card bg-light mb-2'><div class='card-header d-flex justify-content-start' id=\"heading_srv_"+v.index+"\"><h5 class=\"d-flex justify-content-start pl-4\">";
		//**** COLAPSE BUTTON
		this._screen +="<button type=\"button\" class=\"btn btn-sm btn-primary\"  data-toggle=\"collapse\" data-target=\"#collapse_srv_"+v.index+"\" aria-expanded=\"true\" aria-controls=\"servs_card_body_"+v.index+"\"><i class=\"material-icons \">more_vert</i></button>";
		this._screen +="<span class=\"pl-2\">"+v.title_val+"</span><span class=\"pl-4\">"+v.fec_ini_title_tag+":&nbsp;"+v.fec_ini_val+"</span></h5></div>";
		this._screen += "<div id=\"collapse_srv_"+v.index+"\"  class=\"collapse show\" aria-labelledby=\"heading_srv_"+v.index+"\" >";
		this._screen +="<div class=\'card-body d-flex justify-content-around\' id=\"servs_card_body_"+v.index+"\"></div></div></div>";
	}
}

const print_panel = {
	_data:{},
	_screen:'',
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get_screen:function(){
		return this._screen
	},
	set: function(v){
		this._data = v;
		this._screen =
		"<div class='card d-flex flex-fill bg-light'>\
		<div class='card-header  d-flex justify-content-around'>\
		<h5 class=\"pl-4\">"+v.title+"</h5>";
		this._screen +="</div><div class=\'card-body d-flex flex-wrap justify-content-around\' id=print_pnl_\""+v.pnl_id+"_body\">"+v.content+"</div></div>";
	}
}

//  UN PANEL VACIO
var panel = {
	_data:{},
	_screen:'',
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get_screen:function(){
		return this._screen
	},
	set: function(v){
		this._data = v;
		this._screen = "<div class='card   mt-5 d-flex flex-fill bg-light'> ";
		this._screen += "<div class='card-header  d-flex justify-content-between'>";
		this._screen += back_button.create();
		this._screen += "<div class=\"card-title pl-4\">"+v.title+"</div>";
		if(v.hasOwnProperty('print_button')){
			this._screen +="<button type=\"button\" class=\"btn btn-primary\" onClick=\"print_elem(\'"+v.print_option+"\')\"><i class=\"material-icons \">print</i></button>";
		};
		this._screen +="</div><div class=\'card-body d-flex flex-wrap justify-content-around\' id=pnl_\""+v.pnl_id+"_body\">"+v.content+"</div></div>";
	}
}

//  BOXES PARA EL PANEL
var data_box = {
	_data:{},
	_screen:'',
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get_screen:function(){
		return this._screen
	},
	set: function(v){
		this._data = v;
		n = "<div class=\'card border-dark mb-3 \' style=\"max-width: 22rem;\" id=\"data_box"+v.id+"\">";
		n+="<div class=\"card-header\" onClick=\'"+v.onclick+"\' >"+v.label+"</div>"
		n+="<div class=\"card-body text-center \"><legend>"+v.value+"</legend></div></div>";
		this._screen = n;
	}
}

const buton_primay = {
	_screen:'',
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get_screen:function(){
		return this._screen
	},
	set: function(v){
		let n = "<div class=\"col d-flex flex-wrap justify-content-start m-3 p-2\">";
		n += "<button type=\"button\" class=\"btn btn-primary\" onClick=front_call({method:\'"+v.method+"\',action:\'"+v.action+"\',data:'"+v.data+"',sending:"+v.sending+"})>"+v.label+"</button>";
		n +="</div>";
		this._screen = n;
	}
}

const data_box_small = {
	_screen:'',
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get_screen:function(){
		return this._screen
	},
	set: function(v){
		let edit = '';
		if(v.hasOwnProperty('edit_btn')){
			// console.log('edit box',v,TOP)
			edit = "<button type=\"button\" class=\"btn-normal\" onclick=\"front_call({method:'edit_dialog',sending:false,curr_val:'"+v.value+"',pcle_lbl:'"+v.pcle_lbl+"',elm_id:'"+TOP.curr_elem_id+"',container_id:'dbx_cl_body_"+v.id+"\'})\"><i class=\"material-icons \">create</i></button></li>";
		}
		if(v.hasOwnProperty('collapsed')){
			n = "<div class=\'card\' id=\'dbx_clp\'>";
			n +="<div class=\"card-header d-flex justify-content-between\" id=\'dbx_clp_head\'>";
			n += "<div class=\'card-title\'>"+v.label+"</div>";
			n += edit;
			n +="<button type=\"button\" class=\"btn-normal\"  data-toggle=\"collapse\" data-target=\"#dbx_clp_area\" aria-expanded=\"true\" aria-controls=\"dbx_cl_body_"+v.id+"\"><i class=\"material-icons \">remove_circle_outline</i></button>";
			n +="</div>";
			n +="<div id='dbx_clp_area' class='collapse show' aria-labelledby='dbx_clp_head' style=''>";
			n +="<div class=\"card-body p-1 text-center\" id=\"dbx_cl_body_"+v.id+"\">";
			n +=v.value;
			n +="</div></div></div>";
			this._screen = n;

		}else{
			n = "<div class=\'card \'>";
			n +="<div class=\"card-header d-flex justify-content-between\">";
			n += "<div class=\'card-title\'>"+v.label+"</div>";
			n += edit +"</div>";
			n +="<div class=\"card-body p-1 text-center\" id=\"data_box_"+v.id+"\">";
			n += v.value;
			n += "</div></div>";
			this._screen = n;
		}
	}
}

var box_contnt_list = {
	_screen:'',
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get_screen:function(){
		return this._screen
	},
	set: function(v){
		n = "<ul class=\"list-group list-group-flush\">";
		for (var i = 0; i < v.length; i++) {

				n +="<li class=\"list-group-item  d-flex justify-content-between\">"+v[i].label+"<span class=\"card-text \"><strong>"+v[i].val+"</strong></span></li>";
			}
		n +="</ul>";
		this._screen = n;
	}
}


	// *************************************************************************
  	// *** 19/12/2019
  	// *** RETORNA LOS ARCHIVOS SUBIDOS A LA CARPETA EN PARAM
  	// ***
  	// ************************************************************************
var uploaded_files_boxes = {
	_screen:'',
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get_screen:function(){
		return this._screen
	},
	set: function(v){
		let n = "";
		for (var i = 0; i < v.length; i++) {
				n += this.get_uploaded_files(v[i]);
			}
		this._screen = n;
	},
		// ****************** UPLOADED FILES ******************
	get_uploaded_files: function(folder){
		// let data = TOP.data.uploaded_files.map(i=>{return {'Nombre de Archivo':"<a href=\"uploads/lote_data/"+TOP.data.lote.lote_nom+"/"+i+"\" target=\"_blank\">"+i+"</a>"};});
		let source =[];x=[];data=[];
		if(TOP.data.hasOwnProperty('uploaded_files')){
			source = TOP.data.uploaded_files;
			if(source.hasOwnProperty(folder)){
				x = TOP.data.uploaded_files[folder];
			}
		}
		if(x.length > 0){
			data = x.map(i=>{return {'':"<a href=\"#\" onclick=front_call({method:'embed_to_modal',src:\"./uploads/"+folder+"/"+i+"\",title:'"+i+"'})>"+i+"</a>"};});
			r ="<div class=\'col d-flex flex-wrap p-2 \' id=\'col_"+folder+"\' >";
			// ** CREA EL TABLE Y EL DATA BOX PARA LOS FILES UPLD
			r += data_box_small.create({label: ' '+x.length+' '+(x.length == 1?'Archivo de ':'Archivos de ')+' '+(folder.search('web_cli')> -1 ?" Clientes":" Administradores"),id: folder+"_panel_uploaded",value: otbl.create(data,folder+'_tbl_uploaded_files')}).get_screen();
			r += "</div>";
			return r;
		}else{
			return "<div class=\'col d-flex flex-wrap p-2 \' id=\'col_"+folder+"\' ></div>";
		}

	},
	refresh_uploaded_files:function(folder){
		console.log('ob refresh',folder)
		console.log('TOP uploaded',TOP.data.uploaded_files)
		//** PARAM  FOLDER CONTIENE NOMBRES
		if(Array.isArray(TOP.data.uploaded_files[folder])){
			// clear databox
			$('#col_'+folder).html(this.get_uploaded_files(folder));
			// console.log(' refresh',$('#data_box_'+folder+'_panel_uploaded').length)
			//
			// if($('#data_box_'+folder+'_panel_uploaded').length){
			// 	// exists
			// 	console.log('updating panel',TOP.data.uploaded_files[folder])
			// 	$('#data_box_'+folder+'_panel_uploaded').html(otbl.create(TOP.data.uploaded_files[folder],folder+'_tbl_uploaded_files'));

			// }
			// else{
			//
			// }
		}
	},
	//  ***************************************************

}



/* CARD FOOTER CON BOTON
<div class='card-body d-flex justify-content-around ' id=\"cuotas_card_footer\">\
	<button type=\"button\" id=\"bot_\" class=\"btn btn-secondary\" onClick=front_call({method:'set_pago_cuotas'})>Action</button>\
</div>\
*/


	// ** RESUMEN DE CUENTA
var clpsd_cards = {
	_data:{},
	_screen:'',
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	set: function(val){
		TOP.data = val;
		TOP.detalle_ctas_arr = new Array();
		TOP.titulo_det_ctas_arr = new Array();
		TOP.detalle_ctas_a_pagar_arr = new Array();
		TOP.titulo_det_ctas_a_pagar_arr = new Array();
		// // console.log('TOP data',TOP.data)
		this._data = val;
		if(this._data.lote.rscn_data.hasOwnProperty('Nombre')){
			this._screen = this.get_card1() + this.get_card_rscn() + this.get_card2() + (this.get_card_canclds()?this.get_card_canclds():'');
		}else{
			this._screen = this.get_card1() + this.get_card2() + (this.get_card_canclds()?this.get_card_canclds():'');
		}

	},
	get_fec_init : function(){
		let r = "<div id=\'btns_pago_srv\' class=\'col d-flex justify-content-end pt-2\'>";
		r +="<button type=\"button\" id=\"btn_curr_state\" class=\"btn btn-success\">"+this._data.lote['fec_init']+"</button>";
		r += "</div></div>";
		return r;
	},

	//**************** 25 junio 2020
	//** DROPDOWN DE ESTADO DEL CONTRATO
	get_curr_state:function(){
		let r = "<div class=\'col d-flex justify-content-center pt-2\'>";
		r += dropdown.create({row:this._data.lote.estado_contrato,style:'btn-primary',updateMethod:'set_curr_state'});
		r += "</div>";
		return r
	},


	get_header : function(){
		let r = "";
		console.log('cotit length',this._data.lote.datos_boleto.cotit_nomap.length);
		console.log('cotit',this._data.lote.datos_boleto.cotit_nomap);

		r += "<div class='col d-flex flex-wrap card-title justify-content-center'><i class=\"material-icons \">perm_identity</i>" + this._data.lote.datos_boleto.tit_nomap;
		r += ' '+(this._data.lote.datos_boleto.cotit_nomap.length > 2?" y  "+this._data.lote.datos_boleto.cotit_nomap:'')
		r += "</div>";
		r += "<div class='col d-flex flex-wrap  card-title justify-content-center'><i class=\"material-icons \">phone</i>"+this.get_telefono()+"</div>";
		r += "<div class='col d-flex flex-wrap card-title justify-content-center'><i class=\"material-icons \">home</i>"+this.get_domic()+"</div>";
		return r;
	},
	get:function(card,prop){
		if(this._data.hasOwnProperty(card) && this._data[card].hasOwnProperty(prop)){
			return this._data[card][prop];
		}
	},
	get_screen:function(){
		return this._screen
	},
	get_domic : function(){

		return  this.get_gpcle(this._data.lote,'cli_data','domicilio');
	},
	get_telefono : function(){
		let t1 = this.get_gpcle(this._data.lote,'cli_data','celular_difusion');
		let t2 = this.get_gpcle(this._data.lote,'cli_data','celular');
		let t3 = this.get_gpcle(this._data.lote,'cli_data','telefono');
		let r = '';
		if(t1){r+= t1+", "};
		if(t2){r+=t2+", "};
		if(t3){r+=t3};
		return r;
	},
	get_plan : function (){
		return this._data.lote.financ;
	},
	get_pcle : function (card,p,lbl){
		if(this._data.hasOwnProperty(card) && this._data[card].hasOwnProperty(p) && this._data[card][p].length > 0){
			var r = this._data[card][p].filter(function(i){return i.label === lbl});
			if(r.length >0){
				return r[0]['value'];
			}
		}
	},
	get_gpcle : function (arr,p,lbl){

		if(arr && arr.hasOwnProperty(p) && arr[p].length > 0){
			var r = arr[p].filter(function(i){return i.label === lbl});
			if(r.length >0){
				return r[0]['value'];
			}
		}
	},
	get_title : function (c,p,t){
		if(this._data[c].hasOwnProperty(p) && this._data[c][p].length > 0){
			var r = this._data[c][p].filter(function(i){return i.label === t});
			if(r.length >0){
				return r[0]['title'];
			}

		}
	},
	get_nro_cta : function(){
		if(this._data.lote['cta_upc'].length > 0 && this._data.lote['cta_upc'][0].hasOwnProperty('cuota') && this._data.lote['cta_upc'][0]['cuota'].hasOwnProperty('id')){
			var x = this._data.lote['cta_upc'][0]['pcles'].filter(function(i){return i.label == 'nro_cta'});
			return x[0].value;
		}else {
			return ' ';
		}
	},
	get_cta_upc : function(){
		return (this._data.lote.hasOwnProperty('cta_upc') && this._data.lote.cta_upc !== 0 ? parseFloat(this._data.lote.cta_upc.pcles.monto.value):0);
	},
	get_cant_ctas_restantes : function(){
		return this._data.lote.ctas_restantes.events.length;
	},
	get_cant_ctas_pagas : function(){
		return parseInt(this._data.lote.ctas_pagas.events.length) ;
	},
	get_cant_ctas_pftrm :  function(){
		return this._data.lote.ctas_pft.events.length;
	},
	get_cant_ctas_adelant:function(){
		return this._data.lote.ctas_adelantadas.events.length;
	},
	get_cant_ctas_mora : function(){
		return this._data.lote.ctas_mora.events.length;
	},
	get_tot_en_mora : function(){
		return parseInt(this._data.lote.ctas_mora.total);
	},
	get_tot_restantes :function(){
		return parseInt(this._data.lote.ctas_restantes.total);
	},
	get_cant_pagas : function(e,caller){
		let r = 0;r1='',r2='';r3='';
		d = new Array();
		ptrm = e.ctas_pagas.events.length + e.ctas_adelantadas.events.length;
		ftrm = e.ctas_pft.events.length;
		r1 = ptrm +  ftrm + " Ctas. Pagadas";
		if(ptrm > 0){
			r2 = "<br/><span class=\'small text-success\' >"+ ptrm + " en Termino</span>";
		}
		if(ftrm > 0){
			r3 = "<br/><span class=\'small text-danger\'> "+ftrm+" fuera de termino </span>";
		}

		d = e.ctas_pagas.events.concat(e.ctas_adelantadas.events,e.ctas_pft.events);

		ax = TOP.detalle_ctas_arr.push(ordenar_por_fecha(d));

		TOP.titulo_det_ctas_arr[ax-1] = caller;
		ro = {method:'detalle_ctas',det_arr_index:ax-1};
		let res = "<div class=\'text-left\'><a href=\"#\" onClick=front_call("+JSON.stringify(ro)+")>"+r1+"</a>"+' '+r2+' '+r3+"</div>";
		return res;
	},
	get_tot_pagado : function(e){
		let t = e.ctas_pagas.total + e.ctas_adelantadas.total + e.ctas_pft.total
		return (isNaN(t)?'err':t);
	},
	get_tot_a_pagar : function(e){
		let x = 0;c = 0;a=0;
		if(e.cta_upc.events.length > 0){
			// EN EL ARRAY EVENTS LA CUOTA  UPCOMING ES EL ULTIMO ELEMENTO LOS ELEMENTOS PREVIOS
			// SON CUOTAS VENCIDAS IMPAGAS
			c += parseInt(get_pcle(e.cta_upc.events[(e.cta_upc.events.length-1)],'monto_cta'));
			x = e.ctas_mora.total + (c * e.ctas_restantes.events.length) ;
			// SI ES CTA ANTICIPO O CERO, MONTO CTA ES DEL PRIMER ELEMENTO DE RESTANTES
			if(e.cta_upc.events[e.cta_upc.events.length-1].ord_num === '0.0' || c == 0){
					a = parseInt(get_pcle(e.ctas_restantes.events[0],'monto_cta'))
					c = parseInt(get_pcle(e.ctas_restantes.events[1],'monto_cta'));
					x = a + (c * e.ctas_restantes.events.length - 1) ;
			}
		}else{
			if(e.ctas_restantes.total > 0){
				c = parseInt(get_pcle(e.ctas_restantes.events[0],'monto_cta'));
				x = e.ctas_mora.total + (c * e.ctas_restantes.events.length) ;
			}
		}

		return (isNaN(x)?'error - 2345 get_tot_a_pagar':x);
	},

	get_cant_a_pagar : function(e,caller){
		let dta = new Array(); x1='';x2='';x3='';

		x1 = e.ctas_mora.events.length + e.ctas_restantes.events.length
		if(e.ctas_restantes.events.length > 0){
			x2 = "<br/><span class=\'small text-success\'> "+e.ctas_restantes.events.length+" en fecha </span>";
		}
		if(e.ctas_mora.events.length > 0){
			x3 = "<br/><span class=\'small text-danger\'> "+e.ctas_mora.events.length+" en mora </span>";
		}

		dta = e.ctas_mora.events.concat(e.ctas_restantes.events);
		ax = TOP.detalle_ctas_arr.push(dta);
		TOP.titulo_det_ctas_arr[ax-1] = caller;
		ret_obj = {method:'detalle_ctas',det_arr_index:ax-1};
		return "<a href=\"#\" onClick=front_call("+JSON.stringify(ret_obj)+")>"+x1+" Ctas. A Pagar</a>"+x2+x3;
	},
	get_monto_cta_actual(c){
		return c.cta_upc.total;
		// if(c.ctas_restantes.events.length > 0){
		// 	let r = c.ctas_restantes.events[0]['pcles'].filter(function(i){return i.label === 'monto_cta'});
		// 	return parseFloat(r[0]['value']);
		// }else{
		// 	if(c.cta_upc.total > 0){
		// 		return c.cta_upc.total;
		// 	}
		// 	return 0;
		// }
	},

	ctas_lote: function(){
		TOP.ahorrado_tot = this._data.lote.ctas_ahorro;
		let tbl_data_lote = [{
			'PLAN CONTRATADO' : this.get_plan(),
			'AHORRO':this._data.lote.ctas_ahorro,
			'VALOR DE LA CUOTA' : this.get_monto_cta_actual(this._data.lote),
			'CUOTAS PAGADAS' : this.get_cant_pagas(this._data.lote,this.get_plan()),
			'MONTO PAGADO' : this.get_tot_pagado(this._data.lote),
			'CUOTAS A PAGAR' : this.get_cant_a_pagar(this._data.lote,this.get_plan()),
			'MONTO A PAGAR': this.get_tot_a_pagar(this._data.lote),

			// 'Limite Cred.' : this._data.lote.mto_reintegro,
			// 'ACCIONES': this._data.lote.ctas_acciones
			//'En Mora':this.get_tot_en_mora(),
			//'Fuera Term.':this.get_cant_ctas_pftrm()
			}];
		if(TOP.permisos < 10){
			tbl_data_lote[0]['ACCIONES'] = this._data.lote.ctas_acciones;
		}
		return otbl.create(tbl_data_lote,'tbl_ctas_lote');

	},
	ctas_srv: function(){
		let tbl_data_srv = [];
		for (let i = 0 ; i < this._data.srv.length ; i ++){
			if(this._data.srv[i].srvc_name.search(/\b(\w*prestamo\w*)\b/ig) === -1 ){
				if(this._data.srv[i].ctas_restantes.events.length > 0 || this._data.srv[i].ctas_mora.events.length > 0 ){
					TOP.ahorrado_tot += this._data.srv[i].ctas_ahorro;
					var ds = tbl_data_srv.push({
						'SERVICIO CONTRATADO' : this._data.srv[i].srvc_name,
						'AHORRO':this._data.srv[i].ctas_ahorro,
						'VALOR DE LA CUOTA' : this.get_monto_cta_actual(this._data.srv[i]),
						'CUOTAS PAGADAS' : this.get_cant_pagas(this._data.srv[i],(this._data.srv[i].srvc_name?(this._data.srv[i].srvc_name).substring(0,25):'')),
						'MONTO PAGADO' : this.get_tot_pagado(this._data.srv[i]),
						'CUOTAS A PAGAR' : this.get_cant_a_pagar(this._data.srv[i],(this._data.srv[i].srvc_name?(this._data.srv[i].srvc_name).substring(0,25):'')),
						'MONTO A PAGAR' : this.get_tot_a_pagar(this._data.srv[i]),
					});
					if(TOP.permisos < 10){
						tbl_data_srv[ds-1]['ACCIONES'] = this._data.srv[i].ctas_acciones;
					}

				}
			}

		}
		// if(TOP.permisos < 10){
		// 	tbl_data_lote[0]['ACCIONES'] = this._data.lote.ctas_acciones;
		// }

		return otbl.create(tbl_data_srv,'tbl_ctas_srv');

	},

	ctas_prest: function(){
		let tbl_data_prest = [];
		for (let i = 0 ; i < this._data.srv.length ; i ++){
			// console.log('prest ',this._data.srv[i].srvc_name.search(/\b(\w*prestamo\w*)\b/ig));
			if(this._data.srv[i].srvc_name.search(/\b(\w*prestamo\w*)\b/ig) === 0){
				if(this._data.srv[i].ctas_restantes.events.length > 0 || this._data.srv[i].ctas_mora.events.length > 0 ){

					TOP.ahorrado_tot += this._data.srv[i].ctas_ahorro;
					var dp = tbl_data_prest.push({
						'SERVICIO CONTRATADO' : this._data.srv[i].srvc_name,
						'AHORRO':this._data.srv[i].ctas_ahorro,
						'VALOR DE LA CUOTA' : this.get_monto_cta_actual(this._data.srv[i]),
						'CUOTAS PAGADAS' : this.get_cant_pagas(this._data.srv[i],(this._data.srv[i].srvc_name?(this._data.srv[i].srvc_name).substring(0,25):'')),
						'MONTO PAGADO' : this.get_tot_pagado(this._data.srv[i]),
						'CUOTAS A PAGAR' : this.get_cant_a_pagar(this._data.srv[i],(this._data.srv[i].srvc_name?(this._data.srv[i].srvc_name).substring(0,25):'')),
						'MONTO A PAGAR' : this.get_tot_a_pagar(this._data.srv[i]),
					});
					if(TOP.permisos < 10){
						tbl_data_prest[dp-1]['ACCIONES'] = this._data.srv[i].ctas_acciones;
					}
				}
			}
		}
		if(tbl_data_prest.length > 0){
			let tit_prest = "<div class='title-prestamos'><img class=\'jp-icon\' src=\'images/icons/prestamos.png\'></img> ESTADO DE CUENTA DE"+(TOP.permisos >= 10 ?" TUS ":" ")+"PRESTAMOS</div>";

			return tit_prest + otbl.create(tbl_data_prest,'tbl_ctas_prest');
		}else{
			return "";
		}
	},

	// ** DEPRECATED CTAS TBL
	ctas_tbl : function(){
		let tbl_data = [{
			'Plan / Servicio' : this.get_plan(),
			'Cant. Pagas' : this.get_cant_pagas(this._data.lote,this.get_plan()),
			'Monto Pagado' : this.get_tot_pagado(this._data.lote),
			'Cant. a Pagar' : this.get_cant_a_pagar(this._data.lote,this.get_plan()),
			'Monto a Pagar': this.get_tot_a_pagar(this._data.lote),
			'Monto Cta. Actual' : this.get_monto_cta_actual(this._data.lote),
			// 'Limite Cred.' : this._data.lote.mto_reintegro,
			'Acciones': this._data.lote.ctas_acciones
			//'En Mora':this.get_tot_en_mora(),
			//'Fuera Term.':this.get_cant_ctas_pftrm()
			}];
		let gtot_pagado = this.get_tot_pagado(this._data.lote);
		gtot_cant_pagas = this._data.lote.ctas_pagas.events.length + this._data.lote.ctas_adelantadas.events.length + this._data.lote.ctas_pft.events.length;
		gtot_a_pagar = this.get_tot_a_pagar(this._data.lote);
		gtot_cant_a_pagar = this._data.lote.ctas_mora.events.length + this._data.lote.ctas_restantes.events.length;
		gtot_cta_actual = this.get_monto_cta_actual(this._data.lote);
		for (let i = 0 ; i < this._data.srv.length ; i ++){
			if(this._data.srv[i].ctas_restantes.events.length > 0 || this._data.srv[i].ctas_mora.events.length > 0 ){
				gtot_pagado += this.get_tot_pagado(this._data.srv[i])
				gtot_cant_pagas += this._data.srv[i].ctas_pagas.events.length + this._data.srv[i].ctas_adelantadas.events.length + this._data.srv[i].ctas_pft.events.length;
				gtot_a_pagar += this.get_tot_a_pagar(this._data.srv[i]),
				gtot_cant_a_pagar += this._data.srv[i].ctas_mora.events.length + this._data.srv[i].ctas_restantes.events.length,
				gtot_cta_actual += this.get_monto_cta_actual(this._data.srv[i]),
				tbl_data.push({
					'Plan / Servicio' : this._data.srv[i].srvc_name,
					'Cant. Pagas' : this.get_cant_pagas(this._data.srv[i],(this._data.srv[i].srvc_name?(this._data.srv[i].srvc_name).substring(0,25):'')),
					'Monto Pagado' : this.get_tot_pagado(this._data.srv[i]),
					'Cant. a Pagar' : this.get_cant_a_pagar(this._data.srv[i],(this._data.srv[i].srvc_name?(this._data.srv[i].srvc_name).substring(0,25):'')),
					'Monto a Pagar' : this.get_tot_a_pagar(this._data.srv[i]),
					'Monto Cta. Actual' : this.get_monto_cta_actual(this._data.srv[i]),
					// 'Limite Cred.' : 0,
					'Acciones': this._data.srv[i].ctas_acciones
					// 'En Mora':this._data.srv[i].ctas_mora.events.length,
					// 'Fuera Term.':this._data.srv[i].ctas_pft.events.length,
				});
			}
		}
		if(tbl_data.length > 1){
			tbl_data.push({
				'Plan / Servicio' : 'TOTALES',
				'Cant. Pagas' : gtot_cant_pagas,
				'Monto Pagado' : gtot_pagado,
				'Cant. a Pagar' : gtot_cant_a_pagar,
				'Monto a Pagar' : gtot_a_pagar,
				'Monto Cta. Actual' : gtot_cta_actual,
				// 'Limite Cred.' : 0,
				'Acciones' : ' '
					// 'En Mora':this._data.srv[i].ctas_mora.events.length,
					// 'Fuera Term.':this._data.srv[i].ctas_pft.events.length,
				});

		}
		// console.log('actions',tbl_data);
		return otbl.create(tbl_data,'tbl_ctas');
	},

	//  ******************* ctas tble no actions ************
	ctas_tbl_noacc : function(){
		let tbl_data = [{
			'Plan / Servicio' : (this.get_plan()?(this.get_plan()).substring(0,25):''),
			'Cant. Pagas' : this.get_cant_pagas(this._data.lote,(this.get_plan()).substring(0,25)),
			'Monto Pagado' : this.get_tot_pagado(this._data.lote),
			'Cant. a Pagar' : this.get_cant_a_pagar(this._data.lote,(this.get_plan()).substring(0,25)),
			'Monto a Pagar': this.get_tot_a_pagar(this._data.lote),
			'Monto Cta. Actual' : this.get_monto_cta_actual(this._data.lote)
			}];
		let gtot_pagado = this.get_tot_pagado(this._data.lote);
		gtot_cant_pagas = this._data.lote.ctas_pagas.events.length + this._data.lote.ctas_adelantadas.events.length + this._data.lote.ctas_pft.events.length;
		gtot_a_pagar = this.get_tot_a_pagar(this._data.lote);
		gtot_cant_a_pagar = this._data.lote.ctas_mora.events.length + this._data.lote.ctas_restantes.events.length;
		gtot_cta_actual = this.get_monto_cta_actual(this._data.lote);
		for (let i = 0 ; i < this._data.srv.length ; i ++){
			if(this._data.srv[i].ctas_restantes.events.length > 0 || this._data.srv[i].ctas_mora.events.length > 0 ){
				gtot_pagado += this.get_tot_pagado(this._data.srv[i])
				gtot_cant_pagas += this._data.srv[i].ctas_pagas.events.length + this._data.srv[i].ctas_adelantadas.events.length + this._data.srv[i].ctas_pft.events.length;
				gtot_a_pagar += this.get_tot_a_pagar(this._data.srv[i]),
				gtot_cant_a_pagar += this._data.srv[i].ctas_mora.events.length + this._data.srv[i].ctas_restantes.events.length,
				gtot_cta_actual += this.get_monto_cta_actual(this._data.srv[i]),
				tbl_data.push({
					'Plan / Servicio' : (this._data.srv[i].srvc_name)?(this._data.srv[i].srvc_name).substring(0,25):'',
					'Cant. Pagas' : this.get_cant_pagas(this._data.srv[i],(this._data.srv[i].srvc_name?(this._data.srv[i].srvc_name).substring(0,25):'')),
					'Monto Pagado' : this.get_tot_pagado(this._data.srv[i]),
					'Cant. a Pagar' : this.get_cant_a_pagar(this._data.srv[i],(this._data.srv[i].srvc_name?(this._data.srv[i].srvc_name).substring(0,25):'')),
					'Monto a Pagar' : this.get_tot_a_pagar(this._data.srv[i]),
					'Monto Cta. Actual' : this.get_monto_cta_actual(this._data.srv[i])
				});
			}
		}
		if(tbl_data.length > 1){
			tbl_data.push({
				'Plan / Servicio' : 'TOTALES',
				'Cant. Pagas' : gtot_cant_pagas,
				'Monto Pagado' : gtot_pagado,
				'Cant. a Pagar' : gtot_cant_a_pagar,
				'Monto a Pagar' : gtot_a_pagar,
				'Monto Cta. Actual' : gtot_cta_actual
				});

		}

		return otbl.create(tbl_data,'tbl_ctas_noacc');
	},
	//  ***************************************************


	//  ******************* botones Ingresar Pago /  New servicio / Print resumende cuenta  ************
	get_buttons_bar : function(){
		let r = "";
		//*** LOTE RESCINDIDO
		if(this._data.lote.rscn_data && this._data.lote.curr_state == 'RESCINDIDO'){
			r +="<div class='row d-flex justify-content-between mb-2'>" ;
			//  BOT SUBIR ARCHIVO
			r += "<button type='button' class='btn-normal m-1 ' id='button_file_upload' onClick=front_call({method:'lotes_file_upload',sending:false})>SUBIR ARCHIVO</button>";
			// BOT IMPRIMIR
			r +="<button type='button' class='btn-normal' id='print_button_res_cta' onclick=\"print_resumen_de_cta()\">IMPRIMIR EL ESTADO DE CUENTA</button>";
			r += "</div>";
			return r;
		}
		//**** PERMISOS USUARIOS OFICINA SOLO SUBIR ARCHIVOS O IMPRIMIR
		if(TOP.permisos == 3){
			r +="<div class='row d-flex justify-content-between mb-2'>";
			//  BOT SUBIR ARCHIVO
			r += "<button type='button' class='btn-normal m-1 ' id='button_file_upload' onClick=front_call({method:'lotes_file_upload',sending:false})>SUBIR ARCHIVO</button>";
			// BOT IMPRIMIR
			r +="<button type='button' class='btn-normal' id='print_button_res_cta' onclick=\"print_resumen_de_cta()\">IMPRIMIR EL ESTADO DE CUENTA</button>";
			r += "</div>";
			// BOT IMPRIMIR BOLETO
			r +="<button type='button' class='btn-normal' id='print_button_boleto' onclick=front_call({method:'print_boleto',sending:false})>IMPRIMIR BOLETO</button>";
			r += "</div>";

			return r;
		}



		//**** PERMISOS ADMINISTRACION SIN CUOTAS EN MORA
		if(TOP.permisos < 5 && this._data.lote.ctas_mora.events.length < 4 ){
			r +="<div class='row d-flex justify-content-start mb-2'>";
			// DISPONIBLE DE CREDITO
			r +="<div class\'col-sm-4 col-md-2 \'><div class=\' p-3 text-center\'>Credito Disponible: "+accounting.formatMoney(parseFloat(this._data.lote.mto_reintegro), "$ ", 0, ".", ",")+"</div></div>";
			r += "<div class\'col-sm-8 col-md-10 \'>"
			// BOT CUOTAS
			r += "<button type=\"button\" id=\"bot_pago\" class=\"btn-normal m-1\" onClick=front_call({method:'set_pago_cuotas',sending:'true',action:'call',steps_back:true})>CUOTAS PENDIENTES</button>";
			// BOT SERVICIO
			r += "<button type=\"button\" id=\"bot_new_service\" class=\"btn-normal m-1\" onClick=front_call({method:'new_service_elem',action:'call',sending:true})>NUEVO SERVICIO</button>";
			// BOT REFINANCIAR
			r += "<button type=\"button\" id=\"bot_new_service\" class=\"btn-normal m-1\" onClick=front_call({method:'refinanciar',action:'call',sending:false})>REFINANCIAR CUOTA</button>";
			// r += "<div class='row d-flex justify-content-start mb-2'>";
			//  BOT SUBIR ARCHIVO
			if(top.user_id == 502){
				r += "<button type='button' class='btn-normal m-1 ' id='button_file_upload' onClick=front_call({method:'cli_file_upload',sending:false})>SUBIR ARCHIVO COMO CLIENTE</button>";
			}
			r += "<button type='button' class=\'btn-normal\' id='button_file_upload' onClick=front_call({method:'lotes_file_upload',sending:false})>SUBIR ARCHIVO</button>";
			// BOT COMUNICADOS INTERNOS
			// r += "<button type='button' class=\'btn-normal\' id='send_msg' onClick=front_call({method:'send_msg',sending:false,action:'observacion'})>NUEVO MENSAGE</button>";
			// BOT IMPRIMIR
			r +="<button type='button' class='btn-normal m-1' id='print_button_res_cta' onclick=\"print_resumen_de_cta()\">IMPRIMIR EL ESTADO DE CUENTA</button>";
			r += "</div></div>";
			// BOT IMPRIMIR BOLETO
			r +="<button type='button' class='btn-normal' id='print_button_boleto' onclick=front_call({method:'print_boleto',sending:false})>IMPRIMIR BOLETO</button>";
			r += "</div>";

			return r;

		}
		//**** PERMISOS ADMINISTRACION HAY CUOTAS EN MORA
		if(TOP.permisos < 5 && this._data.lote.ctas_mora.events.length > 3 ){
			r +="<div class='row d-flex justify-content-between mb-2'>";
			// DISPONIBLE DE CREDITO
			r +="<div class=\' p-3 text-center\'>Credito Disponible: "+accounting.formatMoney(parseFloat(this._data.lote.mto_reintegro), "$ ", 0, ".", ",")+"</div>";
			// BOT CUOTAS
			r += "<button type=\"button\" id=\"bot_pago\" class=\"btn-danger m-1\" onClick=front_call({method:'set_pago_cuotas',sending:'true',action:'call',steps_back:true}) >CUOTAS PENDIENTES (MAS DE 3 CUOTAS EN MORA)</button>";
			// r += "<button type=\"button\" id=\"bot_pago\" class=\"btn-secondary m-1\" onClick=front_call({method:'set_pago_cuotas',sending:'true',action:'call',steps_back:true})>Cuotas Pendientes</button>";
			// BOT SERVICIO
			r += "<button type=\"button\" id=\"bot_new_service\" class=\"btn-normal m-1\" onClick=front_call({method:'new_service_elem',action:'call',sending:true})>NUEVO SERVICIO</button>";
			//  BOT SUBIR ARCHIVO
			if(top.user_id == 502){
				r += "<button type='button' class='btn-normal m-1 ' id='button_file_upload' onClick=front_call({method:'cli_file_upload',sending:false})>SUBIR ARCHIVO COMO CLIENTE</button>";
			}
			r += "<button type='button' class='btn-normal m-1 ' id='button_file_upload' onClick=front_call({method:'lotes_file_upload',sending:false})>SUBIR ARCHIVO</button>";
			// BOT COMUNICADOS INTERNOS
			r += "<button type='button' class='btn-normal m-1 ' id='button_send_msg' onClick=front_call({method:'send_msg',sending:false,action:'observacion'})>NUEVO MENSAGE</button>";
			// BOT IMPRIMIR
			r +="<button type='button' class='btn-normal' id='print_button_res_cta' onclick=\"print_resumen_de_cta()\">IMPRIMIR EL ESTADO DE CUENTA</button>";
			r += "</div>";
			// BOT IMPRIMIR BOLETO
			r +="<button type='button' class='btn-normal' id='print_button_boleto' onclick=front_call({method:'print_boleto',sending:false})>IMPRIMIR BOLETO</button>";
			r += "</div>";

			return r;
		}

		//**** PERMISOS USUARIOS VENTAS
		if(TOP.permisos == 10){
			// ROW BOTON DE PAGOS
			r += "<div class=\'row text-center mb-2\'>"
			r +="<div class=\'col\'>"
			// if(sinCtasEnMora(this._data)){
				r += "<a href=\'#\'><button type=\"button\" id=\"bot_pago\" class=\"btn-normal \" onClick=front_call({method:'set_pago_cuotas',sending:'true',action:'call',steps_back:true})>ESTADO DE CUOTAS</button></a><br/>";
				// r +="<div class=\' pl-3 pr-3 text-center small\'>Adelanta cuotas y obtené un descuento sobre tu plan, llevás ahorrado: "+accounting.formatMoney(parseFloat(TOP.ahorrado_tot), "$ ", 0, ".", ",")+"</div>";
			// }else{
				// r += "<a href=\'#\'><button type=\"button\" id=\"bot_pago\" class=\"btn-alerta\" >CONTACTESE CON ADMINISTRACION AL NUMERO 11 3359-8458</button></a><br/>";
			// }
			r +="</div></div>"
			// CLOSE ROW
			// ROW CONSULTAS
			r += "<hr/>";
			r +="<div class='row d-flex justify-content-between mb-3'>";
			r +="<div class=\'col d-flex flex-wrap justify-content-around\'>"
			r +="<a href=\'http://api.whatsapp.com/send?phone=5491145260488&amp;text=Hola%20quiero%20mas%20info%20sobre%20un%20prestamo%20que%20vi%20en%20la%20web%20de%20LotesParaTodos\' target='_blank'><button type='button' class='btn-prestamo' id='button_whatsapp' onclick=\"\">CONSULTANOS POR WHATSAPP YA!</button></a>"
			r +="<div class=\' pl-3 pr-3 text-center small\'>Tenés preaprobado un préstamo de hasta: "+accounting.formatMoney(parseFloat(this._data.lote.mto_reintegro), "$ ", 0, ".", ",")+"</div>";
			r +="</div>";
			r +="<div class=\'col d-flex flex-wrap justify-content-around\'>"
			// r += "<a href=\'#\'><button type='button' class='btn-normal ' id='button_file_upload' onClick=front_call({method:'cli_file_upload',sending:false})>ENVIAR COMPROBANTE DE PAGO</button></a>";
			// r +="<div class=\' pl-3 pr-3 text-center small\'>Subí tu comprobante de pago para actualizar tu cuenta.</div>";
			//  BOTON SUBIR ARCHIVO
			r += "<button type='button' class='btn-normal m-1 ' id='button_file_upload' onClick=front_call({method:'lotes_file_upload',sending:false})>SUBIR ARCHIVO</button>";
			r +="</div>"
			// r += "<button type='button' class='btn-secondary m-2 ' id='button_cli_file_upload' onClick=front_call({method:'send_msg',sending:false,action:'observacion'})>Comunicate con nosotros</button>";
			// r += "</div>";
			r +="<div class=\'col d-flex flex-wrap justify-content-around\'>"
			r +="<a href=\'#\'><button type='button' class='btn-normal' id='print_button_res_cta' onclick=\"print_resumen_de_cta()\">IMPRIMÍ EL ESTADO DE CUENTA</button></a>";
			r +="<div class=\' pl-3 pr-3 text-center small\'>Descargá el estado de cuenta y guardalo o imprimilo.</div>";
			r +="</div></div><hr/>"
			return r;

		}



		//**** PERMISOS USUARIOS WEB CLI
		if(TOP.permisos > 10){
			// ROW BOTON DE PAGOS
			r += "<div class=\'row text-center mb-2\'>";
			r +="<div class=\'col\'>";
			let estado_ctas = sinCtasEnMora(this._data)
			console.log('estado',estado_ctas);
			if(estado_ctas === true){
				r += "<a href=\'#\'><button type=\"button\" id=\"bot_pago\" class=\"btn-normal \" onClick=front_call({method:'set_pago_cuotas',sending:'true',action:'call',steps_back:true})>PAGAR CUOTAS ONLINE</button></a><br/>";
				r +="<div class=\' pl-3 pr-3 text-center small\'>Adelanta cuotas y obtené un descuento sobre tu plan, llevás ahorrado: "+accounting.formatMoney(parseFloat(TOP.ahorrado_tot), "$ ", 0, ".", ",")+"</div>";
			}else if(estado_ctas && estado_ctas.indexOf('servicios_en_mora') > -1){
				r += "<a href=\'#\'><button type=\"button\" id=\"bot_pago\" class=\"btn-alerta \" onClick=front_call({method:'set_pago_cuotas',sending:'true',action:'call',steps_back:true})>PAGAR CUOTAS ONLINE ("+estado_ctas+")</button></a><br/>";
				r +="<div class=\' pl-3 pr-3 text-center small\'>Adelanta cuotas y obtené un descuento sobre tu plan, llevás ahorrado: "+accounting.formatMoney(parseFloat(TOP.ahorrado_tot), "$ ", 0, ".", ",")+"</div>";
			}
			else{
				r += "<a href=\'#\'><button type=\"button\" id=\"bot_pago\" class=\"btn-alerta\" >CONTACTESE CON ADMINISTRACION AL NUMERO 11 3359-8458</button></a><br/>";
			}
			r +="</div></div>"
			// CLOSE ROW
			// ROW CONSULTAS
			r += "<hr/>";
			r +="<div class='row d-flex justify-content-between mb-3'>";
			r +="<div class=\'col d-flex flex-wrap justify-content-around\'>"
			r +="<a href=\'http://api.whatsapp.com/send?phone=5491145260488&amp;text=Hola%20quiero%20mas%20info%20sobre%20un%20prestamo%20que%20vi%20en%20la%20web%20de%20LotesParaTodos\' target='_blank'><button type='button' class='btn-prestamo' id='button_whatsapp' onclick=\"\">CONSULTANOS POR WHATSAPP YA!</button></a>"
			r +="<div class=\' pl-3 pr-3 text-center small\'>Tenés preaprobado un préstamo de hasta: "+accounting.formatMoney(parseFloat(this._data.lote.mto_reintegro), "$ ", 0, ".", ",")+"</div>";
			r +="</div>";
			r +="<div class=\'col d-flex flex-wrap justify-content-around\'>"
			r += "<a href=\'#\'><button type='button' class='btn-normal ' id='button_file_upload' onClick=front_call({method:'cli_file_upload',sending:false})>ENVIAR COMPROBANTE DE PAGO</button></a>";
			r +="<div class=\' pl-3 pr-3 text-center small\'>Subí tu comprobante de pago para actualizar tu cuenta.</div>";
			r +="</div>"
			// r += "<button type='button' class='btn-secondary m-2 ' id='button_cli_file_upload' onClick=front_call({method:'send_msg',sending:false,action:'observacion'})>Comunicate con nosotros</button>";
			// r += "</div>";
			r +="<div class=\'col d-flex flex-wrap justify-content-around\'>"
			r +="<a href=\'#\'><button type='button' class='btn-normal' id='print_button_res_cta' onclick=\"print_resumen_de_cta()\">IMPRIMÍ EL ESTADO DE CUENTA</button></a>";
			r +="<div class=\' pl-3 pr-3 text-center small\'>Descargá el estado de cuenta y guardalo o imprimilo.</div>";
			r +="</div></div><hr/>"
			return r;

		}


	},


		//  *** SERVICIOS CARD
	service_cards : function(){
		var r = '';
		for (var i = 0 ; i < this._data.srv.length ; i ++){
			r +="<div id=\'srv_card_"+i+"\' class=\'row \'>"
			r +="<div class = \"panel panel-primary\">";
			r += "<div class = \"panel-heading d-flex flex-wrap justify-content-around\">";
			r += "<a data-toggle = \"collapse\" href = \"#srv_row_"+i+"\"><button type=\"button\" class=\"btn-normal \" ><i class=\"material-icons \">more_vert</i></button></a>";
			r += "<span class=\'d-flex flex-wrap p-2\'>Servicio: "+this._data.srv[i].srvc_name+"</span>";
			r += "<span class=\'d-flex flex-wrap p-2\'>Tot. Pagado:"+accounting.formatMoney(parseFloat(this._data.srv[i].tot_pagado), "$", 0, ".", ",")+"</span>";
			r += "<span class=\'d-flex flex-wrap p-2\'>Cuotas a Pagar:"+this._data.srv[i].ctas_restantes.events.length+"</span>";
			r += "<span class=\'d-flex flex-wrap p-2\'>Monto $:"+accounting.formatMoney(parseFloat(this._data.srv[i].ctas_restantes.total), "$", 0, ".", ",")+"</span>";
			r += "<span class=\'d-flex flex-wrap p-2\'>Cuota Actual $:"+this.get_monto_cta_actual(this._data.srv[i].ctas_restantes.events)+"</span>";
			r += "</div>";
			r +="<div id = \"srv_row_"+i+"\" class=\"panel-collapse collapse\"><ul class = \"list-group\">";
			r += "<a href=\'#\' onClick=\"front_call({method:\'detalle_ctas\',title:\'Cuotas de servicios Pagas en fecha\',elem:'srv',e_index:"+i+",action:\'ctas_pagas\'});\"><li class=\"list-group-item d-flex justify-content-between align-items-center\">Cuotas Pagas<span class=\"badge badge-success badge-pill\">"+this._data.srv[i]['ctas_pagas'].events.length+"</span></li></a>";
			r += "<a href=\'#\' onClick=\"front_call({method:\'detalle_ctas\',title:\'Cuotas de servicios Adelantadas\',elem:'srv',e_index:"+i+",action:\'ctas_adelantadas\'});\"><li class=\"list-group-item d-flex justify-content-between align-items-center\">Cuotas Adelantadas<span class=\"badge badge-success badge-pill\">"+this._data.srv[i]['ctas_adelantadas'].events.length+"</span></li></a>";
			r += "<a href=\'#\' onClick=\"front_call({method:\'detalle_ctas\',title:\'Cuotas de servicio Restantes\',elem:'srv',e_index:"+i+",action:\'ctas_restantes\'});\"><li class=\"list-group-item d-flex justify-content-between align-items-center\">Cuotas Restantes<span class=\"badge badge-success badge-pill\">"+this._data.srv[i]['ctas_restantes'].events.length+"</span></li></a>"
			r += "<a href=\'#\' onClick=\"front_call({method:\'detalle_ctas\',title:\'Cuotas de servicios Pagas fuera de termino\',elem:'srv',e_index:"+i+",action:\'ctas_pft\'});\"><li class=\"list-group-item d-flex justify-content-between align-items-center\">Cuotas Pagas Fuera de Termino<span class=\"badge badge-success badge-pill\">"+this._data.srv[i]['ctas_pft'].events.length+"</span></li></a>";

			r += "<a href=\'#\' onClick=\"front_call({method:\'detalle_ctas\',title:\'Cuotas de servicios Restantes\',elem:'srv',e_index:"+i+",action:\'ctas_mora\'});\"><li class=\"list-group-item d-flex justify-content-between align-items-center\">Cuotas en mora<span class=\"badge badge-danger badge-pill\">"+this._data.srv[i]['ctas_mora'].events.length+"</span></li></a>"

			r += "</ul></div></div></div><hr/>";
			// *** FINAL SERVICIOS

		}
		return r;
	},

	kill_service_btn : function(srv){
		if(parseInt(srv.tot_pagado) === 0 && TOP.permisos <= 5 ){
			return "<button type=\"button\" class=\"btn-normal mr-2\" onclick=\"front_call({method:'kill_service_elem',sending:false,elm_id:'"+srv.srvc_id+"'})\"><i class=\"material-icons \">remove-circle-outline</i</button></li>";
		}else{
			return '';
		}
	},

	//  *****************************************

	// TEXTO DE COMENTARIOS Y OBSERVACIONES
	ventana_de_mensages : function(){
			let r ="<div class=\'col d-flex flex-wrap p-2 \' id=\'txt_obsrv\' >";
			r += data_box_small.create({id:'txt_obsrv',label:"Mensages",value:this._data.lote.observaciones,pcle_lbl:'observaciones',edit_btn:true,collapsed:true}).get_screen();
			r += "</div>";
			return r;
	},


	//  *****************************************

	// ************** CARD 1 OK *****************

	get_card1: function(){
		let r ='';
		//*** ROW STATE AND FEC INIT
		if(TOP.permisos <= 10){
			r+="<div class=\'row d-flex justify-content-around mt-4 mb-1 pt-3 \'>"+back_button.create()+this.get_curr_state()+this.get_fec_init()+"</div>";
		}
		// es cliente web
		if(TOP.permisos >= 10){
			r+="<div class=\'row d-flex justify-content-between mb-1 p-2\'><div class='col'><img src='/images/logo_LPT1.svg'></div><div class='col text-right'><a href='https://lotesparatodos.com.ar'>Hola "+this._data.lote.cli_data[0]['value']+"<br/>cerrar sesión</a></p></div></div>";
		}

		//*** LOTES CARD
		r += "<div class=\'jp-card\' id='card1'>";
		// *** HEADING
		r += "<div id=\"heading_card1\" class='card-header d-flex justify-content-between'>"
		// r += "<button type=\"button\" onClick=front_call({'method':'back'}) class=\"btn-normal \"><i class=\"material-icons \">home</i></button>";
		r +="<button type=\"button\" class=\"btn-normal  \"  data-toggle=\"collapse\" data-target=\"#collapse_card1\" aria-expanded=\"true\" aria-controls=\"card1_body\"><i class=\"material-icons \">more_vert</i></button>";
		r += this.get_header();
		if(TOP.permisos <= 10){
			r += "<div class='col d-flex flex-wrap card-title justify-content-center'><button type=\"button\" class=\"btn-normal\" onClick=front_call({method:'call_edit',action:'call',sending:true,data:{type:'Atom',id:"+this._data.lote['cli_id']+"}})><i class=\"material-icons \">open_in_new</i><span class=\'card-title align-top \'>DATOS CLIENTE</span></button></div>";
		}
		r += "</div>" // CIERRA EL HEADING;
		r +="<div id='collapse_card1' class='collapse show' aria-labelledby='heading_card1' style=''>"
		//*** card 1
		r += "<div class=\'card-body\' id='card1_body' >";
		// r += this.lote_card();
		// r += this.service_cards();
		r += "<div class=\'row justify-content-between mb-3\'>";
		r += "<div class=\'title-lote d-flex\'>";
		r += "<img class=\'jp-icon\' src=\'images/icons/home_blue_small.png\'></img> ESTADO DE CUENTA DEL LOTE "+this._data.lote['lote_nom'];
		r += "</div>"
		r += "<div class=\'title-lote d-flex\'>";
		r += "<a href=\'https://sgt.escobar.gov.ar/pagosDeudaOnline/servlet/com.pagosdeudaescobar.wpdeudaonline\' target=\'_blank\'><button type=\"button\" class=\"btn-normal\" >DEUDA ONLINE</button></a>"
		r += "</div>"
		r += "<div class=\'title-lote d-flex\' >PARTIDA: ";
		r += "<input type=\'text\'  readonly style=\'width:130px;border:0;\' value=\'"+this._data.lote['partida']+"\' id=\'nro_partida\' />";
		r += "<a href=\'#\' onClick='copy_to_clipboard()'><i class=\"material-icons \" title='Copiar al Portapapeles' >file_copy</i></a>"
		r += "</div></div>" // CIERRO EL ROW

		r += this.ctas_lote();
		// r += "<hr />";

		//****  SERVICIOS
		if(this._data.srv.length > 0){
			// SI HAY SERVICIOS CON CUOTAS RESTANTES O CTA UPC LO PONE EN PANTALLA
			if(this.verify_servicio_pendiente()){
				r += "<div class='title-servicios'><img class=\'jp-icon\' src=\'images/icons/servicios_small.png\'></img> ESTADO DE CUENTA DE"+(TOP.permisos >= 10 ?" TUS ":" ")+"SERVICIOS</div>";
				// TOSDOS LOS SERVICIOS QUE NO SON PRESTAMO
				r += this.ctas_srv()
				// TODOS LOS PRESTAMOS

				// for (let c = 0 ; c < t.length ; c ++){
					// if(this._data.srv[i].srvc_name.indexOf('Prestamo') == 0 ){
						// ****  PRESTAMOS
						r += this.ctas_prest()
					// }
				// }
			}
		}
		r += "<hr />";
		//***  ROW DE BUTTONS
		r +=this.get_buttons_bar();

		//** ROW DE VENTANAS DE MENSAGE Y ARCHIVOS UPLOADED
		r +="<div class='row d-flex justify-content-around mt-2'>"
		// r += "<div class=\'row d-flex justify-content-between\'><div class='col-3'>";
		if(TOP.permisos < 10){
			r += this.ventana_de_mensages();
		}

		if(TOP.permisos > 10){
			r += uploaded_files_boxes.create(['web_cli']).get_screen();

		}else{
			r += uploaded_files_boxes.create(['web_cli','lote_data_gen']).get_screen();
		}
		r +="</div>" //*** CIERRA ROW DE VENTANAS MSG Y  UPLOADED
		r += "</div></div></div></div>" //** CIERRA CARD BODY Y CARD1;
		return r;
	},
	// **********************************************

	verify_servicio_pendiente : function(){
		for (let i = 0 ; i < this._data.srv.length ; i ++){
			if(this._data.srv[i].cta_upc.total > 0 || this._data.srv[i].ctas_restantes.total > 0 ){
				return true;
			}
		}
		return false;
	},

	// **************  CARD DE RESCINDIDO  *****************
	get_card_rscn : function(){
		let r = "<div class=\'jp-card\'><div class='card-header  d-flex justify-content-center'>";
		r += "<h5 class=\'card-title\' >Rescisión de Contrato </h5></div>";
		r += "<div class=\'card-body\'>"+otbl.create([this._data.lote.rscn_data],'table_rscn')+"</div>";
		r +="</div>";
		console.log('resci',r);
		return r;
	},


	// **************  ULTIMOS MOVIMIENTOS  *****************

	get_card2 : function(){
		let r= "<div class='jp-card' id='card2' ><div class='card-header  d-flex justify-content-start' id=\"heading_card2\" >";
		r +="<button type=\"button\" class=\"btn-normal  \"  data-toggle=\"collapse\" data-target=\"#collapse_card2\" aria-expanded=\"true\" aria-controls=\"card2_body\"><i class=\"material-icons \">more_vert</i></button>";
		r += "<h5 class=\'card-title\'>Últimos Movimientos</h5></div>";
		r +="<div id='collapse_card2' class='collapse show' aria-labelledby='heading_card2' style=''>"
		r += "<div class=\'card-body\' id='card2_body'>"+otbl.create(this._data.last_mov,'table_last_movs')+"</div>";
		r +="</div></div>";
		return r;
	} ,
	// *********************************************

	// **************  SERVICIOS CANCELADOS  *****************

		get_card_canclds : function(){
			let t_data = new Array();
			for (var i = 0 ; i < this._data.srv.length ; i ++){
				if(this._data.srv[i].ctas_restantes.events.length === 0 && this._data.srv[i].ctas_mora.events.length === 0){
					t_data.push({
						'Plan / Servicio':(this._data.srv[i].srvc_name).substring(0,25),
						'Ctas. Pagas':this._data.srv[i].ctas_pagas.events.length,
						'Monto':this.get_tot_pagado(this._data.srv[i]),
						'Acciones':"<button type=\"button\" class=\"btn-normal\" onClick=front_call({method:'detalle_servicios_cancelados',sending:true,data:{id:'"+this._data.srv[i].srvc_id+"'},container_title:'"+encodeURI(this._data.srv[i].srvc_name)+"'})><i class='material-icons'>open_in_new</i></button>"
						});
				}

			}
			if(t_data.length >0){
				let r= "<div class='jp-card' id='card3'><div class='card-header  d-flex justify-content-start' id=\"heading_card3\"  >";
				r +="<button type=\"button\" class=\"btn-normal  \"  data-toggle=\"collapse\" data-target=\"#collapse_card3\" aria-expanded=\"true\" aria-controls=\"card3_body\"><i class=\"material-icons \">more_vert</i></button>";
				r += "<h5 class=\'card-title\'>Planes Servicios Cancelados</h5></div>";
				r +="<div id='collapse_card3' class='collapse show' aria-labelledby='heading_card3' style=''>"
				r += "<div class=\'card-body\' id='card3_body' >"+otbl.create(t_data,'table_cancs')+"</div>";
				r +="</div></div>";
				return r;
			}
		} ,
		// *********************************************

	// ************** Printed version *****************
	get_print_vers: function(){
		//*** ROW STATE AND FEC INIT
		let r ="<div class='container p-3'><div class=\'row d-flex justify-content-center m-2\'></div>";
		//*** HEADER
		r += "<div class=\'card bg-light mb-2 \'><div class='card-header d-flex justify-content-between'>";
		r += "<div class='col-8 d-flex justify-content-start'><h5> Nombre de Cliente: "+this._data.lote.cli_atom_name+"<br/>"
		r += "Numero de Lote: "+this._data.lote.lote_nom+"<br/>"
		r += "Plan de Financiación: "+this._data.lote.financ+"<br/>";
		r += "Fecha de Inicio del plan:  "+this._data.lote.fec_init + "</h5></div>"
		r +="<div class='col-3 d-flex justify-content-end'><img src=\"/images/logo_LPT1.svg\"></div>"
		r += "</div>"
		//*** LOTE Y SERVICIOS
		r += "<div class=\'card-body\'>";
		// r += this.lote_card();
		// r += this.service_cards();
		r += this.ctas_tbl_noacc();
		r += "<hr/><div class=\'row d-flex justify-content-start\'><h5 class=\'p-2\'>Credito Disponible: "+accounting.formatMoney(parseInt(this._data.lote.mto_reintegro), "$ ", 0, ".", ",")+"</h5></div>";
		r += "</div></div></div></div>";
		return r;
	},
	// **********************************************

};



// objeto TABLA
const otbl={
	create:function(v,id){
		// console.log('otbl',v,id)
		if(v && Array.isArray(v) && v.length > 0){
			let r = "<table id=\'"+id+"\' class='table table-hover tabe-sm'>";
			r += "<thead><tr>";
			r += (Object.keys(v[0]).map(i=>{return "<th class=\'text-center\'>"+i+"</th>"})).join('');
			r +="</thead></tr>";
			r +="<tbody>";
			r += (v && Array.isArray(v)?v.map(row=>{return "<tr>"+Object.keys(row).map(c=>{return td_format_cont(c,row[c])}).join('')+"</tr>"}).join(''):'');
			r += "</tbody></table>";
			return r
		}
	}
}

// objeto TABLA usa jp.css
const otbl_2={
	create:function(v,id){
		if(v && Array.isArray(v)){
			let r = "<table class=\"jp-table\" id=\'"+id+"\'>";
			r += "<thead><tr>";
			r += (Object.keys(v[0]).map(i=>{return "<th class=\'text-center\'>"+i+"</th>"})).join('');
			r +="</thead></tr>";
			r +="<tbody>";
			r += (v && Array.isArray(v)?v.map(row=>{return "<tr>"+Object.keys(row).map(c=>{return "<td>"+td_format_cont(c,row[c])+"</td>"}).join('')+"</tr>"}).join(''):'<td></td>');
			r += "</tbody></table>";
			return r
		}
	}
}


//********* TABLA PARA REPORTS (NO LLEVA TD_FORMAT_CONT POR QUE REQUIERE LOS DATOS SIN FORMATO PARA EL PLUGIN DE FILTROS)
// td_format_cont(c,row[c])
const repotbl={
	create:function(v,id){
		if(v && Array.isArray(v)){
			let r = "<table class=\"table table-hover table-sm	nowrap\" width=\'100%\' id=\'"+id+"\'>";
			r += "<thead><tr>";
			r += (Object.keys(v[(get_obj_with_all_keys(v))]).map(i=>{return "<th class=\'text-center\'>"+i+"</th>"})).join('');
			r +="</thead></tr>";
			r +="<tbody>";
			r += (v && Array.isArray(v)?v.map(row=>{return "<tr>"+Object.keys(v[(get_obj_with_all_keys(v))]).map(c=>{return "<td class='text-center'>"+(row[c] != undefined?row[c]:'-')+"</td>"}).join('')+"</tr>"}).join(''):'<td></td>');
			r += "</tbody><tfoot>"
			ft = false;
			r += (Object.keys(v[(get_obj_with_all_keys(v))]).map(ftrc=>{return "<td class=\'text-center\'>"+(ft?ftrc:'')+"</td>"})).join('');
			r +="</tfoot></table>";
			return r
		}
	}
}


// objeto contenedor y tabla
const ocont_and_table = {
	create:function(t,v,id){
		let r = "<div class='card bg-light '><div class='card-header  d-flex justify-content-center'><p>"+t+"</p></div>";
		r += "<div class=\'card-body\'>"+otbl.create(v,id)+"</div>";
		r +="</div>";
	}
}

// ****** ****************** ***********  ***********
// ****** PANTALLA DE PAGO DE CUOTA
// ****** ****************** ***********  ***********
// c = ['tipo_pago'=>'Normal','fec_vto'=>$ct[0]['cuota']->date,'pcles'=>$ct[0]['pcles'],'tot_cta'=>intval($monto[key($monto)]['value'])];
var pgc = {
	_tipocuota:{'actual':1,'adelanto':2},
	_cant_adelanto:{'cant':1},
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get:function(val){
		return this._data[val];
	},
	get_screen:function(){return this._screen},
	get_gpcle : function (arr,p,lbl){
		if(arr && arr.hasOwnProperty(p) && arr[p].length > 0){
			var r = arr[p].filter(function(i){return i.label === lbl});
			if(r.length >0){
				return r[0]['value'];
			}
		}
	},
	get_pcle : function (p,lbl){
		if(this._data.hasOwnProperty(p) && this._data[p].length > 0){
			var r = this._data[p].filter(function(i){return i.label === lbl});
			if(r.length >0){
				return r[0]['value'];
			}
		}
	},
	fill_select:function(name){
		var x='';
		for (var i = 0; i < this._data.pago_cta.selects[name].length; i++) {
			var n = this._data.pago_cta.selects[name][i];
			x += "<option value="+n.id+">"+n.lbl+"</option>";
		}
		return x;
	},
	set: function(v){
		// console.log('en pgc',TOP.data);
		this._data = TOP.data;
		this._data.pago_cta = TOP.pago;
		this._data.container = 'main_container';
		let permisosDePago = false;
		if(TOP.permisos <= 2){
			permisosDePago = true;
		}
		if(TOP.permisos >= 2 && TOP.permisos <= 5 &&
		TOP.data.lote.ctas_mora.events.length <= 3 &&
		TOP.data.lote.rscn_data == -1 &&
		TOP.data.lote.estado_contrato.value != 'RESCINDIDO' &&
		TOP.data.lote.estado_contrato.value != 'EN LEGALES' &&
		TOP.data.lote.estado_contrato.value != 'BLOQUEADO'
		){
			permisosDePago = true;
		}
		if(TOP.permisos == 100 &&
		// TOP.data.lote.ctas_mora.events.length <= 3 &&
		TOP.data.lote.rscn_data == -1 &&
		TOP.data.lote.estado_contrato.value != 'RESCINDIDO' &&
		TOP.data.lote.estado_contrato.value != 'EN LEGALES' &&
		TOP.data.lote.estado_contrato.value != 'BLOQUEADO'
		){
			permisosDePago = true;
		}
    if(	TOP.data.lote.estado_contrato.value == 'NORMAL'){
      	permisosDePago = true;
    }

    this._screen = "<div class=\"jumbotron mt-5\" style=\"padding:25px;\">\
		<div class=\"row d-flex justify-content-between\">\
		<div class=\"col-sm-2 col-md-1 \"><button type=\"button\" onClick=front_call({'method':'hist_home'}) class=\"btn-normal\"><i class=\"material-icons \">arrow_back_ios</i> </button></div>\
		<div class=\"col-xs-8 col-lg-9\">";
		this._screen += "<h3 >"+this._data.lote.lote_nom+ " "+ this.get_gpcle(this._data.lote,'cli_data','nombre')
		this._screen += " "+(this.get_gpcle(this._data.lote,'cli_data','apellido')== undefined?'':this.get_gpcle(this._data.lote,'cli_data','apellido'))
		this._screen +="<h3>";
		this._screen +="</div></div><div class=\"row d-flex justify-content-between\">"
		this._screen += "<div class=\"col\"><legend class=\'align-baseline\'>"
		if(TOP.data.lote.estado_contrato.value === 'RESCINDIDO' || TOP.data.lote.estado_contrato.value === 'EN LEGALES' ||
			TOP.data.lote.estado_contrato.value === 'BLOQUEADO'
		){
			this._screen +="* PAGO INHABILITADO POR ESTADO "+TOP.data.lote.estado_contrato.value + " *<br/> ";
		}

		this._screen +="Cuotas Lote </legend></div><div class=\"col text-right\">"
		if(permisosDePago){
			this._screen += "<button type=\"button\" onClick=agregar_adls('lote') class=\"btn btn-primary \">Adelantar Cuotas Lote</button>";
		}
		this._screen +="</div></div><div class=\"col-xs-12\" id=\"ctas_table\"></div><hr>";
		if(this._data.srv.length > 0){
			this._screen +=	"<div class=\"row d-flex justify-content-between\"><div class=\"col\">"
			this._screen +="<legend class=\'align-baseline\' id=\'tit_servicios\'>Cuotas Servicios</legend></div><div class=\"col text-right\">"
			if(permisosDePago){
				this._screen +="<button type=\"button\" onClick=agregar_adls('servicios') class=\"btn btn-primary \">Adelantar Cuotas Serv.</bubtn_viewston>";
			}
			this._screen +="</div></div><div class=\"col-xs-12\" id=\"srvs_table\"></div><hr>";
		}
		let n = '',bp='';
		n +="<div class=\"row d-flex justify-content-start\">";

		// **** BLOCK SELECTORES RECIBO / FECHA / CUENTA *****
		n +="<div class=\"col-xs-12  col-sm-12 col-md-6 col-lg-4\">";
		// n +="<div class=\"form-group\" id=\"fg_rec_num\"><label for=\"rec_num\">Nro. de recibo</label><input type=\"number\" readonly=\"true\" class=\"form-control\" id=\"rec_num\" value="+this._data.pago_cta.rec_num+"></div>";
		if(permisosDePago){
			n += date_obj.create({label:'fecha_pago_imputacion',title:'Fecha de imputacion del pago','extras':'no_col'}).get_screen();
		}
		n +="<div class=\"row m-1\"></div>";
		// n += select_obj.create({label:'cuentas',title:'Cuenta','extras':'no_col'}).get_screen();
		// n +="<div class=\"d-flex align-content-start flex-wrap p-1\"><div class=\"form-group\" id=\"fg_cuenta_pgc\"><label for=\"cuenta_pgc\">Cuenta</label><select class=\"form-control\" id=\"cuenta_pgc\"><option value = -1 >Selecciona la cuenta</option>";
		// n += this.fill_select('cuentas');
		n += "</div>";

		// **** BLOCK NUMEROS DE PAGO *********
		n +=  "<div class=\"col-xs-12  col-sm-12 col-md-6 col-lg-4 p-2\"><big>";

		n+="<div class=\'row d-flex justify-content-end\'>";
		n+="<div class=\"form-group row form-inline p-1\">";
		n+="<label for=\"monto_ctas\" class=\"col-form-label text-right\">Total Cuotas $:</label>";
		n+="<div class=\"col \">";
		n+="<input type=\"text\" readonly=\"\" class=\"form-control-plaintext text-right\" id=\"monto_ctas\" value=0>";
		n+="</div>";
		n+="<div class=\"col-1 text-right p-0\"></div>";
		n+="</div></div>";
		n+="<div class=\'row d-flex justify-content-end\'>";
		n+="<div class=\"form-group row form-inline p-1\"><label for=\"monto_interes\" class=\"col-form-label text-right\">Total Intereses $:</label>"
		n+="<div class=\"col\"><input type=\"text\" readonly=\"\" class=\"form-control-plaintext text-right\" id=\"monto_interes\" ></div>"
		n+="<div class=\"col-1 text-right p-0\"></div>";
		n+="</div></div>";


		n+=" <div class=\'row d-flex justify-content-end\'>"
		n+="<div class=\"form-group row form-inline p-1\">"
		n+="<label for=\"monto_servicios \" class=\"col-form-label text-right\">Total Servicios $:</label>"
		n+="<div class=\"col\">"
		n+="<input type=\"text\" readonly=\"\" class=\"form-control-plaintext text-right\" id=\"monto_servicios\"value=0>"
		n+="</div>"
		n+="<div class=\"col-1 text-right p-0\"></div>";
		n+="</div>"
		n+="</div>"

		n+="<div class=\'row d-flex justify-content-end\'>"
		n+="<div class=\"form-group row form-inline p-1\">"
		n+="<label for=\"monto_a_pagar \" class=\"col-form-label text-right\">Total Cargos $:</label>"
		n+="<div class=\"col \">"
		n+="<input type=\"text\" readonly=\"\" class=\"form-control-plaintext text-right\" id=\"monto_a_pagar\">"
		n+="</div>";
		n+="<div class=\"col-1 text-right p-0\"></div>";
		n+="</div>"
		n+="</div>"

		n+="<div class=\'row d-flex justify-content-end\'>"
		n+="<div class=\"form-group row form-inline p-1 text_right\">"
		n+="<label for=\"saldo \" class=\"col-form-label text-right\">Saldo en cuenta $:</label>"
		n+="<div class=\"col\">"
		n+="<input type=\"text\" readonly=\"\" class=\"form-control-plaintext text-right\" id=\"saldo\">"
		n+="</div>";
		n+="<div class=\"col-1 p-0\"></div>";
		n+="</div>";
		n+="</div>";

		n+=" <div class=\'row d-flex justify-content-end\'>"
		n+="<div class=\"form-group row form-inline p-1\">"
		n+="<label for=\"estado_actual \" class=\"col-form-label text-right\">Estado Actual $:</label>"
		n+="<div class=\"col\">"
		n+="<input type=\"text\" readonly=\"\" class=\"form-control-plaintext text-right\" id=\"estado_actual\"value=0>"
		n+="</div>"
		n+="<div class=\"col-1 text-right p-0\"></div>";
		n+="</div>"
		n+="</div>"

		n+="<div class=\"col-1 text-right p-0\"></div>";
		n+="</div>";

		// ****** BLOCK BOTONES INGRESAR / IMPUTAR PAGOS   *********

		n+="<div class=\"col-xs-12  col-sm-12 col-md-6 col-lg-4 \">";
		// n+="<div class=\'row d-flex justify-content-center m-4\'>";
		// n+="<div class=\"form-group row form-inline p-1\">";
		// n+="<label for=\"monto_recibido\" class=\"col-form-label-lg text-right\">Total ingresado $:</label>";
		// n+="<div class=\"col \">";
		// n+="<input type=\"number\" class=\"form-control-lg\" id=\"monto_recibido\" onChange=check_pgc_monto_regibido()>";
		// n+="</div>";
		// n+="</div>";
		// n+="</div>";
		// if(
		// 	TOP.permisos < 5 &&
		// 	TOP.data.lote.ctas_mora.events.length < 3 &&
		// 	TOP.data.lote.rscn_data == -1 &&
		// 	TOP.data.lote.estado_contrato.value != 'RESCINDIDO' &&
		// 	TOP.data.lote.estado_contrato.value != 'EN LEGALES' &&
		// 	TOP.data.lote.estado_contrato.value != 'BLOQUEADO'
		// ){
		// 	console.log('cuotas en mora');
		// }
    // console.log('permisos',TOP.permisos +" PDP:"+ permisosDePago);
		if(TOP.permisos <= 2 && permisosDePago){
				bp += "<div class=\'row d-flex justify-content-center m-3\'>";
				bp += "<div class=\"form-group  p-1 \">";
				bp += "<div class=\"btn btn-primary\" id=\"bot_ingresar_pago\" onClick=\"front_call({method:'ingresar_pago',sending:false})\" href=\"#\" role=\"button\">Ingresar Pago &nbsp;</div>";
				bp += "</div>";
				bp += "</div>"; // cierro el row
				bp += "<div class=\'row d-flex justify-content-center m-3\'>";
				bp += "<div class=\"form-group p-1 \">";
				bp += "<div class=\"btn btn-primary\" id=\"bot_imputar_pago\" onClick=\"front_call({method:'procesar_pago_cuota',sending:false})\" href=\"#\" role=\"button\">Imputar Cuotas</div>";
				bp += "</div>";
				bp += "</div>"; // cierro el row
		}


			// BOTON DE PAGOS ONLINE
			// bp += "<div class=\'row d-flex justify-content-center m-3\'>";
			// bp += "<div class=\"form-group p-1 \">";
			// bp += "<div class=\"btn btn-primary\" id=\"bot_pago_online\" onClick=\"front_call({method:'call_pago_api',sending:true})\" href=\"#\" role=\"button\">Pagar Cuotas Online</div>";
			// bp += "</div>";
			// bp += "</div>"; // cierro el row

			// CAMBIO DE FINANC PLAN APARECE SOLO
			// bp += "<div class=\"form-group p-1 \">";
			// bp += "<div class=\"btn btn-primary\" id=\"bot_update_plan\" onClick=\"front_call({method:'set_cambio_financ_plan',sending:false,action:'call'})\" href=\"#\" role=\"button\">Revisar Financiación</div>";
			// bp += "</div>";

		//**** SI ES UN USUARIO WEB
		if(TOP.permisos == 100 && permisosDePago){
			// console.log('Usuario web detect');
			bp += "<div class=\"form-group p-1 \">";
			bp += "<div class=\"btn btn-primary\" id=\"bot_pago_online\" onClick=\"front_call({method:'call_pago_api',sending:true})\" href=\"#\" role=\"button\">Pagar Cuotas Online</div>";
			bp += "</div>";

		}
		TOP.botones_de_pago = bp;
		n += "<div class=\'row d-flex justify-content-center m-3\' id=\'pagos_container\'></div></div>";
		this._screen += n;
	}
};

// RECIBO DE IMPUTACIONES Y PAGOS
var rec_pgc = {
	_print:{},
	create: function(){
		var o = Object.create(this);
		o.set();
		return o;
	},
	get_print:function(){return this._print},
	get_Tpcle : function (arr,lbl){
		if(arr.length > 0){
			var r = arr.filter(function(i){return i.label === lbl});
			if(r.length > 0){
				return r[0]['value'];
			}
		}
	},
	set: function (){
		this._print ="<font size=\"+2\"><div class=\"container-fluid p-4\"><div class=\"row\">";
		this._print +="<div class=\"col\"><img src=\"/images/logo_recibo.jpg\"></div>";
		this._print +="<div class=\"col\"><p></p><legend><p>RECIBO NRO.: "+TOP.curr_rec.recibo_nro+"</p>";
		this._print +="<p>FECHA: "+moment(TOP.curr_rec.fecha_pago).format('D/M/YYYY')+"</p></legend></div></div>";
		this._print +="<div class=\"row\"><div class=\"col\"><p>Lote: "+TOP.curr_rec.nom_lote+"</p>"
		this._print +="<p>Titular: "+TOP.curr_rec.nom_cli+"</p></div>"
		this._print +="<div class=\"col\"><p>Domicilio: "+this.get_Tpcle(TOP.data.lote.cli_data,'domicilio')+", "+this.get_Tpcle(TOP.data.lote.cli_data,'localidad')+"</p>"
		this._print +="</div></div>";
		this._print +="<div class=\"row\">"+(TOP.curr_rec.detalle?tbl.create(TOP.curr_rec.detalle,'tbl_det_rec'):'')+"</div><hr/>";
		this._print +="<div class=\"row\">"
		this._print +="<p>Recibimos la suma de Pesos: "+numeroALetras(TOP.curr_rec.monto, {plural: 'PESOS ',singular: 'PESO',centPlural: 'CENTAVOS',centSingular: 'CENTAVO'})+"</p>"
		// this._print +="</div><div class=\"row\"><p>En concepto de: "+TOP.curr_rec.concepto+"</p></div>";
		this._print += "</div><div class=\"row\"><p>Pagado en: "+TOP.curr_rec.caja_name+"</p></div>";
		this._print += "<div class=\"row\"><p>Saldo en Cuenta: "+accounting.formatMoney(parseInt(TOP.curr_rec.saldo), "$", 0, ".", ",")+"</p></div>";
		this._print +="</br></br><div class=\"row\"><div class=\"col\">";
		this._print +="<p>firma: ______________________</p><p>Aclaración: __________________</p></div>";
		this._print +="<div class=\"col\"><h1 class='bold'>Son: "+accounting.formatMoney(parseInt(TOP.curr_rec.monto), "$", 0, ".", ",")+"</h1>";
		this._print +="</div></div>";
		this._print +="<hr/><div class=\"row\"><div class=\"col\"><p>";
		this._print +="* La fecha de vencimiento de cada cuota es el dia 25 de cada mes, luego generara intereses por mora."
		this._print +="</p></div></div>";
		this._print +="<div class=\"row\"><div class=\"col\"><p>";
		this._print +="* Su codigo de Pago Facil para abonar en Pagos Pyme es de LPT + "+ TOP.curr_rec.nom_lote;
		this._print +="</p></div></div>";
		this._print +="<div class=\"row\"><div class=\"col\"><p>";
		this._print +="* No se reciben depositos bancarios, si transferencias y por la misma, debe enviar el comprobante a la casilla de mail administracion@lotesparatodos.com.ar o bien al whatsapp 11 3359-8458";
		this._print +="</p></div></div>";
		this._print +="</div></font>";
	}
};
const dialog_imputacion_ctas = {
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get_screen:function(){return this._screen},
	set: function(){
		let r = "";
		r += "<div class=\'row d-flex justify-content-around pt-4\'><div class=\'col d-flex justify-content-end\'><h4>Total Cuotas $:</h4></div><div class=\'col d-flex justify-content-end pr-5\'><h4>"+ accounting.formatMoney(parseInt(TOP.tot_monto_ctas), "", 0, ".", ",") + "</h4></div></div>";
		r += "<div class=\'row d-flex justify-content-around pt-4\'><div class=\'col d-flex justify-content-end\'><h4>Total Servicios $: </h4></div><div class=\'col d-flex justify-content-end pr-5\'><h4> "+ accounting.formatMoney(parseInt(TOP.tot_monto_srvc), "", 0, ".", ",")+"</h4></div></div>";
		if(TOP.tot_monto_intrs > 0){
			r += "<div class=\'row d-flex justify-content-around pt-4\'><div class=\'col d-flex justify-content-end\'><h4>Total Intereses $: </h4></div><div class=\'col d-flex justify-content-end pr-5\'><h4>"+ accounting.formatMoney(parseInt(TOP.tot_monto_intrs), "", 0, ".", ",")+"</h4></div></div>";
		}
		r += "<p><hr /></p><div class=\'row d-flex justify-content-around pt-4\'><div class=\'col d-flex justify-content-end\'><h4 \'>Total imputacion $ : </h4></div><div class=\'col d-flex justify-content-end pr-5\'><h4>"+ accounting.formatMoney(parseInt(TOP.tot_a_pagar), "", 0, ".", ",")+"</h4></div></div>";
		r += "</div></div>";
		this._screen = r;
	}
}

// ****** ****************** ***********  ***********
// ** CONFIRMATION WINDOW
// ****** ****************** ***********  ***********
var conf={
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get:function(val){
		return this._data[val];
	},
	get_screen:function(){return this._screen},
	set: function(v){
		this.title = v.title;
		this._data = v;
		this._screen = "\
		<div class=\"row\">\
		<div class=\"col-lg-12\">\
		<legend>"+v.msg+"</legend>\
		</div>\
		</div>";
	}

};

// ****** ****************** ***********  ***********
// ** ALERT  WINDOW
// ****** ****************** ***********  ***********
const alert={
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get:function(val){
		return this._data[val];
	},
	get_screen:function(){return this._screen},
	set: function(v){
		this._data = v;
		this._screen = "<div class=\"alert alert-"+v.type+"\">";
		if(v.hasOwnProperty('tit')){this._screen += "<h4 id=\'alert_tit\'>"+v.tit+"</h4>";}
		this._screen += "<p id='alert_msg'>"+v.msg+"</p></div>";

	}
};

// ****** ****************** ***********  ***********
// ** INPUT WINDOW
// ** llamado por
// ** Obtener resumen de cuenta y Editar Contrato de Lote
// ****** ****************** ***********  ***********
var get_element_input={
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get:function(val){
		return this._data[val];
	},
	get_screen:function(){return this._screen},
	set: function(v){
		this._data = v;
		this._screen = "\
		<div class=\"form-group\">\
		<label class=\"col-form-label\" for=\"lote\">Nombre de Cliente o Lote</label>\
		<input type=\"text\" class=\"form-control\" placeholder=\"Ingresa nombre del cliente o numero de lote \" id=\"lote\">\
		</div>"
	}
};
// *********************

//  PANTALLA PLANILLA DE CAJA
var planilla_caja = {
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	set: function(v){
		this._data = v;
		this.tot_caja = (v.saldo + v.tt_ingr) - v.tt_egre
		var x = ''
		this._screen =
		"<div class=\"jumbotron mt-5 jumbotron-fluid\" style=\"padding-top:25px\">\
		<div class='container-fluid' role='container'>\
		<div class=\"row d-flex justify-content-start\">\
		<button type=\"button\" onClick=front_call({'method':'back'}) class=\"btn btn-primary\"><i class=\"material-icons \">arrow_back_ios</i></button>\
		<div class=\"col-6 \"><h5>Planilla de caja: "+v.caja_nom+"</h5></div>\
		<div class=\"col\"><h5>Desde: "+v.fd+"</h5></div>\
		<div class=\"col\"><h5>Hasta: "+v.fh+"</h5></div>\
		</div>\
		<hr>"
		if(v.tt_ingr > 0){
			this._screen +="<div class=\"row\">\
			<div class=\"col\"><h4>Ingresos</h4></div>\
			</hr>\
			</div>\
			<div class=\"row\">\
			<div class=\"col\" id=\"table_ingresos\"></div>\
			</div>"
		}
		if(v.tt_egre > 0){
			this._screen += "<div class=\"row\">\
			</hr>\
			<div class=\"col\"><h4>Egresos</h4></div>\
			</hr>\
			</div>\
			<div class=\"row\">\
			</hr>\
			<div class=\"col\" id=\"table_egresos\"></div>\
			</hr>\
			</div>";
		}
		this._screen += "</br>\
		<div class=\"row d-flex justify-content-end\">\
		<div class=\"col-6\"></div>\
		<div class=\"col-3 text-right\"><h5>Saldo Previo:</h5></div>\
		<div class=\"col-3 text-right\"><h5>"+v.saldo.toLocaleString()+"</h5></div>\
		</hr>\
		<div class=\"col-6\"></div>\
		<div class=\"col-3 text-right\"><h5>Total Ingresos:</h5></div>\
		<div class=\"col-3 text-right\"><h5>"+v.tt_ingr.toLocaleString()+"</h5></div>\
		</div>\
		<div class=\"row d-flex justify-content-end\">\
		</hr>\
		<div class=\"col-6\"></div>\
		<div class=\"col-3 text-right\"><h5>Total Egresos:</h5></div>\
		<div class=\"col-3 text-right\"><h5>"+v.tt_egre.toLocaleString()+"</h5></div>\
		</div>\
		<div class=\"row d-flex justify-content-end\">\
		</hr>\
		<div class=\"col-6\"></div>\
		<div class=\"col-3 text-right\"><h5>Total:</h5></div>\
		<div class=\"col-3 text-right\"><h5>"+this.tot_caja.toLocaleString()+"</h5></div>\
		</div>\
		</div>\
		</div>";
	}
}

// ** DEPRECATED PANTALLA DE REGISTRO DE OPERACIONES
var reg_op_old = {
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	fill_select:function(name){
		// // console.log('setting select:',name)
		var x='';
		for (var i = 0; i < this._data.selects[name].length; i++) {
			var n = this._data.selects[name][i];
			x += "<option value="+n.id+">"+n.lbl+"</option>";
		}
		return x;

	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	set: function(v){

		this._data = v;
		this._screen =
		"<div class=\"row\"><div class=\'col\'><h5></h5></div></div>\
		<div class=\"jumbotron mt-5 \">\
		<div class=\"row mt-5 d-flex justify-content-between\">\
		<div class=\"col-sm-1\"><button type=\"button\" onClick=front_call({'method':'registro_operacion','sending':false,'action':'back'}) class=\"btn btn-primary\"><i class='align-bottom icon ion-md-arrow-back'></i></button>\
		</div>\
		<div class=\"col\">\
		<h4>Registro de Operaciones<h4>\
		</div>\
		<div class=\"col\">\
		<h5>Fecha: "+v.fecha+" </h5>\
		</div>\
		</div>\
		<hr>\
		<div class=\"row d-flex justify-content-between\">\
		<div class=\"col-sm-12\">\
		<div class=\"row d-flex justify-content-between\">\
		<div class=\"col-sm-6\">\
		<div class=\"form-group\" id=\"fg_tipo_asiento\">\
		<label for=\"tipo_asiento\">Tipo de Asiento</label>\
		<select class=\"form-control\" id=\"tipo_asiento\" onBlur=chk_tipo_asiento()>\
		<option value=\"\">Selecciona el tipo de asiento</option>\
		<option value=\"INGRESOS\">Ingreso</option>\
		<option value=\"EGRESOS\">Egreso</option>\
		</select>\
		</div>\
		</div>\
		<div class=\"col-sm-6\" >\
		<div class=\"form-group\" id=\"fg_cuenta\">\
		<label for=\"cuenta\">Cuenta</label>\
		<select class=\"form-control\" id=\"cuenta\"\"><option value=''>Selecciona la cuenta</option>\
		"+this.fill_select('cuentas')+"</select>\
		</div>\
		</div>\
		</div>\
		<hr>\
		<div class=\"row d-flex justify-content-between\">\
		<div class=\"col-sm-6\">\
		<div class=\"form-group\" id=\"fg_imputacion\">\
		<label for=\"imputacion\">Concepto</label>\
		<select class=\"form-control\" id=\"imputacion\"><option value=''>Selecciona la imputación</option>\
		"+this.fill_select('impt_prov')+"</select>\
		</div>\
		</div>\
		<div class=\"col-sm-6\">\
		<div class=\"form-group\" id=\"fg_contraparte\">\
		<label for=\"contraparte\">Proveedor</label>\
		<select class=\"form-control\" id=\"contraparte\">\
		<option value=''>Seleccionar -</option>\
		"+0+"</select>\
		</div>\
		</div>\
		</div>\
		<hr>\
		<div class=\"row d-flex justify-content-between\" id=\"centro_costos_container\">\
		<div class=\"col-sm-5\">\
		<div class=\"form-group\" id=\"fg_cent_ctos_"+TOP.count_centro_costos_list+"\">\
		<label for=\"cent_ctos\">Centro de Costos</label>\
		<select class=\"form-control\" id=\"cent_ctos_"+TOP.count_centro_costos_list+"\" onChange=\"select_cctos_id(this.id)\" ><option value=''>Selecciona el Centro de Costos</option>\
		"+this.fill_select('barrio')+"</select>\
		</div>\
		</div>\
		<div class=\"form-group\" id=\"fg_percent_cctos_"+TOP.count_centro_costos_list+"\">\
		<label for=\"percent_barrio_"+TOP.count_centro_costos_list+"\">Distribucion Porcentaje </label>\
		<div class=\"input-group \">\
		<input type=\"number\" max=100 min=0 class=\"form-control\" id=\"percent_cctos_"+TOP.count_centro_costos_list+"\" aria-describedby=\"basic-addon\"\
		<div class=\"input-group-append\">\
		<span class=\"input-group-text\" id=\"basic-addon\">%</span>\
		</div>\
		</div>\
		<div class=\"col-sm-3\">\
		</br>\
		<div class=\"btn btn-normal align-bottom\" onClick=add_cctos() href=\"#\" role=\"button\"><i class=\"material-icons \">control_point</i> </div>\
		<div class=\"btn btn-normal align-bottom\" onClick=remove_cctos() href=\"#\" role=\"button\"><i class=\"material-icons \">remove_circle_outline</i></div>\
		</div>\
		</div>\
		<hr>\
		<div class=\"row d-flex justify-content-between\">\
		<div class=\"col-sm-4\">\
		<div class=\"form-group\">\
		<label for=\"numero_comprobante \" class=\"col-form-label\">Nro. Comprobante</label>\
		<input type=\"text\" class=\"form-control\" id=\"numero_comprobante\">\
		</div>\
		</div>\
		<div class=\"col-sm-8\">\
		<div class=\"form-group\">\
		<label for=\"observaciones\" class=\"col-form-label\">Observaciones</label>\
		<input type=\"text\" class=\"form-control\" id=\"observaciones\">\
		</div>\
		</div>\
		</div>\
		<hr>\
		<div class=\"row d-flex justify-content-end\">\
		<div class=\"col-sm-4 \">\
		<div class=\"form-group inline float-right\" id=\"fg_monto\">\
		<label for=\"monto\" class=\"col-form-label\"><legend>Monto $:</legend></label>\
		<input type=\"number\" class=\"form-control align-self-center\" id=\"monto\" >\
		</div>\
		</div>\
		<div class=\"col-sm-2 align-self-end\">\
		<div class=\"form-group \">\
		<div class=\"btn btn-primary\" id=\"bot_guardar\"onClick=\"front_call({method:'registro_operacion',sending:true,action:'save'})\"  href=\"#\" role=\"button\">Guardar\
		</div>\
		</div>\
		</div>\
		</div>\
		<hr>\
		<div class=\"row d-flex justify-content-center\">\
		<div class=\"col-sm-8\" id=\"result_footer\" ></div>\
		<div class=\"col-sm-2 align-self-end\">\
		<div class=\"form-group \">\
		<div class=\"btn btn-secondary\" id=\"bot_volver\"onClick=\"front_call({method:'back'})\"  href=\"#\" role=\"button\">Volver\
		</div>\
		</div>\
		</div>\
		</div>\
		</div>\
		</div>\
		</div>"
	}

}

// ** NUEVA PANTALLA DE REGISTRO DE OPERACIONES

// ** REGISTRO DE OPERACIONES
var reg_op = {
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	fill_select:function(name){
		// // console.log('setting select:',name)
		var x='';
		for (var i = 0; i < this._data.selects[name].length; i++) {
			var n = this._data.selects[name][i];
			x += "<option value="+n.id+">"+n.lbl+"</option>";
		}
		return x;

	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	set: function(v){
		this._data = v;
		let scr = "<div class='jumbotron jp-jumbotron'><div class='row d-flex justify-content-around'>";
		scr +="<div class='btn btn-normal' onClick=front_call({'method':'back'})><i class='material-icons'>arrow_back_ios</i></div>";
		scr +="<div class='jp-title'>REGISTRO DE OPERACIONES</div><div class='jp-title'>Fecha: "+v.fecha+"</div>"
		scr += "</div><hr/>";
		// CERRE EL ROW DE TITULO
		// row 1 tipo asiento y cuenta
		scr += "<div class='row d-flex justify-content-start'>";
		scr += "<div class='col d-flex'><div class='form-group' id='fg_tipo_asiento'><label for='tipo_asiento'>Tipo de Asiento</label>";
		scr += "<select class='form-control' id='tipo_asiento'><option value=\'\'>Selecciona el tipo de asiento</option><option value='INGRESOS'>Ingreso</option><option value=\'EGRESOS'>Egreso</option></select>";
		scr += "</div></div>";
		scr += "<div class='col d-flex'><div class='form-group' id='fg_cuenta'><label for='cuenta'>Cuenta</label>";
		scr += "<select class='form-control' id='cuenta'><option value=''>Selecciona la cuenta</option>"+this.fill_select('cuentas')+"</select>";
		scr += "</div></div>";
		scr += "<div class='col d-flex'></div>";
		scr +="</div><hr/>";
		// row 2 contraparte_tipo concepto y contraparte
		scr += "<div class='row d-flex justify-content-between'>";
		scr += "<div class='col d-flex'><div class='form-group' id='fg_contraparte_select'><label for='contraparte_select'>Contraparte</label>";
		scr += "<select class='form-control' id='contraparte_select' onChange=chk_tipo_contraparte() ><option value=\'\'>Selecciona una contraparte</option><option value='CLIENTE'>Cliente</option><option value=\'PROVEEDOR'>Proveedor</option></select>";
		scr += "</div>";
		scr += "</div>";
		scr += "<div class='col d-flex'>";
		scr += "<div class='form-group' id='fg_imputacion'><label for='imputacion'>Concepto</label>";
		scr += "<select class='form-control' id='imputacion'><option value=\'\'>Selecciona la imputación</option>";
		scr += this.fill_select('impt_prov')+"</select>";
		scr += "</div>";
		scr += "</div>";
		scr += "<div class='col d-flex'>";
		scr += "<div class='form-group' id='fg_contraparte'><label for='contraparte'></label>";
		scr += "<select class='form-control invisible' id='contraparte'><option value=''></option>"+0+"</select>";
		scr += "</div>";
		scr += "</div>";
		scr += "</div><hr/>";
		// row 3 centros de costo container
 		// row 1 cctos
		scr += "<div id='centro_costos_container' >"
		scr += "<div class='row d-flex justify-content-between'>";
		scr += "<div class='col d-flex'>";
		scr += "<div class='form-group' id='fg_cent_ctos_"+TOP.count_centro_costos_list+"''><label for='cent_ctos'>Centro de Costos</label>";
		scr += "<select class='form-control' id='cent_ctos_"+TOP.count_centro_costos_list+"' onChange=select_cctos_id(this.id) ><option value=''>Selecciona el Centro de Costos</option>\
		"+this.fill_select('barrio')+"</select>";
		scr += "</div>";
		scr += "</div>";

		scr += "<div class='col d-flex'>";
		scr += "<div class='form-group' id='fg_percent_cctos_"+TOP.count_centro_costos_list+"'><label for='percent_barrio_"+TOP.count_centro_costos_list+"'>Distribución %</label>";
		scr += "<input type='number' max=100 min=0 class='form-control' id='percent_cctos_"+TOP.count_centro_costos_list+"'>";
		scr += "</div>";
		scr += "</div>";
		scr += "<div class='col d-flex'>";
		scr += "<div class='form-group' id='fg_add_cent_ctos''><label for='add_cent_ctos'>Agregar / Quitar</label><br/>";
		scr += "<div class='btn btn-normal align-bottom mr-2' onClick=add_cctos() href='#' role='button'><i class='material-icons '>control_point</i> </div>";
		scr += "<div class='btn btn-normal align-bottom' onClick=remove_cctos() href='#' role='button'><i class='material-icons '>remove_circle_outline</i></div>";
		scr += "</div>";
		scr += "</div>";
		// cierre row cct1
		scr += "</div>";
		// cierre de centro_costos_container
		scr += "</div><hr/>";
		// row 4 nro comprob y observac
		scr += "<div class='row d-flex justify-content-between'>";
		scr += "<div class='col d-flex'>";
		scr += "<div class='form-group'><label for='numero_comprobante ' class='col-form-label'>Nro. Comprobante</label>";
		scr += "<input type='text' class='form-control' id='numero_comprobante'>";
		scr += "</div>";
		scr += "</div>";
		scr += "<div class='col d-flex'>";
		scr += "<div class='form-group '><label for='observaciones' class='col-form-label'>Observaciones</label>";
		scr += "<input type='text' class='form-control' id='observaciones'>";
		scr += "</div>";
		scr += "</div>";
		scr += "<div class='col d-flex'></div>";
		scr += "</div><hr/>";
		// row 5 MONTO
		scr += "<div class='row d-flex justify-content-end'>";
		scr += "<div class='col d-flex'></div>";

		scr += "<div class='col d-flex'>";
		scr += "<div class='form-group row' id='fg_monto'><label for='monto' class='col-form-label'><legend>Monto $:</legend></label>";
		scr += "<div class='col d-flex pt-3'>";
		scr += "<input type='number' class='form-control' id='monto' >";
		scr += "</div>";
		scr += "</div>";
		scr += "</div>";
		// scr += "<div class='col d-flex '>";
		// scr += "<div class='form-group '><br/>";
		// scr += "</div>";
		// scr += "</div>";

		scr += "</div><hr/>";
		// ROW 6 footer mesg y bot guardar
		scr += "<div class='row d-flex justify-content-between'>";
		scr += "<div class='col d-flex' id='result_footer' ></div>";
		scr += "<div class='col d-flex'></div>";
		scr += "<div class='col d-flex '>"
		scr += "<div class='btn btn-normal btn-block' id='bot_guardar' onClick=front_call({method:'registro_operacion',sending:true,action:'save'})  href='#' role='button'><legend>Guardar</legend></div>";
		scr += "</div>";
		scr += "</div>";

		scr += "</div><hr/>";
		// cierre de  jumbotron
		scr += "</div>";

		this._screen = scr;
	}

}

// ** PANTALLA DE PASE ENTRE CAJAS
var pase_caja_screen = {
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	fill_select:function(name){
		// // console.log('setting select:',name)
		var x='';
		for (var i = 0; i < this._data.selects[name].length; i++) {
			var n = this._data.selects[name][i];
			x += "<option value="+n.id+">"+n.lbl+"</option>";
		}
		return x;

	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	set: function(v){

		this._data = v;
		this._screen =
		"<div class=\"jumbotron mt-5\" style=\"padding-top:25px;\">\
		<div class=\"row d-flex justify-content-around\">\
		<div class=\"col-sm-1\"><button type=\"button\" onClick=front_call({'method':'back'}) class=\"btn btn-primary\"><i class=\"material-icons \">arrow_back_ios</i></button>\
		</div>\
		<div class=\"col\">\
		<h4>Transferencia entre Cajas<h4>\
		</div>\
		<div class=\"col\">\
		<h5>Fecha: "+v.fecha+" </h5>\
		</div>\
		</div>\
		<hr>\
		<div class=\"row d-flex justify-content-between\">\
		<div class=\"col-sm-12\">\
		<div class=\"row d-flex justify-content-between\">\
		<div class=\"col-sm-6\">\
		<div class=\"form-group\" id=\"fg_cuenta_egreso\">\
		<label for=\"cuenta_egreso\">Cuenta Origen</label>\
		<select class=\"form-control\" id=\"cuenta_egreso\">\
		<option value=''>Selecciona la cuenta</option>\
		"+this.fill_select('cuentas')+"</select>\
		</select>\
		</div>\
		</div>\
		<div class=\"col-sm-6\" >\
		<div class=\"form-group inline float-right\" id=\"fg_monto_egreso\">\
		<label for=\"monto_egreso\" >Monto salida :</label>\
		<input type=\"number\" min=1 class=\"form-control align-self-center\" id=\"monto_egreso\" >\
		</div>\
		</div>\
		</div>\
		<hr>\
		<div class=\"row d-flex justify-content-between\">\
		<div class=\"col-sm-6\">\
		<div class=\"form-group\" id=\"fg_cuenta_ingreso\">\
		<label for=\"cuenta_ingreso\">Cuenta Destino</label>\
		<select class=\"form-control\" id=\"cuenta_ingreso\">\
		<option value=''>Selecciona la cuenta</option>\
		"+this.fill_select('cuentas')+"</select>\
		</select>\
		</div>\
		</div>\
		<div class=\"col-sm-6\" >\
		<div class=\"form-group inline float-right\" id=\"fg_monto_ingreso\">\
		<label for=\"monto_ingreso\">Monto Ingresado :</label>\
		<input type=\"number\" min=1 class=\"form-control align-self-center\" id=\"monto_ingreso\" >\
		</div>\
		</div>\
		</div>\
		<hr>\
		<div class=\"row d-flex justify-content-start\">\
		<div class=\"col-sm-8\">\
		<div class=\"form-group\">\
		<label for=\"observaciones\" class=\"col-form-label\">Observaciones</label>\
		<input type=\"text\" class=\"form-control\" id=\"observaciones\">\
		</div>\
		</div>\
		</div>\
		<hr>\
		<div class=\"row d-flex justify-content-end\">\
		<div class=\"col-sm-2 align-self-end\">\
		<div class=\"form-group \">\
		<div class=\"btn btn-primary\" id=\"bot_guardar\"onClick=\"front_call({method:'pase_entre_cajas',sending:true,action:'save'})\"  href=\"#\" role=\"button\">Guardar\
		</div>\
		</div>\
		</div>\
		</div>\
		<hr>\
		<div class=\"row d-flex justify-content-center\">\
		<div class=\"col-sm-8\" id=\"result_footer\" ></div>\
		<div class=\"col-sm-2 align-self-end\">\
		<div class=\"form-group \">\
		<div class=\"btn btn-secondary\" id=\"bot_volver\"onClick=\"front_call({method:'back'})\"  href=\"#\" role=\"button\">Volver\
		</div>\
		</div>\
		</div>\
		</div>\
		</div>\
		</div>\
		</div>"
	}

}


// SELECCIONAR monto de cuota y confirmar la cuota a refinanciar
const dialog_refi = {
	_scrn:''
	,get_screen:function(){return this._scrn}
	,create:function(v){
		const o = Object.create(this);
		o.set(v);
		return o;
	}
	,set: function(v){
		let r = "<div class=\"row d-flex justify-content-center\"><div class=\"col d-flex justify-content-center\">";
		r +="<ul class=\'list-group\'>"
		r +="<li class=\'list-group-item d-flex justify-content-between align-items-center\'>Numero de Cuota:";
		r +="<span class=\'mb-0\'>"+get_pcle(v.lote.cta_upc.events[0],'nro_cta')+"</span></li>"

		r +="<li class=\'list-group-item d-flex justify-content-between align-items-center\'>Fecha de Vencimiento:";
		r +="<span class=\'mb-0\'>"+get_pcle(v.lote.cta_upc.events[0],'fecha_vto')+"</span></li>"
		r +="<li class=\'list-group-item d-flex justify-content-between align-items-center\'>Monto total a Refinanciar :";
		r += "<input type=\"number\" class=\"form-control\" value ="+parseInt(get_pcle(v.lote.cta_upc.events[0],'monto_cta'))+"  id=\'refi_cta_monto\' onBlur=validate_field(\'refi_cta_monto\') onChange=validate_field(\'refi_cta_monto\') style=\'width: 230px;\'>"
		r +="</li>";

	if(v.srv.length > 0){
		let totserv=0;
		let refi_servs_id = [];
		r +="<li class=\'list-group-item d-flex justify-content-between align-items-center\'><legend>Servicios</legend></li>";
		TOP.refi_srv_events_id = [];
		for (var i = 0; i < v.srv.length; i++) {

			if(v.srv[i]['srvc_name'].indexOf('Refinanc') == -1 && v.srv[i].cta_upc.events.length > 0 ){
				r +="<li class=\'list-group-item d-flex justify-content-between align-items-center\'>Servicio:";
				r +="<span class=\'mb-0\'>"+v.srv[i]['srvc_name']+"</span></li>"

				r +="<li class=\'list-group-item d-flex justify-content-between align-items-center\'>Nro. de Cuota Refinanciada:";
				r +="<span class=\'mb-0\'>"+get_pcle(v.srv[i].cta_upc.events[0],'nro_cta')+"</span></li>"


				r +="<li class=\'list-group-item d-flex justify-content-between align-items-center\'>Fecha de Vencimiento:";
				r +="<span class=\'mb-0\'>"+get_pcle(v.srv[i].cta_upc.events[0],'fecha_vto')+"</span></li>"

				r +="<li class=\'list-group-item d-flex justify-content-between align-items-center\'>Monto a Refinanciar:";
				r +="<span class=\'mb-0\'>"+get_pcle(v.srv[i].cta_upc.events[0],'monto_cta')+"</span></li>"

				r +="</li>";
				TOP.refi_srv_events_id.push(v.srv[i].cta_upc.events[0]['id']);
				totserv += parseInt(get_pcle(v.srv[i].cta_upc.events[0],'monto_cta'));
				refi_servs_id.push(get_pcle(v.srv[i].cta_upc.events[0],'id'));
			}
		}
		r +="<li class=\'list-group-item d-flex justify-content-between align-items-center\'>Total Servicios a Refinanciar:";
		r += "<input type=\"number\" class=\"form-control mb-0\" value ="+totserv+" id=\'refi_srv_monto\' onBlur=validate_field(\'refi_srv_monto\') onChange=validate_field(\'refi_cta_monto\') style=\'width: 230px;\'>"
		r +="</li></ul>"
	}
	this._scrn = r;
	}
}


// SELECCIONAR ARCHIVO PARA SUBIR AL SERVIDOR
const dialog_upload = {
	_scrn:''
	,get_screen:function(){return this._scrn}
	,create:function(v){
		const o = Object.create(this);
		o.set(v);
		return o;
	}
	,set: function(v){
		this._scrn =
	"<form id=\"upload_form\" method=\"post\" enctype=\"multipart/form-data\"><div class=\"form-group\"><input type=\"file\" class=\"form-control-file\" id=\"file_to_upload\" aria-describedby=\"fileHelp\"><small id=\"fileHelp\" class=\"form-text text-muted\">seleciona el archivo para adjuntar y clickea el boton OK.</small></div></format>";
	}
}


// DIALOG RESCISION DE CONTRATO
var dialog_rscn = {
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		this._screen = obj.set(val);
		return obj;
	},
	get_screen:function(){return this._screen},
	set: function(v){

		//  *** SELECT DE FINANCIACION

		let r = "<div class=\"row d-flex justify-content-center\"><div class=\"col d-flex justify-content-center\">";

		// *** MONTO A REINTEGRAR (falta la tabla de %) Y NRO. DE OPERACION DEL REINTEGRO
		r += number_obj.create({label:'mto_reintegro',title:'Monto a Reintegrar:',value:v.data.mto_reintegro}).get_screen();
		r +="</div><div class=\"col d-flex justify-content-center \">";
		r += text_obj.create({label:'reintegro_nro_op',title:'Nro. de Operacion de Reintegro:',value:v.data.reintegro_nro_op}).get_screen();

		r +="</div></div><hr/>";
		r +="<div class=\"row d-flex justify-content-center \"><div class=\"col d-flex justify-content-center \">";

		// *** MEDIO DE RESCISION Y NUMERO DE COMPROBANTE DE RESCISION
		r += select_obj.create({label:'rscn_tipo_id',title:'Medio de Rescisión',value:v.data.rscn_tipo_id}).get_screen();
		r +="</div><div class=\"col d-flex justify-content-center \">";
		r += text_obj.create({label:'rscn_nro_compr',title:'Nro. de Comprobante:',value:v.data.rscn_nro_compr}).get_screen();
		r +="</div></div><hr/>";
		r +="</div>";
		return r;
	}
}

//***** DIALOG INGRESAR DATOS DEL PAGO *****
const dialog_ingresar_pago = {
	_screen:{},
	create:function(){
		var obj = Object.create(this);
		obj.set();
		return obj;
	},
	get_screen:function(){return this._screen},
	set: function(){
		//  SETEA CAJA ESCOBAR POR DEFECTO PARA USUARIOS OF CAJA
		let def_val = 0;x = '';
		if(TOP.permisos > 1 && TOP.permisos < 5){def_val = 1}
		//  **
		x = "<div class=\"row mb-5 d-flex justify-content-around\">";
		x +="<div class=\"col\">" +date_obj.create({label:'fecha_pago',title:'Fecha de pago'}).get_screen()+"</div>";
		x +="<div class=\"col\">" +select_obj.create({label:'cuentas',title:'Cuenta',value:def_val}).get_screen() + "</div>";
		x += "</div>";
		x +="<div class=\"row d-flex justify-content-center\">";
		x +="<div class=\"form-group form-inline p-1\">";
		x +="<label for=\"monto_recibido\" class=\"col-form-label\">Monto $:&nbsp;</label>";
		x +="<input type=\"text\" class=\"form-control\" id=\"monto_recibido\" >";
		x += "</div>";
		this._screen = x;
	}
}

// SELECT NUEVO PLAN DE FINANC EN CAMBIO DE CICLO
var dialog_new_plan = {
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	set: function(v){
		this._data = v;
		this._screen ="<div class=\"row d-flex justify-content-center\">";
		this._screen +="<div class=\"col d-flex \"><div class=\"form-group row\"><label class=\'col-form-label\'  for=\"lfp\">Última fecha de Pago:</label><input type=\"text\" id=\'lfp\' readonly=\"\" class=\"form-control-plaintext text-left\" value=\'"+v.last_fec_pago+"\'></div></div>";

		this._screen +="<div class=\"col  d-flex \"><div class=\"form-group row\"><label class=\'col-form-label\' for=\"new_monto\">Monto $:</label><input type=\"number\" id=\'new_monto\' class=\"form-control \" value=\'"+v.last_monto_pagado+"\'></div></div>";
		this._screen +="</div>";
		this._screen +="<hr/><div class=\"row d-flex justify-content-left\">";

		this._screen +=select_obj.create({label:'financ_ciclo2',title:'Segundo ciclo del plan de financiación '}).get_screen();

		this._screen +="</div>";
		// <div class=\"col\">\
		// <div class=\"form-group\" id=\"fg_financ_plan_select\">\
		// <label for=\"financ_plan_select\">Selecciona el Plan de Financiación</label>\
		// <select class=\"form-control\" id=\"financ_plan_select\"\"><option value=''>Selecciona</option>\
		// "+this.fill_select('financ_ciclo2')+"</select>\
		// </div>\
		// </div>\
		// </div>\
		// ";
		this._screen +="<hr/><div class=\"row d-flex justify-content-center\"><div class=\"col p-2\">";
		this._screen +=date_obj.create({label:'financ_plan_fec_prox_venc',title:'Proximo Vencimiento'}).get_screen();
		this._screen +="</div></div>";
	}
}


// SELECT REVISION PLAN DE FINANC
var dialog_revision_fplan = {
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		this._screen = obj.set(val);
		return obj;
	},
	get_screen:function(){return this._screen},
	set: function(v){
		//  *** SELECT DE FINANCIACION

		let c= {cant_ctas_restantes:0,monto_cuota:0}
		let r = "<div class=\"row d-flex justify-content-center\"><div class=\"col d-flex justify-content-center\">";
		//  **** SELECT DEL PLAN Y CANT CUOTAS PAGAS
		r += select_obj.create({label:'rev_fplan',title:'Plan de Financiación',value:v.data.financ_id}).get_screen();
		r +="</div><div class=\"col d-flex justify-content-center \">";
		r += number_obj.create({label:'last_pay_amount',title:'Cuotas Pagas:',value:v.data.last_pay_ord_num,readonly:true}).get_screen();
		r +="</div></div><hr/>";
		//****  DATOS DEL PLAN
		r +="<div class=\"row d-flex justify-content-center \"><div class=\"col d-flex justify-content-center \">";
		r += text_obj.create({label:'last_pay_date',title:'Fecha Último Pago:',value:v.data.last_pay_date,readonly:true}).get_screen();
		r +="</div>"

		r +="<div class=\"col d-flex justify-content-center \">";
		r += number_obj.create({label:'last_pay_amount',title:'Monto Pagado $',value:v.data.last_pay_amount,readonly:true}).get_screen();
		r +="</div>"

		r +="<div class=\"col d-flex justify-content-center \">";
		r += number_obj.create({label:'saldo',title:'Saldo a Pagar $',value:v.data.saldo_a_pagar,readonly:true}).get_screen();
		r +="</div>";

		r +="</div><hr/>";
		r +="<div class=\"row d-flex justify-content-center\">";
		r +="<div class=\"col d-flex justify-content-center \">";
		r += number_obj.create({label:'cant_ctas_rest',title:'Ctas. Restantes',value:c.cant_ctas_restantes,readonly:true}).get_screen();
		r +="</div>";

		r +="<div class=\"col d-flex justify-content-center \">";
		r += number_obj.create({label:'monto_cta_rest',title:'Monto Prox. Cuota $',value:c.monto_cuota}).get_screen();
		r +="</div>";

		r +="</div>";
		return r;
	}
}

//  config select del atom va a deprecate
var dialog_atom = {
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	fill_select:function(name){
		var x='';
		for (var i = 0; i < this._data.selects[name].length; i++) {
			var n = this._data.selects[name][i];
			x += "<option value="+n.id+">"+n.lbl+"</option>";
		}
		return x;

	},
	set: function(v){
		this._data = v;
		this._screen =
		"<div class=\"row d-flex justify-content-center\">\
		<div class=\"col\">\
		<div class=\"form-group\" id=\"fg_atm_select\">\
		<label for=\"caja\">Obtener Listado de Items</label>\
		<select class=\"form-control\" id=\"atm_select\"\"><option value=''>Selecciona</option>\
		"+this.fill_select('atoms')+"</select>\
		</div>\
		</div>\
		</div>\
		";
	}

}
//  config select del atom
var dialog_revision = {
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	set: function(v){
		this._data = v;
		var c = "<div class=\"row d-flex justify-content-center\"><div class=\"col\"><div class=\"form-group\"><label class=\"col-form-label\" for=\"lote\">Numero de Lote</label><input type=\"text\" class=\"form-control\" id=\"rev_lote\" value=\""+(this._data.lote != undefined ? this._data.lote : '')+"\"></div></div></div>";
		c +="<div class=\"row d-flex justify-content-center\"><div class=\"col\"><div class=\"form-group\"><label class=\"col-form-label\" for=\"coment\">Mensage</label><input type=\"text\" class=\"form-control\" id=\"rev_coment\" placeholder=\""+(this._data.coment != undefined ? this._data.coment : '')+"\"></div></div></div>";

		c +="<div class=\"row d-flex justify-content-center\"><div class=\"col\"><div class=\"form-group\">"+select_obj_by_name.create({label:'asignado_a',title:'Asignar A'}).get_screen()+"</div></div></div>";

		this._screen = c;
	}
}

//  config select del tablas contab
var dialog_contab = {
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	fill_select:function(name){
		var x='';
		for (var i = 0; i < this._data.selects[name].length; i++) {
			var n = this._data.selects[name][i];
			x += "<option value="+n.id+">"+n.lbl+"</option>";
		}
		return x;

	},
	set: function(v){
		this._data = v;
		this._screen =
		" <div class=\"row d-flex justify-content-center\">\
		<div class=\"col\">\
		<div class=\"form-group\" id=\"fg_contab_select\">\
		<label for=\"caja\">Obtener Listado Items de caja</label>\
		<select class=\"form-control\" id=\"contab_select\"\"><option value=''>Selecciona</option>\
		"+this.fill_select('contab')+"</select>\
		</div>\
		</div>\
		</div>\
		";
	}

}

// *** arqueo DIALOG WINDOW
var dialog_arqueo= {
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	fill_select:function(name){
		var x='';
		for (var i = 0; i < this._data.selects[name].length; i++) {
			var n = this._data.selects[name][i];
			x += "<option value="+n.id+">"+n.lbl+"</option>";
		}
		return x;

	},
	set: function(v){
		this._data = v;
		this._screen =
		"<div class=\"row d-flex justify-content-center\">\
		<div class=\"col-sm-4\">"+date_obj.create({label:'fec_desde',title:'Desde Fecha'}).get_screen()+"</div>\
		<div class=\"col-sm-4\">"+date_obj.create({label:'fec_hasta',title:'Hasta Fecha'}).get_screen()+"</div>\
		<div class=\"col-sm-4\">\
		<div class=\"form-group\" id=\"fg_caja\">\
		<label for=\"caja\">Caja o Banco</label>\
		<select multiple =\'\' class=\"form-control\" id=\"caja\"\"><option value=''>Selecciona</option>\
		"+this.fill_select('cuentas')+"</select>\
		</div>\
		</div>\
		</div>\
		";
	}

};

// *** DIALOG de rango de fechas
var dialog_date_range= {
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get_screen:function(){return this._screen},
	// fill_select:function(name){
	// 	var x='';
	// 	for (var i = 0; i < this._data.selects[name].length; i++) {
	// 		var n = this._data.selects[name][i];
	// 		x += "<option value="+n.id+">"+n.lbl+"</option>";
	// 	}
	// 	return x;

	// },
	set: function(v){
		this._screen =
		"<div class=\"row d-flex justify-content-around\">\
		<div class=\"col\">"+date_obj.create({label:'fec_desde',title:'Desde Fecha'}).get_screen()+"</div>\
		<div class=\"col\">"+date_obj.create({label:'fec_hasta',title:'Hasta Fecha'}).get_screen()+"</div>\
		</div>\
		";
	}

};

// *** CRUDE DIALOG WINDOW
var dialog_crude= {
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	set: function(v){
		this._data = v;
		this._screen =
		"<div class=\"form-group width-100\">\
		<label class=\"col-form-label\" for=\"atom_name\">"+v.label+"</label>\
		<input type=\"text\" class=\"form-control\" placeholder=\""+v.placeholder+"\" id=\"atom_name\">\
		</div>\
		"
	}
};

// *******************
// *** VISUAL OBJECTS *
// *******************

var container_obj={
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	set: function(v){
		this._data = v;
		this._screen ="<div class=\"d-flex align-content-stretch flex-wrap  \">";
		this._screen += v;
		this._screen +="</div>";
	}
};

// *** 21/11/2019 -- NEW OBJETOS DE PANTALLA CONTIENEN LA LLAMADA A FRONT_CALL PARA UPDATE O NULL EN EL ONCHANGE
// VOC VISUAL OBJECT CONTEXT ES EL FORM GROUP Y EL LABEL COMUN EN TODOS LOS OBJECT DE PANTALLA
var voc = {
	_oid:null,
	create:function(val){
		var obj = Object.create(this);
		return obj.set(val);
	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	set: function(v){
		let x = '';
		if(v.label && v.id){this._oid = v.label+"_"+v.id;}
		else{this._oid = v.label;}
		x += "<div class='d-flex align-content-start flex-wrap p-1 m-1 '>";
		x += "<div class='form-group width-100' id='fg_"+this._oid+"' >";
		x += "<label class='col-form-label' for='"+this._oid+"' style='text-transform:capitalize;'>";
		x += (v.title == ''?v.label.charAt(0).toUpperCase() + v.label.slice(1):v.title)+"</label>";
		return x;

	}
}

var text_obj_updater = {
	_screen:{},
	_oid:null,
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	set: function(v){
		let x = '';
		this._oid = v.id;
		x += voc.create(v);
		x += "<input type='text' id='"+this._oid+"' class='form-control' ";
		x += (v.hasOwnProperty('readonly')&&v.readonly == true ?'readonly=\'\'': '')
		x += " value ='"+(v.value!=null?v.value:'')+"'  style='width: 230px;'";

		x += " onChange='"+(v.hasOwnProperty('front_call')?"front_call("+JSON.stringify(v.front_call)+")":null)+"' >";
		x += "</div></div>";
		this._screen = x;
	}
};

// OBJ NUMBER
var number_obj_updater = {
	_screen:{},
	_oid:null,
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	set: function(v){
		let x = '';
		// this._oid = v.label+"_"+v.id;
		this._oid = v.id;
		x += "<div class='d-flex align-content-start flex-wrap p-1 m-1 '>";
		x += "<div class='form-group id='fg_"+this._oid+"' >";
		x += "<label class='col-form-label' for='"+this._oid+"' style='text-transform:capitalize;'>";
		x += (v.title == ''?v.label.charAt(0).toUpperCase() + v.label.slice(1):v.title)+"</label>";
		x += "<input type='number' id='"+this._oid+"' class='form-control' ";
		x += (v.hasOwnProperty('readonly')&&v.readonly == true ?'readonly=\'\'': '')
		x += " value ='"+(v.value!=null?v.value:'')+"'  style='width: 230px;'";
		// si esta en update screen y tiene la llamada de update a front_call o
		// solo valida el campo para el caso en que el label este contemplado
		if(v.hasOwnProperty('front_call')){
			x += " onChange='front_call("+JSON.stringify(v.front_call)+")'";
		}else{
			x += " onBlur='validate_field('"+v.label+"')'";
			x += " onChange='validate_field('"+v.label+"'')";
		}
		//***  CIERRE DEL TAG DE INPUT
		x += "style='width: 230px;' >";
		x += "</div></div>";
		this._screen = x;
	}
};

// OBJ DATE PICKER
var date_obj_updater={
	_oid:null,
	_screen:'',
	create:function(val){
		this._data = val;
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get:function(p){return this._data[p]},
	get_screen:function(){return this._screen},
	set: function(v){
		// console.log('setting date',v);
		let x = '';
		// this._oid = v.label+"_"+v.id;
		this._oid = v.id;
		x += voc.create(v);
		x +="<div class=\"input-group date\" >";
		x +="<input type='text' class='form-control' id='"+this._oid+"' value='"+v.value+"'";
		x += (v.hasOwnProperty('readonly')&&v.readonly == true ?'disabled=\'\' readonly=\'\' ': '');
		x +="placeholder=\"Selecciona una fecha\" onblur=";
		x += (v.hasOwnProperty('front_call')? "front_call(" + JSON.stringify(v.front_call).replace(/"/g,"'") + ")":'')+" >" ;
		x +="</div>";
		x +="<script type=\"text/javascript\">$(function () { $('#"+this._oid+"').datetimepicker({ locale: 'es', allowInputToggle: true, format: 'DD/MM/YYYY',showClear: true, showClose: true, ignoreReadonly: true }); });</script>";
		x +="</div></div>";
		this._screen = x;
	}
};

// OBJ_UPDATER SELECTOR
var select_obj_updater = {
	_screen:'',
	_oid:null,
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get_screen:function(){return this._screen},
	set: function(v){
		// this._oid = v.label+"_"+v.id;
		this._oid = v.id;
		let x = voc.create(v);

		x +="<select class=\"form-control\" id=\""+this._oid+"\""
		x += (v.hasOwnProperty('readonly')&&v.readonly == true ?" disabled=\'\' readonly=\'\' ": '');
		x += " onChange='"+(v.hasOwnProperty('front_call')?"front_call("+JSON.stringify(v.front_call)+")":null)+"' style=\'width: 230px;\' >";
		x +=  "<option value='-1'>Selecciona</option>";
		// console.log('selects',TOP.selects);
		if(TOP.hasOwnProperty('selects')){
			if(TOP.selects[v.label] != undefined){
				for (var i = 0; i < TOP.selects[v.label].length; i++) {
					var sl = (TOP.selects[v.label][i].id == v.value)?"selected=\"selected\"":" ";
					x += "<option value="+TOP.selects[v.label][i].id+" "+sl+" > "+TOP.selects[v.label][i].lbl+"</option>";
				}
			}
		}
		x +="</select></div></div>";
  	    this._screen = x;
  	}
}




/* *** hay que hacer objects para edicion o revisar
var text_readonly_obj={
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	set: function(v){
		this._data = v;
		// console.log('title ',v.title)
		this._screen =
		"<div class=\"d-flex align-content-start flex-wrap p-1 m-1 \">\
		<div class=\"form-group width-100\" id=\"fg_"+v.label+"\">\
		<label class=\"col-form-label\" for=\""+v.label+" style=\"text-transform:capitalize;\">"+(v.title == ''?v.label.charAt(0).toUpperCase() + v.label.slice(1):v.title)+"</label>\
		<input type=\"text\" class=\"form-control\" readonly=\"\" value =\""+(v.value!=null?v.value:'')+"\" id=\""+v.label+"\" style=\'width: 230px;\'>\
		</div>\
		</div>"
	}
};

// OBJ TEXTAREA
var textarea_obj={
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	set: function(v){
		console.log('mktext area',v.label)
		let r = "<div class=\"d-flex align-content-start flex-wrap p-1 m-1\">";
		r += "<div class=\"form-group width-100\" id=\"fg_"+v.label+"\">";
		r += "<label class=\"col-form-label\" for=\""+v.label+" style=\"text-transform:capitalize;\">"+(v.hasOwnProperty('title') && v.title != ''?v.title:v.label.charAt(0).toUpperCase() + v.label.slice(1))+"</label>";
		r += "<textarea rows=\"4\" class=\"form-control\" "+(v.hasOwnProperty('readonly')&&v.readonly == true ?'readonly=\"\"': '')+" id=\""+v.label+"\" >"+(v.value!=null?v.value:'')+"</textarea>";
		r += "</div></div>";
		this._screen = r;
	}
};

// <label for="exampleTextarea">Example textarea</label>

//  onChange="+(v.hasOwnProperty('front_call')?front_call(v.front_call):check_select(v.label))+"
// OBJ NUMBER
var number_obj={
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	set: function(v){
		this._data = v;
		this._screen =
		"<div class=\"d-flex align-content-start flex-wrap p-1 m-1 \">\
		<div class=\"form-group\" id=\"fg_"+v.label+"\">\
		<label class=\"col-form-label "+(v.vis_ord_num == -1?'d-none':'')+"\" id=\'lbl_"+v.label+"\' for=\""+v.label+"\" style=\"text-transform:capitalize;\">"+(v.title == null?v.label:v.title)+"</label>\
		<input type=\"number\" class=\"form-control "+(v.vis_ord_num == -1?'d-none':'')+"\" "+(v.hasOwnProperty('readonly')&&v.readonly == true ?'readonly=\"\"': '')+" value =\""+(v.value!=null?v.value:'')+"\" id=\""+v.label+"\" onBlur=validate_field(\""+v.label+"\") onChange=validate_field(\""+v.label+"\") style=\'width: 230px;\'>\
		</div>\
		</div>";
	}
};

// OBJ DATE PICKER
var date_obj={
	_data:{},
	_screen:{},
	create:function(val){
		this._data = val;
		var obj = Object.create(this);
		obj.set();
		return obj;
	},
	get:function(p){return this._data[p]},
	get_screen:function(){return this._screen},
	set: function(){

		var v = this._data;
		// console.log('date ', v);
		let x = "<div class=\"d-flex align-content-start flex-wrap p-1 m-1\">";
		x +="<div class='form-group' id='fg_"+v.label+"'>";
		x +="<label class=\"col-form-label\" for=\""+v.label+"\">"+v.title+"</label>";
		x +="<div class='input-group date' >";
		x +="<input type='text' class='form-control' id='"+v.label+"' value='"+v.value+"' placeholder='Selecciona una fecha' onChange='"+(v.hasOwnProperty('front_call')?"front_call("+JSON.stringify(v.front_call)+")":'')+"' >";
		x +="</div>";
		x +="<script type=\"text/javascript\""+(v.hasOwnProperty('readonly')&&v.readonly == true ?'disabled': '')+" >$(function () { $('#"+v.label+"').datetimepicker({ locale: 'es', allowInputToggle: true, format: 'DD/MM/YYYY',showClear: true, showClose: true, ignoreReadonly: true }); });</script>";
		x +="</div></div>";
	}
};


const select_obj_by_name = {
	_sn:'',
	create:function(v){
		const o = Object.create(this);
		o.set(v);
		return o;
	},
	get_screen:function(){return this._sn},
	set: function(v){
		let c = "<div class=\"d-flex align-content-start flex-wrap p-1 m-1\"><div class=\"form-group\">";
		c += (v.hasOwnProperty('title')?"<label class=\"col-form-label\" for=\""+v.label+" style=\"text-transform:capitalize;\">"+(v.title == null?v.label : v.title)+"</label>":"");
		c += " <select class=\"form-control\" style=\'width: 7em;\' id=\""+v.label+" onChange=\'"+(v.hasOwnProperty('front_call')?"front_call("+JSON.stringify(v.front_call)+")":check_select(v.label))+" style=\'width: 230px;\'><option value=''>Selecciona</option>";
		const x = v.label;
		if(TOP.hasOwnProperty('selects')){
			if(TOP.selects[x] != undefined){
				for (let i = 0; i < TOP.selects[x].length; i++) {
					let sl = (TOP.selects[x][i].lbl == v.value)?"selected=\"selected\"":"";
					c += "<option "+sl+" > "+TOP.selects[x][i].lbl+"</option>";
				}
			}
		}
		c +="</select></div></div>";
		this._sn = c;
	}
}

// OBJ SELECTOR
var select_obj = {
	_screen:'',
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get_screen:function(){return this._screen},
	set: function(v){
		this._screen ="<div class=\"d-flex align-content-start flex-wrap p-1 m-1 \"><div class=\"form-group\">";
		if(v.title != 'no_title'){
			this._screen +="<label class=\"col-form-label\"  for=\""+v.label+" style=\"text-transform:capitalize;\">"+(v.title == null?v.label : v.title)+"</label>";
		}
		this._screen +="<select class=\"form-control\" id=\""+v.label+"\" "+(v.hasOwnProperty('readonly')&&v.readonly == true ?'disabled': '')+" onChange=\'"+(v.hasOwnProperty('front_call')?"front_call("+JSON.stringify(v.front_call)+")":check_select(v.label))+"  style=\'width: 230px;\' ><option value='-1'>Selecciona</option>";
		var x = v.label;
		if(TOP.hasOwnProperty('selects')){
  	    		// console.log('selector_obj',TOP.selects)
  	    		if(TOP.selects[x] != undefined){
  	    			for (var i = 0; i < TOP.selects[x].length; i++) {
  	    				var sl = (TOP.selects[x][i].id == v.value)?"selected=\"selected\"":" ";
  	    				this._screen += "<option value="+TOP.selects[x][i].id+" "+sl+" > "+TOP.selects[x][i].lbl+"</option>";
  	    			}
  	    		}
  	    	}
  	    	this._screen +="</select></div></div>";
  	    }
  	}


*/

// *** CURRENT WORKING DUPLICATED OBJECT DE PANTALLA
var text_obj = {
	_oid:null,
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	set: function(v){
		// console.log('seting text',v)

		if(v.label && v.id){this._oid = v.label+"_"+v.id;}
		else{this._oid = v.label;}
		let x = voc.create(v);
		x += "<input type=\"text\" class=\"form-control\" ";
		x += (v.hasOwnProperty('readonly')&&v.readonly == true ?'readonly=\"\"': '');
		x += " value =\""+(v.value!=null?v.value:'')+"\" ";
		x += " id=\""+this._oid + "\" ";
		x += " style=\"width: 230px;\"></div></div>";
		this._screen = x;
	}
};


var text_readonly_obj={
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	set: function(v){
		this._data = v;
		// console.log('title ',v.title)
		this._screen =
		"<div class=\"d-flex align-content-start flex-wrap p-1 m-1 \">\
		<div class=\"form-group width-100\" id=\"fg_"+v.label+"\">\
		<label class=\"col-form-label\" for=\""+v.label+" style=\"text-transform:capitalize;\">"+(v.title == ''?v.label.charAt(0).toUpperCase() + v.label.slice(1):v.title)+"</label>\
		<input type=\"text\" class=\"form-control\" readonly=\"\" value =\""+(v.value!=null?v.value:'')+"\" id=\""+v.label+"\" style=\'width: 230px;\'>\
		</div>\
		</div>"
	}
};

// OBJ TEXTAREA
var textarea_obj={
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	set: function(v){
		console.log('mktext area',v.label)
		let r = "<div class=\"d-flex align-content-start flex-wrap p-1 m-1\">";
		r += "<div class=\"form-group width-100\" id=\"fg_"+v.label+"\">";
		r += "<label class=\"col-form-label\" for=\""+v.label+" style=\"text-transform:capitalize;\">"+(v.hasOwnProperty('title') && v.title != ''?v.title:v.label.charAt(0).toUpperCase() + v.label.slice(1))+"</label>";
		r += "<textarea rows=\"4\" class=\"form-control\" "+(v.hasOwnProperty('readonly')&&v.readonly == true ?'readonly=\"\"': '')+" id=\""+v.label+"\" >"+(v.value!=null?v.value:'')+"</textarea>";
		r += "</div></div>";
		this._screen = r;
	}
};

// <label for="exampleTextarea">Example textarea</label>

// *** CURRENT WORKING number OBJECT DE PANTALLA
var number_obj = {
	_oid:null,
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	set: function(v){
		if(v.label && v.id){this._oid = v.label+"_"+v.id;}
		else{this._oid = v.label;}
		let x = voc.create(v);
		x += "<input type=\"number\" class=\"form-control\" ";
		x += (v.hasOwnProperty('readonly')&&v.readonly == true ?'readonly=\"\"': '');
		x += " value =\""+(v.value!=null?v.value:'')+"\" ";
		x += " id=\""+this._oid + "\" ";
		x += " style=\"width: 230px;\"></div></div>";
		this._screen = x;
	}
};



// old OBJ NUMBER
var number_obj_old={
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	set: function(v){


		this._data = v;
		this._screen =
		"<div class=\"d-flex align-content-start flex-wrap p-1 m-1 \">\
		<div class=\"form-group\" id=\"fg_"+v.label+"\">\
		<label class=\"col-form-label "+(v.vis_ord_num == -1?'d-none':'')+"\" id=\'lbl_"+v.label+"\' for=\""+v.label+"\" style=\"text-transform:capitalize;\">"+(v.title == null?v.label:v.title)+"</label>\
		<input type=\"number\" class=\"form-control "+(v.vis_ord_num == -1?'d-none':'')+"\" "+(v.hasOwnProperty('readonly')&&v.readonly == true ?'readonly=\"\"': '')+" value =\""+(v.value!=null?v.value:'')+"\" id=\""+v.label+"\" onBlur=validate_field(\""+v.label+"\") onChange=validate_field(\""+v.label+"\") style=\'width: 230px;\'>\
		</div>\
		</div>";
	}
};

// OBJ DATE PICKER
var date_obj={
	_data:{},
	_screen:{},
	create:function(val){
		this._data = val;
		var obj = Object.create(this);
		obj.set();
		return obj;
	},
	get:function(p){return this._data[p]},
	get_screen:function(){return this._screen},
	set: function(){
		var v = this._data;
		this._screen =
		"<div class=\"d-flex align-content-start flex-wrap p-1 m-1\">\
		<div class=\"form-group\" id=\"fg_"+v.label+"\">\
		<label class=\"col-form-label\" for=\""+v.label+"\">"+v.title+"</label>\
		<div class='input-group date' >\
		<input type='text' class=\"form-control\" id=\""+v.label+"\" value="+(v.hasOwnProperty('value')?v.value:'')+"  placeholder=\"Selecciona una fecha\" readonly='readonly'/>\
		</div>\
		<script type=\"text/javascript\">$(function () { $('#"+v.label+"').datetimepicker({ locale: 'es', allowInputToggle: true, format: 'DD/MM/YYYY',showClear: true, showClose: true, ignoreReadonly: true }); });</script>\
		</div>\
		</div>";
	}
};


const select_obj_by_name = {
	_sn:'',
	create:function(v){
		const o = Object.create(this);
		o.set(v);
		return o;
	},
	get_screen:function(){return this._sn},
	set: function(v){
		let c = "<div class=\"d-flex align-content-start flex-wrap p-1 m-1\"><div class=\"form-group\">";
		c += (v.hasOwnProperty('title')?"<label class=\"col-form-label\" for=\""+v.label+" style=\"text-transform:capitalize;\">"+(v.title == null?v.label : v.title)+"</label>":"");
		c += " <select class=\"form-control\" style=\'width: 7em;\' id=\""+v.label+"\" onChange=front_call({method:\'"+v.method+"\',data:{id:\'"+v.id+"\',value:this.value}}) style=\'width: 230px;\'><option value=''>Selecciona</option>";
		const x = v.label;
		if(TOP.hasOwnProperty('selects')){
			if(TOP.selects[x] != undefined){
				for (let i = 0; i < TOP.selects[x].length; i++) {
					let sl = (TOP.selects[x][i].lbl == v.value)?"selected=\"selected\"":"";
					c += "<option "+sl+" > "+TOP.selects[x][i].lbl+"</option>";
				}
			}
		}
		c +="</select></div></div>";
		this._sn = c;
	}
}


// OBJ SELECTOR
var select_obj = {
	_screen:'',
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get_screen:function(){return this._screen},
	set: function(v){
		this._screen ="<div class=\"d-flex align-content-start flex-wrap p-1 m-1 \">";
		this._screen += "<div class=\"form-group\" id=\"fg_"+v.label+"\">";
		if(v.title != 'no_title'){
			this._screen +="<label class=\"col-form-label\"  for=\""+v.label+" style=\"text-transform:capitalize;\">"+(v.title == null?v.label : v.title)+"</label>";
		}
		this._screen +="<select "+(v.hasOwnProperty('multiple')?' multiple ':'')+"class=\"form-control\" id=\""+v.label+"\" onChange=check_select(\""+v.label+"\") style=\'width: 230px;\' ><option value='-1'>Selecciona</option>";
		var x = v.label;
		if(TOP.hasOwnProperty('selects')){
  	    		// console.log('selector_obj',TOP.selects)
  	    		if(TOP.selects[x] != undefined){
  	    			for (var i = 0; i < TOP.selects[x].length; i++) {
  	    				var sl = (TOP.selects[x][i].id == v.value)?"selected=\"selected\"":" ";
  	    				this._screen += "<option value="+TOP.selects[x][i].id+" "+sl+" > "+TOP.selects[x][i].lbl+"</option>";
  	    			}
  	    		}
  	    	}
  	    	this._screen +="</select></div></div>";
  	    }
  	}

		var select_multiple_obj = {
			_screen:'',
			create:function(val){
				var obj = Object.create(this);
				obj.set(val);
				return obj;
			},
			get_screen:function(){return this._screen},
			set: function(v){
				this._screen ="<div class=\"d-flex align-content-start flex-wrap p-1 m-1 \"><div class=\"form-group\">";
				if(v.title != 'no_title'){
					this._screen +="<label class=\"col-form-label\"  for=\""+v.label+" style=\"text-transform:capitalize;\">"+(v.title == null?v.label : v.title)+"</label>";
				}
				this._screen +="<select multiple class=\"form-control\" id=\""+v.label+"\" onChange=check_select(\""+v.label+"\") style=\'width: 230px;\' ><option value='-1'>Selecciona</option>";
				var x = v.label;
				if(TOP.hasOwnProperty('selects')){
		  	    		// console.log('selector_obj',TOP.selects)
		  	    		if(TOP.selects[x] != undefined){
		  	    			for (var i = 0; i < TOP.selects[x].length; i++) {
		  	    				var sl = (TOP.selects[x][i].id == v.value)?"selected=\"selected\"":" ";
		  	    				this._screen += "<option value="+TOP.selects[x][i].id+" "+sl+" > "+TOP.selects[x][i].lbl+"</option>";
		  	    			}
		  	    		}
		  	    	}
		  	    	this._screen +="</select></div></div>";
		  	    }
		  	}

// *** END CURRENT WORKING DUPLICATED OBJECT DE PANTALLA



// OBJ checkbox
var checkbox_obj = {
	_screen:'',
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get_screen:function(){return this._screen},
	set: function(v){
		var x = "<div class=\"custom-control custom-checkbox\">";
		x += "<input type=\"checkbox\" class=\"custom-control-input\" id=\""+v.label+"\" "+(v.value == 'true'?'checked':'')+">";
		x += "<label class=\"custom-control-label\" for=\""+v.label+"\">"+v.title+"</label>";
		x += "</div>";
		this._screen = x;

	}
}


// ** CONTRATO
var dialog_contrato={
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj;
	},
	get:function(val){return this._data[val]},
	get_screen:function(){return this._screen},
	set: function(v){
		this._data = v;
		var scr = "<div class=\"row\"><div class=\"col\" >";
		scr += date_obj.create({label:'date_contrato',title:'Fecha de inicio del contrato'}).get_screen();
		scr += "<hr>";
		scr += select_obj.create({label:'lt_disp',title:'Lote Numero'}).get_screen();
		scr += select_obj.create({label:'clientes',title:'Comprador'}).get_screen();
		scr += select_obj.create({label:'financiacion',title:'Plan de Financiacion'}).get_screen();
		scr += "<div class=\"col d-none\" id=\"cnt_"+v.label+"\" >";
		scr += numebr_obj.create({label:'anticipo',title:'Anticipo $'}).get_screen();
		scr +="</div>"
		scr += "<hr>";
		scr += number_obj.create({label:'mto_cta_1',title:'Monto de cuota 1'}).get_screen();
		scr += "</div></div>";
		this._screen += scr;
	}

};

// var dialog_new_atom = {
// 	_screen:{},
// 	create:function(val){
// 		var obj = Object.create(this);
// 		obj.set(val);
// 		return obj
// 	},
// 	get_screen:function(){return this._screen},
// 	set:function(v){
// 		let r = window[v.vet+'_obj'].create(v.value).get_screen();
// 	}
// }



// 21/11/2019
// CONSTRUYE UN SET DE INPUTS EDITABLES
// RECIBE UN ARRAY DE OBJETOS QUE EXPONEN LOS CAMPOS DE LA BASE DE DATOS
const editable_set = {
	_screen:{},
	create:function(val,extras){
		var obj = Object.create(this);
		obj.set(val,extras);
		return obj
	},
	get_screen:function(){return this._screen},
	set:function(v,xt){
		let t = '',dx =1;vet_obj= 'text';//date_obj.create({label:"srvc_fec_init",title:'Fecha de Inicio'}).get_screen();
		t += "<div class=\"row d-flex justify-content-around m-1\">";
		v.map(function(i){
			if(i.label != null){
				vet_obj = vet_check(i.vis_elem_type)
				// si encuentra elitem en redonly lo agrega al visual element
				if(xt.hasOwnProperty('readonly')){
					const found = xt.readonly.find(function(e) {
						  return e == i.label;
						});
					if(found){
						i.readonly = true;
					}
				}
				//** DO ELEMENTS UPDATE
				if(i.hasOwnProperty('elements_id')){
					switch(i.label){
						case 'fec_ini':
							// console.log('edit fec ini');
							i.front_call = {
								method:'pcle_updv_fec_ini',
								sending:false,
								// pcle_id:i.label+"_"+i.id,
								data:{
									type:"Element",
									pcle_id:i.id,
									prnt_id:i.elements_id,
									// id_id:i.id,
									// LID -> LOCAL ID ES EL ID DEL INPUT EN PANTALLA
									lid:i.label+"_"+i.id
								}
							};
						break;
						default:
							i.front_call = {
								method:'pcle_updv_cnfg',
								sending:true,
								// pcle_id:i.id,
								// prnt_id:i.atom_id,
								data:{
									type:"Element",
									prnt_id:i.elements_id,
									pcle_id:i.id,
									// prnt_id:i.atom_id,
									// LID -> LOCAL ID ES EL ID DEL INPUT EN PANTALLA
									lid:i.label+"_"+i.id
								}
							};
						break;
					}

				}
				//*** DO ATOM PCLES UPDATE
				if(i.hasOwnProperty('atom_id')){
					i.front_call = {
						method:'pcle_updv_cnfg',
						sending:true,
						// pcle_id:i.id,
						// pcle_id:i.label+"_"+i.id,
						// prnt_id:i.atom_id,
						data:{
							type:"Atom",
							// pcle_id:i.label+"_"+i.id,
							prnt_id:i.atom_id,
							pcle_id:i.id,
							// LID -> LOCAL ID ES EL ID DEL INPUT EN PANTALLA
							lid:i.label+"_"+i.id
						}
					};
				}
				// let xo = vet_obj+'_obj';
				// if(vet_obj == "text" || vet_obj == "number" ){
				// 	xo = vet_obj+'_obj_updater';
				// }

				const xo = vet_obj+'_obj_updater';
				// console.log(xo);
				t += window[xo].create(i).get_screen();
			}
		});
		t +="</div>"
		this._screen = t;
	},
}


const edit_modal = {
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj
	},
	get_screen:function(){return this._screen},
	set:function(v){
		console.log('edit modal',v);
		let t = '',dx =1;vet_obj= 'text';//date_obj.create({label:"srvc_fec_init",title:'Fecha de Inicio'}).get_screen();
		t += "<div class=\"row d-flex justify-content-around m-1\">";

		v.map(function(i){
      console.log('modal item',i);
      if(i.vis_elem_type !==	 '-2'){

				if(i.label == 'nombre_contacto' || i.label == 'nombre_segundo_contacto' ){
          t += "</div><hr/><hr/><div class=\'row d-flex justify-content-around m-1\'>";
        }
				t += window[i.vis_elem_type+'_obj'].create(i).get_screen();
				dx ++;
			}
		});
		t +="</div>"
		this._screen = t;
	},
};




const dialog_new_contrato = {
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj
	},
	get_screen:function(){return this._screen},
	set:function(v){
		console.log('dialog new contrato',v);
		let t = '',dx =1;//date_obj.create({label:"srvc_fec_init",title:'Fecha de Inicio'}).get_screen();
		t += "<div class=\"row d-flex justify-content-around m-1\">";

		v.data.map(function(i){

			if(i.vis_elem_type !==	 '-1'){
				t += window[i.vis_elem_type+'_obj'].create(i).get_screen();
				if((dx % 4) == 0 ){t += "</div><div class=\"row d-flex justify-content-around m-1\">"}
				dx ++;
			}


		});
		t +="</div>"
		this._screen = t;
	},
};


const dialog_new_elem = {
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj
	},
	get_screen:function(){return this._screen},
	set:function(v){
		console.log('dialog new element',v);
		let t = '',dx =1;
		t += "<div class=\"row d-flex justify-content-around m-1\">";
		v.data.map(function(i){
			if(i.vis_elem_type !==	 '-1'){
				t += window[i.vis_elem_type+'_obj'].create(i).get_screen();
				if((dx % 4) == 0 ){t += "</div><div class=\"row d-flex justify-content-around m-1\">"}
				dx ++;
			}
		});
		t +="</div>"
		this._screen = t;
	},
};

// NEW <--12/11-->
const dialog_update_plan = {
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		obj.set(val);
		return obj
	},
	get_screen:function(){return this._screen},
	get_fec_ven:function(){
		//10/02/2020  ANTES ESTABA TOMANDO UNA FECHA DE VENCIMIENTO POR PARAM Y AGREGANDOLE  UN MES
		// PERO LOS PAGOS ADELANTADOS ROMPEN ESTE TEMA DE AGREGAR UN MES A LAS FECHAS
		// ACTUALMENTE SOLO GENEAR UNA FECHA DEL DIA PARA PASARLE A date_obj
			// let f = d.filter(i=>{return i.label === 'fecha_vto'});

		// let dlv = f[0]['value'].split("/");
		// console.log('fec vto',new Date(dlv[2],parseInt(dlv[1]),dlv[0]))
		//******  ojo que aca en la creacion de la nueva fecha de VENCIMIENTO
		 // al dejar dlv[1] (el index del mes sin cambios estoy agregando un mes)
		 // por el tema de javascript y la descripcion de los mesese como un array con cero
		 // donde enero es cero etc...
		 // de todas formas la idea es sumar un mes al vencimiento anterior
		// return  dnv = moment(new Date(dlv[2],dlv[1],dlv[0])).format('DD/MM/YYYY');
		return moment(new Date()).format('DD/MM/YYYY');
	},
	set:function(v){
		// TOP.data = val;
		console.log('dialog updte_plan',v);
		let t = '',dx =1;
		t += "<div class=\"row d-flex justify-content-start m-1\">";
			v.ctr_data.map(function(i){
				if(i.vis_elem_type !==	 '-1'){
					t += window[i.vis_elem_type+'_obj'].create(i).get_screen();
					if((dx % 4) == 0 ){t += "</div><div class=\"row d-flex justify-content-start m-1\">"}
					dx ++;
				}
			});

		t +="</div><hr />"
		t += "<div class=\"row d-flex justify-content-start m-1\">";
		v.ctr_fields.map(function(i){
			if(i.vis_elem_type !==	 '-1'){
				t += window[i.vis_elem_type+'_obj'].create(i).get_screen();
				if((dx % 4) == 0 ){t += "</div><div class=\"row d-flex justify-content-start m-1\">"}
				dx ++;
			}
		});
		t += date_obj.create({label:'update_plan_fec_prox_venc',title:'Proximo Vencimiento',value:this.get_fec_ven()}).get_screen();
		if(v.hasOwnProperty('archivos_lote')){
			t += this.get_archivos_lote(v.archivos_lote,'lote_data_gen');
		}

		t +="</div>"
		this._screen = t;
	},


	get_archivos_lote: function(list,folder){
		let x =[];
		if(list.length > 0){
			x = list
		}
		if(x.length > 0){
			data = x.map(i=>{return {'':"<a href=\"./uploads/"+folder+"/"+i+"\" target='_blank'>"+i+"</a>"};});
			r ="<div class=\'col d-flex flex-wrap p-2 \' id=\'col_"+folder+"\' >";
			// console.log('web_cli',folder.search('web_cli'))
			// ** CREA EL TABLE Y EL DATA BOX PARA LOS FILES UPLD
			r += data_box_small.create({label: ' '+x.length+' '+(x.length == 1?'Archivo de ':'Archivos de ')+' '+(folder.search('web_cli')> -1 ?" Clientes":" Administradores"),id: folder+"_panel_uploaded",value: otbl.create(data,folder+'_tbl_uploaded_files')}).get_screen();
			r += "</div>";
			return r;
		}else{
			return "<div class=\'col d-flex flex-wrap p-2 \' id=\'col_"+folder+"\' ></div>";
		}
	}

};


//  modl win create service
var dialog_new_service ={
	_data:{},
	_screen:{},
	create:function(val){
		var obj = Object.create(this);
		this._data = val;
		obj.set();
		return obj
	},
	get:function(p){return this._data[p]},
	get_screen:function(){return this._screen},
	set:function(){
		console.log('dialog new service',this._data);
		var tx = '';//date_obj.create({label:"srvc_fec_init",title:'Fecha de Inicio'}).get_screen();
		tx += "<div class=\"row d-flex justify-content-start\">";

		this._data.struct.map(function(i){
			tx += "<div class=\"col-6 "+(i.label == 'anticipo' || i.label == 'cuentas'?'d-none':'')+" \" id=\"cnt_"+i.label+"\" >";
			tx += window[i.vis_elem_type+'_obj'].create(i).get_screen();
			tx +="</div>"
		});


		// tx += " \
		// 				<div class=\"form-group\" id=\"fg_srvc_select\">\
		// 	                <label for=\"srvc\">Servicio</label>\
		// 	                <select class=\"form-control\" id=\"srvc_select\"\"><option value=''>Selecciona</option>\
		// 	                        "+this.fill_select('srvs')+"</select>\
		// 	            </div>\
		// 	        </div>\
		// 		    <div class=\"col\">\
		// 		            <div class=\"form-group\" id=\"fg_srvc_monto\">\
		// 		                <label for=\"srvc_monto\">Monto</label>\
		// 		                <input type=\'number\' class=\"form-control\" id=\"srvc_monto\"\"><option value=''>Selecciona</option>\
		// 		                        "+this.fill_select('financ')+"</select>\
		// 		            </div>\
		// 		    </div>";

  //       tx +="<div class=\"row d-flex justify-content-center\">\
		// 			<div class=\"col\">\
		// 				<div class=\"form-group\" id=\"fg_srvc_select\">\
		// 	                <label for=\"srvc\">Servicio</label>\
		// 	                <select class=\"form-control\" id=\"srvc_select\"\"><option value=''>Selecciona</option>\
		// 	                        "+this.fill_select('srvs')+"</select>\
		// 	            </div>\
		// 	            <div class=\"form-group\" id=\"fg_financ_select\">\
		// 	                <label for=\"financ\">Financiacion</label>\
		// 	                <select class=\"form-control\" id=\"financ_select\"\"><option value=''>Selecciona</option>\
		// 	                        "+this.fill_select('financ')+"</select>\
		// 	            </div>\
		// 	        </div>\
		// 	     </div>";


		this._screen = tx;
	},

};


const history = {
	add:function(v){
		let m = TOP.history.find(function(x){return x.method == v.method })
		if(!m){
			TOP.history.push(v);
			console.log('history pushed',TOP.history);
		}
	},
	back:function(){
		if(TOP.hasOwnProperty('history2') && TOP.history2.length > 0){
			$('#main_container').html(TOP.history2[TOP.history2.length -1]);
			TOP.route = TOP.prev_route;
			return true;
		}
		if(TOP.history.length > 1){
			let x = TOP.history.pop()
			console.log('hist',TOP.history);
			front_call(TOP.history[TOP.history.length -1]);
		}else{
			$('#main_container').html('');
			location.reload(true);
		}
	},
	//****** 2 de julio 2020 ******
	//**** back to home de history
	//*****************************
	home:function(){
		if(TOP.history.length > 0){
				front_call(TOP.history[0]);

		}
	}
}
