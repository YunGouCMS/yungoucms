var ajaxUtil={
	ajax : function(obj, callback) {
		if (obj.type == 'PUT' || obj.type == 'put') {
			obj.type = 'POST';
			obj.data['_method'] = 'PUT';
		} 
		if (obj.type == 'DELETE' || obj.type == 'delete') {
			obj.type = 'POST';
			obj.data['_method'] = 'DELETE';
		}
	
		if (null == obj.dataType || "undefined" == typeof (obj.dataType)
				|| "" == obj.dataType || obj.dataType == "json"
				|| obj.dataType == "JSON") {
			obj.dataType = "json"
		}
		if (null == obj.data || "undefined" == typeof (obj.data)
				|| "" == obj.data || obj.data == []) {
			obj.data == []
		}
		$.ajax({
			url : obj.url,
			type : obj.type,
			dataType : obj.dataType,
			data : obj.data,
			success : callback
		});
	}
}
