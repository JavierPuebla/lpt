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
    let c = "<div class=\"dropdown\" ><div class=\"btn-group dropright\" style=\'z-index:2140000000;\'>"
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
