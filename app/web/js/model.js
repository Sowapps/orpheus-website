
function Model(model) {
	
	this.type = model.data("model_type");
	this.list = model.parent();
	this.model = model.first().removeClass("item_model").addClass("model_item").detach();
	this.placeholder = $(".model_placeholder.item-"+this.type).detach();
//	console.log(":input[name="+this.type+"]", $(":input[name="+this.type+"]").val());
//	this.items = $.parseJSON($(":input[name="+this.type+"]").val());
	this.associative = this.model.data("associative");
	var _ = this;
	
//	var models = {};
//	function getModel(_.type) {
//		return models[_.type].model;
//	}
//	function getPlaceholder(_.type) {
//		return models[_.type].placeholder;
//	}

	this.getInput = function() {
		return $(":input.input-"+_.type);
//		return $(":input[name="+_.type+"]");
	};
	
	this.createClone = function(itemData) {
//		var model = getModel(_.type);
//	 	console.log("Model ", model);
		var cloneHTML = _.model.outerHTML();
//			console.log("Model html", cloneHTML);
		// Fill
		// Replace all fields
		cloneHTML = cloneHTML.replace(new RegExp("\\{\\{([^\\}\\|]+)(?:\\|([^\\}\\|]+))?\\}\\}", 'g'), function myFunction(string, field, formatter, offset){
//				console.log("Replace", string, field, formatter, offset);
			if( !isSet(itemData[field]) ) {
				return string;
			}
			var value = itemData[field];
			if( isDefined(formatter) ) {
//					console.log("formatter is defined");
				var fn = window[formatter];
//					console.log("formatter function", fn);
				if( isFunction(fn) ) {
//						console.log("formatter is a function");
					value = fn(value);
				}
			}
			return value;
		});
//	 	console.log("cloneHTML ", cloneHTML);
		/*
		// Search fields
		for( var key in itemData ) {
			var value = data[_.type];
			if( !isString(value) ) {
				continue;
			}
			cloneHTML = cloneHTML.replace(new RegExp("\{\{"++"\}\}", 'g'), function myFunction(x){return x.toUpperCase();});
		}
		*/
		var clone = $(cloneHTML).data("itemdata", itemData).data("itemtype", _.type).uniqueId();
//	 	var clone = $(cloneHTML).removeClass("item_model").addClass("model_item").data("itemdata", itemData).data("itemtype", _.type).uniqueId();
		
		// Hide invalid requires
		clone.find("[data-model_require]").each(function() {
			if( !itemData[$(this).data("model_require")] ) {
				// Remove (or hide ?)
				$(this).remove();
			}
		});
		clone.data('model', _);
		
//	 	console.log("Generated clone ", clone);
		return clone;
	};

	this.addItem = function(itemData, noSave) {
//	 	console.log("Model of "+_.type, $(".item_model.item-"+_.type), itemData);
		// Add clone to the end
//	 	console.log("model", getModel(_.type));
//	 	console.log("model", getModel(_.type));
		// Add after last item or model
//		var clone = modelClone(_.type, itemData);// do it before, init model
//		_.list.append(clone);
		_.list.append(_.createClone(itemData));
		if( !noSave ) {
			_.saveItems();
		}
//	 	getModel(_.type).parent().find(".item.item-"+_.type).last().after(modelClone(_.type, itemData));
	};
	
//	this.updateItem = function(itemRow, itemData, noSave) {
	this.updateItem = function(itemRow, itemData) {
		// Update clone, preserve ID
		itemRow = $(itemRow);
//	 	console.log("itemRow.data ", itemRow.data(), itemRow);
		itemRow.after(_.createClone(itemData).attr("id", itemRow.attr("id"))).remove();
//		if( !noSave ) {
		_.saveItems();
//		}
	};

	this.saveItems = function() {
//		Config[itemName] = isArray(Config[itemName]) ? [] : {};
		var items = _.associative ? {} : [];
		var count = 0;
		$(".item.model_item.item-"+_.type).each(function(index) {
			var data = jQuery.extend({}, $(this).data("itemdata"));
//		 			console.log(data);
			if( isDefined(data._key_) ) {
				index = data._key_;
				delete data._key_;
			}
			if( isDefined(data._value_) ) {
				data = data._value_;
			}
//		 			console.log(index+" => ", data);
			items[index] = data;
			count++;
		});
//		 		console.log("Config["+itemName+"]", Config[itemName]);
//		if( isDefined(models[itemName]) ) {
		if( count ) {
			_.placeholder.hide().detach();
		} else {
			_.list.append(_.placeholder.show());
		}
		_.getInput().val(JSON.stringify(items));
	};
	
//	console.log("OUT - Init model "+_.type);
	
	(function() {
//		console.log("IN - Init model "+_.type);
		// Init - Load items in model
		Model.instances[_.type] = _;
//		var items = $.parseJSON($(":input[name="+_.type+"]").val());
		var items = $.parseJSON(_.getInput().val());
		if( !isDefined(_.associative) ) {
			_.associative = isPureObject(items);
		}
		for( var i in items ) {
			var itemData = items[i];
			if( isScalar(itemData) ) {
				itemData = {"_value_": itemData};
			} else
			if( !isObject(itemData) ) {
				continue;
			}
			if( _.associative ) {
				itemData._key_ = i;
			}
			_.addItem(itemData, true);
		}
	})();
}

Model.instances = {};

Model.get = function(type) {
//	return $(".item_model.item-"+type);
	var model = Model.instances[type];
	return model ? model.model : null;
};

$.fn.model = function(option, param1) {
	var model = $(this).data("model");
	if( !model ) {
		console.warn("No model for item ", this);
		return null;
	}
	console.log("option => "+option);
	switch( option ) {
		case "removeItem": {
			$(this).remove();
			model.saveItems();
		};
		case "addItem": {
			console.log("AddItem to model ", model, "with param", param1);
			model.addItem(param1);
		};
		case "updateItem": {
			// TODO: Check is not model
			model.updateItem($(this), param1);
		};
//		case "save": {
//			model.saveItems();
//		};
	}
	return model;
};

$(function() {
	
	$(".item.item_model").each(function() {
		$(this).data("model", new Model($(this)));
	});
	
});


function url_host(url) {
	if( !url ) {
		return "";
	}
	var location = getLocation(url);
	return location.host;
}

