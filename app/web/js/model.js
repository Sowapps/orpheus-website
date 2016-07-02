
function Model(model) {
	
	this.type = model.data("model_type");
	this.list = model.parent();
	this.model = model.first().removeClass("item_model").addClass("model_item").detach();
	this.placeholder = $(".model_placeholder.item-"+this.type).detach();
	this.items = $.parseJSON($(":input[name="+this.type+"]").val());
	this.associative = this.model.data("associative");
	if( !isDefined(this.associative) ) {
		this.associative = isPureObject(items);
	}
	var _ = this;
	
	(function() {
		// Init - Load items in model
		for( var i in this.items ) {
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
			_.addItem(itemData);
		}
	})();
	
//	var models = {};
//	function getModel(_.type) {
//		return models[_.type].model;
//	}
//	function getPlaceholder(_.type) {
//		return models[_.type].placeholder;
//	}

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
		
//	 	console.log("Generated clone ", clone);
		return clone;
	}

	this.addItem = function(itemData) {
//	 	console.log("Model of "+_.type, $(".item_model.item-"+_.type), itemData);
		// Add clone to the end
//	 	console.log("model", getModel(_.type));
//	 	console.log("model", getModel(_.type));
		// Add after last item or model
//		var clone = modelClone(_.type, itemData);// do it before, init model
//		_.list.append(clone);
		_.list.append(_.createClone(itemData));
//	 	getModel(_.type).parent().find(".item.item-"+_.type).last().after(modelClone(_.type, itemData));
	}
	
	this.updateItem = function(itemRow, itemData) {
		// Update clone, preserve ID
		itemRow = $(itemRow);
//	 	console.log("itemRow.data ", itemRow.data(), itemRow);
		itemRow.after(_.createClone(itemData).attr("id", itemRow.attr("id"))).remove();
	}

	this.saveItems = function() {
		Config[itemName] = isArray(Config[itemName]) ? [] : {};
		var count = 0;
		$(".item.model_item.item-"+itemName).each(function(index) {
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
			Config[itemName][index] = data;
			count++;
		});
//		 		console.log("Config["+itemName+"]", Config[itemName]);
		if( isDefined(models[itemName]) ) {
			if( count ) {
				models[itemName].placeholder.hide().detach();
			} else {
				models[itemName].list.append(models[itemName].placeholder.show());
			}
		}
	}
}

$(function() {
	
	
});


