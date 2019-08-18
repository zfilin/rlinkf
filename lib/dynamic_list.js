$( document ).ready(function() {

	var php_script = 'index.php?json=true';

	var template = {item:"",add:"",edit:""};	
	$.getJSON(php_script, {action: "get_templates", list_id: "0"}, function(templates_data){
		template = templates_data;
			
		$('ul.dynamic_list').each(function(index) {
			var list=$(this);		
			$.getJSON(php_script, {action: "get_all", list_id: list.prop('id')}, function(list_data){
				if( typeof list_data === 'string' ) alert(list_data);
				else {
					list.html('\n');
					$.each(list_data,function(index,item_data){
						var _tmpl=template.item.replace(/%%index%%/g,index).replace(/%%data%%/g,item_data);
						list.append(_tmpl);
					});
					list.append(template.add);
				}
			});		
		});

	});	
	
	$("ul.dynamic_list").on("click", "div.controls .delete", function(e){
		
		var list=$(e.target).closest("ul.dynamic_list");
		var item=$(e.target).closest("li");
		$.get(php_script, {action: 'delete', list_id: list.prop('id'), item_id: item.prop('id')}, function(del_res){
			if(isNaN(del_res)) alert(del_res);
			else {
				item.animate({ opacity: 'hide' }, "fast");
				item.remove();
			}
		});
		e.preventDefault();
	}); 
	
	$("ul.dynamic_list").on("click", "div.controls .add", function(e){

		var list=$(e.target).closest("ul.dynamic_list");
		list.children("li.editing").remove();
		list.children("li").show();
		
		var item=$(e.target).closest('li.add_item');
		var item_id=item.children('input.item_id');
		var content=item.children('input.item_content');
		
		$.getJSON(php_script, {action: 'add', list_id: list.prop('id'), item_id: item_id.prop('value'), content: content.prop('value')}, function(index){
			if( typeof index === 'string' ) alert(index);
			else {
				item_id_res=index.item_id;
				var _tmpl=template.item.replace(/%%index%%/g,item_id_res).replace(/%%data%%/g,content.prop('value'));
				item.before(_tmpl);
				item_id.prop('value','');
				content.prop('value','');
			}
		});
		e.preventDefault();
		
	});

	$("ul.dynamic_list").on("click", "div.controls .edit", function(e){

		var list=$(e.target).closest("ul.dynamic_list");
		list.children("li.editing").remove();
		list.children("li").show();
		
		var item=$(e.target).closest("li");
		var content=item.children('div.item_content'); 
		var _tmpl=template.edit.replace(/%%index%%/g,item.prop('id')).replace(/%%data%%/g,content.html());
		item.after(_tmpl);
		item.hide();
		e.preventDefault();
		
	});

	$("ul.dynamic_list").on("click", "div.controls .cancel", function(e){
		var list=$(e.target).closest("ul.dynamic_list");
		list.children("li.editing").remove();
		list.children("li").show();
		e.preventDefault();
	});
	
	$("ul.dynamic_list").on("click", "div.controls .ok", function(e){

		var list=$(e.target).closest("ul.dynamic_list");
		var item=$(e.target).closest("li");
		var content=item.children("input.item_content");
		
		$.get(php_script, {action: 'edit', list_id: list.prop('id'), item_id: item.prop('id'), content: content.prop('value')}, function(edit_res){
			if(isNaN(edit_res)) alert(edit_res);
			else {
				var _tmpl=template.item.replace(/%%index%%/g,item.prop('id')).replace(/%%data%%/g,content.prop('value'));
				item.after(_tmpl);
				item.remove();
				list.children("li:hidden").remove();
			}
		});
		e.preventDefault();

	});
	
});
